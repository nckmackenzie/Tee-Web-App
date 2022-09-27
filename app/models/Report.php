<?php
class Report
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function Getfeepayments($data)
    {
        $this->db->query('CALL sp_feepayment_by_date(:sdate,:edate,:cid)');
        $this->db->bind(':sdate',date('Y-m-d',strtotime($data['sdate'])));
        $this->db->bind(':edate',date('Y-m-d',strtotime($data['edate'])));
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }
}