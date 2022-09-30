<?php
class Invoicereport
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetSuppliers($sort)
    {
        $this->db->query('SELECT ID,UCASE(SupplierName) As FieldName 
                          FROM suppliers 
                          WHERE (Deleted = 0)
                          ORDER BY FieldName '.$sort.'');
        return $this->db->resultset();
    }

    public function GetInvoices()
    {
        $this->db->query('SELECT UCASE(InvoiceNo) AS ID,UCASE(InvoiceNo) As FieldName 
                          FROM invoice_header 
                          WHERE (Deleted = 0) AND (CenterId = :cid)
                          ORDER BY FieldName DESC');
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
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

    //fetch payments from db
    public function GetPayments($data)
    {
        $sql = 'SELECT * FROM vw_supplier_payments';
        if($data['type'] === 'bysupplier'){
            $sql .= ' WHERE (SupplierId =:sid) AND (PaymentDate BETWEEN :sdate AND :edate)';
        }elseif ($data['type'] === 'bydate') {
            $sql .= ' WHERE (PaymentDate BETWEEN :sdate AND :edate)';
        }else{
            $sql .= ' WHERE (InvoiceNo =:invoice)';
        }
        $sql .= ' AND (CenterId = :cid)';
        //set query
        $this->db->query($sql);
        if($data['type'] === 'bydate') :
            $this->db->bind(':sdate',!is_null($data['sdate']) ? date('Y-m-d',strtotime($data['sdate'])) : '');
            $this->db->bind(':edate',!is_null($data['edate']) ? date('Y-m-d',strtotime($data['edate'])) : '');
        elseif ($data['type'] === 'bysupplier') :
            $this->db->bind(':sid',!is_null($data['supplier']) ? (int)$data['supplier'] : '');
            $this->db->bind(':sdate',!is_null($data['sdate']) ? date('Y-m-d',strtotime($data['sdate'])) : '');
            $this->db->bind(':edate',!is_null($data['edate']) ? date('Y-m-d',strtotime($data['edate'])) : '');
        else :
            $this->db->bind(':invoice',!is_null($data['invoiceno']) ? $data['invoiceno'] : '');
        endif;
        $this->db->bind(':cid',(int)$_SESSION['centerid'] );
        return $this->db->resultset();
    }

    //get supplier statement
    public function GetStatement($data)
    {
        $this->db->query('CALL sp_get_supplier_statement(:sid,:sdate,:edate)');
        $this->db->bind(':sid',!is_null($data['supplier']) ? (int)$data['supplier'] : '');
        $this->db->bind(':sdate',!is_null($data['sdate']) ? date('Y-m-d',strtotime($data['sdate'])) : '');
        $this->db->bind(':edate',!is_null($data['edate']) ? date('Y-m-d',strtotime($data['edate'])) : '');
        return $this->db->resultset();
    }
}