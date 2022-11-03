<?php 
class Pettycashreceipt
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetReceipts()
    {
        return loadresultset($this->db->dbh,'SELECT * FROM vw_petty_cash',[]);
    }

    public function GetReceiptNo()
    {
        return getuniqueid($this->db->dbh,'ReceiptNo','pettycash',$_SESSION['centerid'],false);
    }

    public function CheckReferenceExists($ref,$id)
    {
        $count =  getdbvalue($this->db->dbh,'SELECT COUNT(*) 
                                             FROM pettycash 
                                             WHERE (Deleted = 0) AND (Reference = ?) AND (ID <> ?)',[$ref,$id]);
        if((int)$count > 0){
            return false;
        }else{
            return true;
        }
    }

    public function CreateUpdate($data)
    {
        try {
            $this->db->dbh->beginTransaction();
            $receipt = $this->GetReceiptNo();

            if(!$data['isedit']){
                $this->db->query('INSERT INTO pettycash (ReceiptNo,TransactionDate,Debit,Reference,Narration,TransactionType,CenterId)
                                  VALUES(:receipt,:tdate,:debit,:ref,:narr,:ttype,:cid)');
                $this->db->bind(':receipt',$receipt);
            }else{
                $this->db->query('UPDATE pettycash SET TransactionDate=:tdate,Debit=:debit,Reference=:ref,Narration=:narr
                                  WHERE (ID = :id)');
            }
            $this->db->bind(':tdate',$data['receiptdate']);
            $this->db->bind(':debit',$data['amount']);
            $this->db->bind(':ref',$data['reference']);
            $this->db->bind(':narr',$data['narration']);
            if(!$data['isedit']){
                $this->db->bind(':ttype',10);
                $this->db->bind(':cid',$_SESSION['centerid']);
            }else{
                $this->db->bind(':id',$data['id']);
            }

            $this->db->execute();
            $tid = $data['isedit'] ? $data['id'] : $this->db->dbh->lastInsertId();

            if($data['isedit']){
                $this->db->query('DELETE FROM ledger WHERE (TransactionType = 10) AND (TransactionId = :id)');
                $this->db->bind(':id',$data['id']);
                $this->db->execute();

                $this->db->query('DELETE FROM bankpostings WHERE (TransactionType = 10) AND (TransactionId = :id)');
                $this->db->bind(':id',$data['id']);
                $this->db->execute();
            }

            savetoledger($this->db->dbh,$data['receiptdate'],'cash at bank',0,$data['amount'],
                         $data['narration'],3,10,$tid,$_SESSION['centerid']);
            savetoledger($this->db->dbh,$data['receiptdate'],'petty cash',$data['amount'],0,
                         $data['narration'],3,10,$tid,$_SESSION['centerid']);
            savebankposting($this->db->dbh,$data['receiptdate'],0,null,0,$data['amount'],
                            $data['reference'],$data['narration'],10,$tid,$_SESSION['centerid']);             
            
            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
            
        } catch (PDOException $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            error_log($e->getMessage(),0);
        }
    }

    public function GetReceipt($id)
    {
        $this->db->query('SELECT * FROM pettycash WHERE (ID = :id)');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    public function Delete($id)
    {
        try {
            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE pettycash SET Deleted = 1
                              WHERE (ID = :id)');
            $this->db->bind(':id',$id);
            $this->db->execute();
           
            $this->db->query('UPDATE ledger SET Deleted = 1 WHERE (TransactionType = 10) AND (TransactionId = :id)');
            $this->db->bind(':id',$id);
            $this->db->execute();

            $this->db->query('UPDATE bankpostings SET Deleted = 1 WHERE (TransactionType = 10) AND (TransactionId = :id)');
            $this->db->bind(':id',$id);
            $this->db->execute();
                        
            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
            
        } catch (PDOException $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            error_log($e->getMessage(),0);
        }
    }
}