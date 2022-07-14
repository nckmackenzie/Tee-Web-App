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

            $this->db->query('INSERT INTO receiptsheader (ReceiptDate,ReceiptType,MtnNo,GrnNo,CenterId) 
                              VALUES(:rdate,:rtype,:mtn,:grn,:cid)');
            $this->db->bind(':rdate',$data['date']);
            $this->db->bind(':rtype',$data['type'] === 'grn' ? 1 : 2);
            $this->db->bind(':mtn',!empty($data['mtn']) ? $data['mtn'] : null);
            $this->db->bind(':grn',$data['reference']);
            $this->db->bind(':cid',$_SESSION['centerid']);
            $this->db->execute();

            $id = $this->db->dbh->lastInsertId();

            for ($i=0; $i < count($data['booksid']); $i++) { 
                $this->db->query('INSERT INTO receiptsdetails(HeaderId,BookId,Qty) VALUES(:hid,:bid,:qty)');
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
}