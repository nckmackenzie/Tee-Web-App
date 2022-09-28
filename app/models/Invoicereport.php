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

    public function GetDueOrWithBalances($type)
    {
        if($type === 'due'){
            $this->db->query('CALL sp_get_due_invoices(:cid)');
        }elseif ($type === 'balances') {
            $this->db->query('CALL sp_get_invoices_with_balances(:cid)');
        }
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function GetInvoicesByDateAndSupplier($data)
    {
        $this->db->query('CALL sp_get_invoices_by_supplier_date(:sdate,:edate,:cid,:supplier)');
        $this->db->bind(':sdate',$data['sdate']);
        $this->db->bind(':edate',$data['edate']);
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        $this->db->bind(':supplier',$data['type'] === 'bysupplier' ? (int)$data['supplier'] : NULL);
        return $this->db->resultset();
    }
}