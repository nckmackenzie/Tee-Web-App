<?php

class Sale
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetSales()
    {
        $this->db->query('SELECT * FROM vw_sales WHERE (CenterId = :cid)');
        $this->db->bind(':cid',intval($_SESSION['centerid']));
        return $this->db->resultset();
    }
}