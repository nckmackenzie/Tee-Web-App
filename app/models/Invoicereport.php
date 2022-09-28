<?php
class Invoicereport
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetSuppliers()
    {
        $this->db->query('SELECT ID,UCASE(SupplierName) As SupplierName FROM suppliers WHERE (Deleted = 0)');
        return $this->db->resultset();
    }
}