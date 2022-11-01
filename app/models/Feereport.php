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
}