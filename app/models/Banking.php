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

    public function GetValues($data)
    {
        $cleareddeposits = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Debit),0) As SumOfValue
                                                      FROM bankpostings
                                                      WHERE (Deleted = 0) AND (IsMpesa = 0) AND (Cleared = 1)
                                                             AND (TransactionDate BETWEEN ? AND ?)',[$data['sdate'],$data['edate']]);
        $clearedwithdrawals = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Credit),0) As SumOfValue
                                                         FROM bankpostings
                                                         WHERE (Deleted = 0) AND (IsMpesa = 0) AND (Cleared = 1)
                                                         AND (TransactionDate BETWEEN ? AND ?)',[$data['sdate'],$data['edate']]);
        $uncleareddeposits = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Debit),0) As SumOfValue
                                                        FROM bankpostings
                                                        WHERE (Deleted = 0) AND (IsMpesa = 0) AND (Cleared = 0)
                                                        AND (TransactionDate BETWEEN ? AND ?)',[$data['sdate'],$data['edate']]);
        $unclearedwithdrawals = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Credit),0) As SumOfValue
                                                           FROM bankpostings
                                                           WHERE (Deleted = 0) AND (IsMpesa = 0) AND (Cleared = 0)
                                                           AND (TransactionDate BETWEEN ? AND ?)',[$data['sdate'],$data['edate']]); 
        
        return [$cleareddeposits,$clearedwithdrawals,$uncleareddeposits,$unclearedwithdrawals];
    }

    public function GetUnclearedReports($data)
    {
        $sql = '';
        if($data['type'] === 'deposits'){
            $sql = 'SELECT TransactionDate,IFNULL(Debit,0) As Amount,Reference,Narration 
                    FROM bankpostings
                    WHERE (Deleted = 0) AND (Cleared = 0) AND (TransactionDate BETWEEN ? AND ?) 
                          AND (Debit > 0) AND (IsMpesa = 0)
                    ORDER BY TransactionDate';
        }elseif ($data['type'] === 'withdrawals') {
            $sql = 'SELECT TransactionDate,IFNULL(Credit,0) As Amount,Reference,Narration 
                    FROM bankpostings
                    WHERE (Deleted = 0) AND (Cleared = 0) AND (TransactionDate BETWEEN ? AND ?) 
                          AND (Credit > 0)  AND (IsMpesa = 0)
                    ORDER BY TransactionDate';
        }
        return loadresultset($this->db->dbh,$sql,[$data['sdate'],$data['edate']]);
    }
}