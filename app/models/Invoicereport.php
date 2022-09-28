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

    public function GetDue()
    {
        $this->db->query('CALL sp_get_due_invoices(:cid)');
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }
}