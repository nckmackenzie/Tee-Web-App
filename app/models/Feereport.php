<?php
class Feereport
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function Getfeepayments($data)
    {
        $this->db->query('CALL sp_feepayment_by_date(:sdate,:edate)');
        $this->db->bind(':sdate',date('Y-m-d',strtotime($data['sdate'])));
        $this->db->bind(':edate',date('Y-m-d',strtotime($data['edate'])));
        return $this->db->resultset();
    }

    public function GetSemisters()
    {
        $sql = 'SELECT ID,UCASE(SemisterName) AS SemisterName FROM semisters WHERE (Deleted = 0) ORDER BY SemisterName';
        return loadresultset($this->db->dbh,$sql,[]);
    }

    public function GetSemisterBalances($semister)
    {
        $this->db->query('CALL sp_studentbalances(:semister)');
        $this->db->bind(':semister',$semister);
        return $this->db->resultset();
    }
}