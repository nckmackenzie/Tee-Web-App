<?php
class Stock
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    //get receipts
    public function GetReceipts()
    {
        $this->db->query('SELECT * FROM vw_receipts WHERE CenterId = :cid');
        $this->db->bind(':cid',$_SESSION['centerid']);
        return $this->db->resultset();
    }
}