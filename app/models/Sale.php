<?php

class Sale
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetSales()
    {
        $this->db->query('SELECT * FROM vw_sales WHERE (CenterId = :cid)');
        $this->db->bind(':cid',intval($_SESSION['centerid']));
        return $this->db->resultset();
    }

    public function GetStudentsOrGroups($type)
    {
        if($type === 'student'){
            $this->db->query('SELECT ID,UCASE(StudentName) AS CriteriaName 
                              FROM   students 
                              WHERE  (Deleted =0) AND (StatusId = 1)');
        }elseif ($type === 'group') {
            $this->db->query('SELECT ID,UCASE(GroupName) AS CriteriaName 
                              FROM   groups 
                              WHERE  (Deleted =0) AND (Active = 1)');
        }
        
        return $this->db->resultset();
    }
    
    public function GetSaleId()
    {
        $this->db->query("SELECT COUNT(*) FROM sales_header WHERE (CenterId = :cid) AND (Deleted = 0)");
        $this->db->bind(':cid',intval($_SESSION['centerid']));
        if(intval($this->db->getvalue()) === 0){
            return 1;
        }else{
            $this->db->query('SELECT SalesID FROM sales_header 
                              WHERE (CenterId = :cid) AND (Deleted = 0) 
                              ORDER BY SalesID DESC LIMIT 1');
            $this->db->bind(':cid',intval($_SESSION['centerid']));
            return intval($this->db->getvalue()) + 1;
        }
    }

    public function GetBooks()
    {
        $this->db->query("SELECT ID,UCASE(Title) AS Title 
                          FROM books 
                          WHERE (Deleted = 0) AND (Active = 1)
                          ORDER BY Title");
        return $this->db->resultset();
    }

    public function GetStockAndRate($date,$id)
    {
        $this->db->query("SELECT fn_getprice(b.ID,:cdate) As Rate,
                                 fn_getstock(b.ID,:cdate,:cid) As Stock 
                          FROM `books` b WHERE b.ID = :bid");
        $this->db->bind(':cdate',$date);
        $this->db->bind(':cid',intval($_SESSION['centerid']));
        $this->db->bind(':bid',$id);
        return $this->db->single();
    }

    function GetItemBuyingPrice($date,$item){
        $this->db->query('SELECT 
                            IFNULL(BuyingPrice, 0) AS bp
                          FROM
                            prices
                          WHERE
                            (:tdate BETWEEN StartDate AND EndDate)
                            AND (BookId = :bid)
                            AND (Deleted = 0)');
        $this->db->bind(':tdate',$date);
        $this->db->bind(':bid',intval($item));
        return floatval($this->db->getvalue());
    }

    function GetBuyerName($type,$id){
        $this->db->query('SELECT '.ucwords($type).'Name FROM '.$type.'s WHERE ID = :id');
        $this->db->bind(':id',intval($id));
        return ucwords($this->db->getvalue());
    }   

    function Save($data){
        $desc = 'Sale of '.count($data['booksid']) . ' book(s) to '.$this->GetBuyerName($data['type'],$data['studentorgroup']);
        try {
            $this->db->dbh->beginTransaction();

            $this->db->query('INSERT INTO sales_header (SalesID,SalesDate,SaleType,GroupId,StudentId,SubTotal,
                                          Discount,NetAmount,AmountPaid,Balance,CenterId) 
                              VALUES(:saleid,:sdate,:stype,:gid,:student,:stotal,:discount,:net,:paid,:bal,:cid)');
            $this->db->bind(':saleid',intval($data['saleid']));
            $this->db->bind(':sdate',$data['sdate']);
            $this->db->bind(':stype',$data['type']);
            $this->db->bind(':gid',$data['type'] === 'group' ? $data['studentorgroup'] : null);
            $this->db->bind(':student',$data['type'] === 'student' ? $data['studentorgroup'] : null);
            $this->db->bind(':stotal',$data['subtotal']);
            $this->db->bind(':discount',!empty($data['discount']) ? $data['discount'] : 0);
            $this->db->bind(':net',!empty($data['net']) ? $data['net'] : 0);
            $this->db->bind(':paid',!empty($data['paid']) ? $data['paid'] : 0);
            $this->db->bind(':bal',!empty($data['balance']) ? $data['balance'] : 0);
            $this->db->bind(':cid',$_SESSION['centerid']);
            $this->db->execute();

            $tid = $this->db->dbh->lastInsertId();

            for ($i=0; $i < count($data['booksid']); $i++) { 
                $bp = $this->GetItemBuyingPrice($data['sdate'],$data['booksid'][$i]);
                $this->db->query('INSERT INTO sales_details (HeaderId,BookId,Qty,BoughtValue,SellingValue) 
                              VALUES(:hid,:bid,:qty,:bought,:selling)');
                $this->db->bind(':hid',$tid);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->bind(':bought',$bp);
                $this->db->bind(':selling',$data['rates'][$i]);
                $this->db->execute();
            }

            savetoledger($this->db->dbh,$data['sdate'],'supplies',0,$data['paid'],$desc,3,1,$tid,$_SESSION['centerid']);
            if(intval($data['paymethod']) === 1){
                savetoledger($this->db->dbh,$data['sdate'],'cash at hand',$data['paid'],0,$desc,3,1,$tid,$_SESSION['centerid']);
            }else{
                savetoledger($this->db->dbh,$data['sdate'],'cash at bank',$data['paid'],0,$desc,3,1,$tid,$_SESSION['centerid']);
            }

            if(!$this->db->dbh->commit()){
                return  false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            if ($this->db->dbh->inTransaction()) {
                $this->db->dbh->rollBack();
            }
            throw $e;
            return false;
        }
    }

    function Update($data){
        $desc = 'Sale of '.count($data['booksid']) . ' book(s) to '.$this->GetBuyerName($data['type'],$data['studentorgroup']);
        try {
            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE sales_header SET SalesDate=:sdate,SaleType=:stype,GroupId=:gid,StudentId=:student
                                                      ,SubTotal=:stotal,Discount=:discount,NetAmount=:net,
                                                      AmountPaid=:paid,Balance=:bal
                              WHERE (ID=:id)');
            $this->db->bind(':sdate',$data['sdate']);
            $this->db->bind(':stype',$data['type']);
            $this->db->bind(':gid',$data['type'] === 'group' ? $data['studentorgroup'] : null);
            $this->db->bind(':student',$data['type'] === 'student' ? $data['studentorgroup'] : null);
            $this->db->bind(':stotal',$data['subtotal']);
            $this->db->bind(':discount',!empty($data['discount']) ? $data['discount'] : 0);
            $this->db->bind(':net',!empty($data['net']) ? $data['net'] : 0);
            $this->db->bind(':paid',!empty($data['paid']) ? $data['paid'] : 0);
            $this->db->bind(':bal',!empty($data['balance']) ? $data['balance'] : 0);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM sales_details WHERE HeaderId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM ledger WHERE TransactionType = 1 AND TransactionId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            for ($i=0; $i < count($data['booksid']); $i++) { 
                $bp = $this->GetItemBuyingPrice($data['sdate'],$data['booksid'][$i]);
                $this->db->query('INSERT INTO sales_details (HeaderId,BookId,Qty,BoughtValue,SellingValue) 
                              VALUES(:hid,:bid,:qty,:bought,:selling)');
                $this->db->bind(':hid',$data['id']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->bind(':bought',$bp);
                $this->db->bind(':selling',$data['rates'][$i]);
                $this->db->execute();
            }

            savetoledger($this->db->dbh,$data['sdate'],'supplies',0,$data['paid'],$desc,3,1,$data['id'],$_SESSION['centerid']);
            if(intval($data['paymethod']) === 1){
                savetoledger($this->db->dbh,$data['sdate'],'cash at hand',$data['paid'],0,$desc,3,1,$data['id'],$_SESSION['centerid']);
            }else{
                savetoledger($this->db->dbh,$data['sdate'],'cash at bank',$data['paid'],0,$desc,3,1,$data['id'],$_SESSION['centerid']);
            }

            if(!$this->db->dbh->commit()){
                return  false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            if ($this->db->dbh->inTransaction()) {
                $this->db->dbh->rollBack();
            }
            throw $e;
            return false;
        }
    }

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            return $this->Save($data);
        }else{
            return $this->Update($data);
        }
    }

    public function GetSaleHeader($id)
    {
        $this->db->query('SELECT * FROM sales_header WHERE id = :id');
        $this->db->bind(':id',intval($id));
        return $this->db->single();
    }

    public function GetSaleDetails($id)
    {
        $this->db->query('SELECT * FROM vw_salesdetails WHERE HeaderId = :id');
        $this->db->bind(':id',intval($id));
        return $this->db->resultset();
    }
}