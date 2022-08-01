<?php
class Stock
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    //get receipts
    public function GetReceiptsOrTransfers($type)
    {
        $this->db->query("CALL sp_getreceiptsortransfers(:action,:cid)");
        $this->db->bind(':action',$type);
        $this->db->bind(':cid',$_SESSION['centerid']);
        return $this->db->resultset();
    }

    //get books
    public function GetBooks()
    {
        $this->db->query('SELECT ID,UCASE(Title) As Title FROM books WHERE Deleted = 0 ORDER BY Title ASC');
        return $this->db->resultset();
    }

    //get mtns
    public function GetMtns()
    {
        $this->db->query('SELECT ID,UCASE(MtnNo) AS Mtn FROM transfersheader WHERE (Deleted=0) AND (ToCenter =:cid)');
        $this->db->bind(':cid',$_SESSION['centerid']);
        return $this->db->resultset();
    }

    //get book price
    public function GetPrice($book,$date)
    {
       $this->db->query('SELECT fn_getprice(:book,:rdate) As Price');
       $this->db->bind(':book',(int)$book);
       $this->db->bind(':rdate',date('Y-m-d',strtotime($date)));
       return $this->db->getvalue();
    }

    //create new receipt
    public function CreateUpdateReceipt($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('INSERT INTO receiptsheader (ReceiptDate,ReceiptType,TransferId,GrnNo,CenterId) 
                              VALUES(:rdate,:rtype,:mtn,:grn,:cid)');
            $this->db->bind(':rdate',$data['date']);
            $this->db->bind(':rtype',$data['type'] === 'grn' ? 1 : 2);
            $this->db->bind(':mtn',!empty($data['mtn']) ? $data['mtn'] : null);
            $this->db->bind(':grn',$data['reference']);
            $this->db->bind(':cid',$_SESSION['centerid']);
            $this->db->execute();

            $id = $this->db->dbh->lastInsertId();

            for ($i=0; $i < count($data['booksid']); $i++) { 
                $this->db->query('INSERT INTO receiptsdetails(HeaderID,BookId,Qty) VALUES(:hid,:bid,:qty)');
                $this->db->bind(':hid',$id);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty', !empty($data['qtys'][$i]) ? $data['qtys'][$i] : 0);
                $this->db->execute();

                $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,Reference,
                                          TransactionType,TransactionId,CenterId) 
                              VALUES(:tdate,:bid,:qty,:ref,:ttype,:tid,:cid)');
                $this->db->bind(':tdate',$data['date']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->bind(':ref',$data['reference']); 
                $this->db->bind(':ttype',2);
                $this->db->bind(':tid',$id);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
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

    public function GetCenters()
    {
        $this->db->query('SELECT ID,
                                 UCASE(CenterName) AS CenterName 
                          FROM   centers 
                          WHERE  Deleted = 0 AND ID <> :id
                          ORDER BY CenterName');
        $this->db->bind(':id',$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function SaveTransfer($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('INSERT INTO transfersheader (TransferDate,MtnNo,ToCenter,CenterId) 
                              VALUES(:tdate,:mtn,:tocenter,:cid)');
            $this->db->bind(':tdate',$data['date']);
            $this->db->bind(':mtn',!empty($data['mtn']) ? $data['mtn'] : null);
            $this->db->bind(':tocenter',$data['center']);
            $this->db->bind(':cid',$_SESSION['centerid']);
            $this->db->execute();

            $id = $this->db->dbh->lastInsertId();

            for ($i=0; $i < count($data['booksid']); $i++) { 
                $this->db->query('INSERT INTO transferdetails(HeaderID,BookId,Qty) VALUES(:hid,:bid,:qty)');
                $this->db->bind(':hid',$id);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->execute();

                $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,Reference,
                                          TransactionType,TransactionId,CenterId) 
                              VALUES(:tdate,:bid,:qty,:ref,:ttype,:tid,:cid)');
                $this->db->bind(':tdate',$data['date']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->bind(':ref',$data['mtn']); 
                $this->db->bind(':ttype',3);
                $this->db->bind(':tid',$id);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
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
    public function UpdateTransfer($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE transfersheader SET TransferDate=:tdate,MtnNo=:mtn,ToCenter=:tocenter
                              WHERE (ID=:id)');
            $this->db->bind(':tdate',$data['date']);
            $this->db->bind(':mtn',!empty($data['mtn']) ? $data['mtn'] : null);
            $this->db->bind(':tocenter',$data['center']);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM transferdetails WHERE HeaderID = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM stockmovements WHERE TransactionType = 3 AND TransactionId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            for ($i=0; $i < count($data['booksid']); $i++) { 
                $this->db->query('INSERT INTO transferdetails(HeaderID,BookId,Qty) VALUES(:hid,:bid,:qty)');
                $this->db->bind(':hid',$data['id']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->execute();

                $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,Reference,
                                          TransactionType,TransactionId,CenterId) 
                              VALUES(:tdate,:bid,:qty,:ref,:ttype,:tid,:cid)');
                $this->db->bind(':tdate',$data['date']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->bind(':ref',$data['mtn']); 
                $this->db->bind(':ttype',3);
                $this->db->bind(':tid',$data['id']);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
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
    public function CreateUpdateTransfer($data)
    {
        if(!$data['isedit']){
            return $this->SaveTransfer($data);
        }elseif($data['isedit'] && $data['allowedit']){
            return $this->UpdateTransfer($data);
        }
    }
    public function GetTransfereHeader($id)
    {
        $this->db->query('SELECT h.ID,
                                 h.TransferDate,
                                 h.MtnNo,
                                 h.ToCenter,
                                 fn_checktransferreceived(h.ID) AS Received,
                                 CenterId
                          FROM   transfersheader h
                          WHERE  h.ID = :id');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    public function GetTransferDetails($id)
    {
        $this->db->query('SELECT 
                            d.BookId,
                            UCASE(b.Title) As Title,
                            d.Qty
                          FROM 
                            transferdetails d 
                                INNER JOIN 
                            books b 
                                ON 
                            d.BookId =b.ID
                          WHERE 
                            d.HeaderID = :id');
        $this->db->bind(':id',$id);
        return $this->db->resultset();
    }
    public function CheckGrnMtnAvailability($type,$mtn,$id)
    {
        $this->db->query('SELECT fn_checkmtngrnavailability(:ttype,:mtn,:id)');
        $this->db->bind(':ttype',$type);
        $this->db->bind(':mtn',$mtn);
        $this->db->bind(':id',$id);
        if((int)$this->db->getvalue() > 0){
            return false;
        }else{
            return true;
        }
    }

    public function DeleteTransfer($id)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE transfersheader SET Deleted=1
                              WHERE (ID=:id)');
            $this->db->bind(':id',$id);
            $this->db->execute();

            $this->db->query('UPDATE stockmovements SET Deleted=1 WHERE TransactionType = 3 AND TransactionId = :id');
            $this->db->bind(':id',$id);
            $this->db->execute();

            if(!$this->db->dbh->commit()){
                return false;
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

    public function CheckStockAvailability($book,$date,$qty)
    {
        $this->db->query('SELECT fn_getstock(:bid,:rdate,:cid)');
        $this->db->bind(':bid',$book);
        $this->db->bind(':rdate',$date);
        $this->db->bind(':cid',$_SESSION['centerid']);
        if((int)$this->db->getvalue() < $qty){
            return false;
        }else{
            return true;
        }
    }

    public function ValidateReceiptVsTransferDate($date,$mtn)
    {
        $this->db->query('SELECT TransferDate FROM transfersheader WHERE ID = :id');
        $this->db->bind(':id',$mtn);
        $transferdate = date('Y-m-d',strtotime($this->db->getvalue()));
        if($transferdate > $date){
            return false;
        }else{
            return true;
        }
    }
}