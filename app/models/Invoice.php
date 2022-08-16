<?php
class Invoice
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetInvoices()
    {
        $this->db->query('SELECT * FROM vw_invoices WHERE (CenterId = :id)');
        $this->db->bind(':id',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }
}