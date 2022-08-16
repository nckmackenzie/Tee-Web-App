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

    public function GetSuppliers()
    {
        $this->db->query('SELECT ID,UCASE(SupplierName) AS SupplierName 
                          FROM suppliers 
                          WHERE (Deleted = 0)
                          ORDER BY SupplierName');
        return $this->db->resultset();
    }

    public function GetBooks()
    {
        $this->db->query('SELECT ID,UCASE(Title) AS BookName 
                          FROM books 
                          WHERE (Deleted = 0) AND (Active =1)
                          ORDER BY BookName');
        return $this->db->resultset();
    }

    public function GetVatTypes()
    {
        $this->db->query('SELECT ID,UCASE(VatType) AS VatType 
                          FROM vat_types');
        return $this->db->resultset();
    }

    public function GetVats()
    {
        $this->db->query('SELECT ID,UCASE(vat) AS Vat
                          FROM vat');
        return $this->db->resultset();
    }
}