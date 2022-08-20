<?php
class Fee
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetFees()
    {
        $this->db->query('SELECT * FROM vw_feepayments WHERE CenterId = :cid');
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }
}