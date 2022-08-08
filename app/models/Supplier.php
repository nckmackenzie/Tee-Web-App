<?php
class Supplier
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetSuppliers()
    {
        $this->db->query('SELECT * FROM vw_suppliers');
        return $this->db->resultset();
    }
}