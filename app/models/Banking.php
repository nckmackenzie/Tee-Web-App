<?php
class Banking
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    //fun get transactions
    public function GetTransactions($data)
    {
        $type = $data['type'] === 'bankings' ? 0 : 1;
        $sql = "SELECT ID,
                       TransactionDate,
                       IF(Debit > 0 ,Debit,Credit * -1) As Amount,
                       IF(Debit > 0 ,'deposit','withdrawal') As TransactionType,
                       Reference
                FROM   bankpostings 
                WHERE  (Deleted = 0) AND (Cleared = 0) AND (TransactionDate BETWEEN ? AND ?) AND (IsMpesa = ?)
                ORDER BY TransactionDate";
        return loadresultset($this->db->dbh,$sql,[$data['sdate'],$data['edate'],$type]);
    }

    //clear transactions
    public function ClearTransactions($bankings)
    {
        try {
            $this->db->dbh->beginTransaction();

            for ($i=0; $i < count($bankings); $i++) { 
                $cdate = $bankings[$i]->clearDate;
                $this->db->query('UPDATE bankpostings SET Cleared = 1,ClearedDate = :cdate WHERE (ID = :id)');
                $this->db->bind(':cdate',$cdate);
                $this->db->bind(':id',$bankings[$i]->id);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }

        } catch (PDOException $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            error_log($e->getMessage(),0);
            return false;
        }
    }
}