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

    function GetGlDetails($bookid){
        $values = [];
        $this->db->query('SELECT GlAccountId FROM books WHERE ID = :id');
        $this->db->bind(':id',(int)$bookid);
        $glaccount = (int)$this->db->getvalue();

        $this->db->query('SELECT AccountName,AccountTypeId FROM accounttypes WHERE ID = :id');
        $this->db->bind(':id',$glaccount);
        $details = $this->db->single();
        array_push($values,$details->AccountName);
        array_push($values,$details->AccountTypeId);
        return $values;
    }

    public function GetVatRate($vat)
    {
        $this->db->query('SELECT Rate FROM vat WHERE ID = :id');
        $this->db->bind(':id',$vat);
        return $this->db->getvalue();
    }

    function Save($data)
    {
        try {
            $this->db->dbh->beginTransaction();
            $amountinc = calculatevat($data['vattype'],$data['total'],$data['vatrate'])[2];
            
            $this->db->query('INSERT INTO invoice_header (InvoiceDate,DueDate,SupplierId,InvoiceNo,`Description`,VatType,VatId,
                                                          ExclusiveVat,Vat,InclusiveVat,PayStatus,CenterId) 
                              VALUES(:idate,:ddate,:supplier,:invoice,:narr,:vtype,:vid,:excl,:vat,:incl,:pstatus,:cid)');
            $this->db->bind(':idate',!empty($data['invoicedate']) ? $data['invoicedate'] : null);
            $this->db->bind(':ddate',!empty($data['duedate']) ? $data['duedate'] : null);
            $this->db->bind(':supplier',!empty($data['supplier']) ? $data['supplier'] : null);
            $this->db->bind(':invoice',!empty($data['invoiceno']) ? $data['invoiceno'] : null);
            $this->db->bind(':narr',!empty($data['description']) ? strtolower($data['description']) : null);
            $this->db->bind(':vtype',!empty($data['vattype']) ? $data['vattype'] : null);
            $this->db->bind(':vid',!empty($data['vat']) ? $data['vat'] : null);
            $this->db->bind(':excl',calculatevat($data['vattype'],$data['total'],$data['vatrate'])[0]);
            $this->db->bind(':vat',calculatevat($data['vattype'],$data['total'],$data['vatrate'])[1]);
            $this->db->bind(':incl',$amountinc);
            $this->db->bind(':pstatus',3);
            $this->db->bind(':cid',$_SESSION['centerid']);
            $this->db->execute();
            $tid = $this->db->dbh->lastInsertId();

            for($i = 0; $i < count($data['booksid']); $i++){
               $this->db->query('INSERT INTO invoice_details (HeaderId,ProductId,Qty,Rate)
                                 VALUES(:hid,:pid,:qty,:rate)');
               $this->db->bind(':hid',$tid);
               $this->db->bind(':pid',$data['booksid'][$i]);
               $this->db->bind(':qty',$data['qtys'][$i]);
               $this->db->bind(':rate',$data['rates'][$i]);
               $this->db->execute();

               $accountname = $this->GetGlDetails($data['booksid'][$i])[0];
               $accountid = $this->GetGlDetails($data['booksid'][$i])[1];
               $amountwithvat = calculatevat($data['vattype'],$data['gross'][$i],$data['vatrate'])[2];
               savetoledger($this->db->dbh,$data['invoicedate'],$accountname,$amountwithvat,0,
                            strtolower($data['description']),$accountid,3,$tid,$_SESSION['centerid']);
            }

            $this->db->query('INSERT INTO invoice_payments (TransactionDate,HeaderId,SupplierId,Credit,
                                                            Narration,TransactionType,TransactionId,CenterId) 
                              VALUES(:tdate,:hid,:supplier,:credit,:narr,:ttype,:tid,:cid)');
            $this->db->bind(':tdate',!empty($data['invoicedate']) ? $data['invoicedate'] : null);
            $this->db->bind(':hid',$tid);
            $this->db->bind(':supplier',!empty($data['supplier']) ? $data['supplier'] : null);
            $this->db->bind(':credit',$amountinc);
            $this->db->bind(':narr',!empty($data['description']) ? strtolower($data['description']) : null);
            $this->db->bind(':ttype',3);
            $this->db->bind(':tid',$tid);
            $this->db->bind(':cid',$_SESSION['centerid']);
            $this->db->execute();

            savetoledger($this->db->dbh,$data['invoicedate'],'accounts payable',0,$amountinc,
                            strtolower($data['description']),4,3,$tid,$_SESSION['centerid']);

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            throw $e;
            return false;
        }
    }

    function Update($data)
    {
        try {
            $this->db->dbh->beginTransaction();
            $amountinc = calculatevat($data['vattype'],$data['total'],$data['vatrate'])[2];
            
            $this->db->query('UPDATE invoice_header SET InvoiceDate=:idate,DueDate=:ddate,SupplierId=:supplier,
                                                        InvoiceNo=:invoice,`Description`=:narr,VatType=:vtype,
                                                        VatId=:vid,ExclusiveVat=:excl,Vat=:vat,
                                                        InclusiveVat=:incl 
                              WHERE (ID = :id)');
            $this->db->bind(':idate',!empty($data['invoicedate']) ? $data['invoicedate'] : null);
            $this->db->bind(':ddate',!empty($data['duedate']) ? $data['duedate'] : null);
            $this->db->bind(':supplier',!empty($data['supplier']) ? $data['supplier'] : null);
            $this->db->bind(':invoice',!empty($data['invoiceno']) ? $data['invoiceno'] : null);
            $this->db->bind(':narr',!empty($data['description']) ? strtolower($data['description']) : null);
            $this->db->bind(':vtype',!empty($data['vattype']) ? $data['vattype'] : null);
            $this->db->bind(':vid',!empty($data['vat']) ? $data['vat'] : null);
            $this->db->bind(':excl',calculatevat($data['vattype'],$data['total'],$data['vatrate'])[0]);
            $this->db->bind(':vat',calculatevat($data['vattype'],$data['total'],$data['vatrate'])[1]);
            $this->db->bind(':incl',$amountinc);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();
            
            $this->db->query('DELETE FROM invoice_details WHERE HeaderId = :hid');
            $this->db->bind(':hid',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM ledger WHERE TransactionType = 3 AND TransactionId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            for($i = 0; $i < count($data['booksid']); $i++){
               $this->db->query('INSERT INTO invoice_details (HeaderId,ProductId,Qty,Rate)
                                 VALUES(:hid,:pid,:qty,:rate)');
               $this->db->bind(':hid',$data['id']);
               $this->db->bind(':pid',$data['booksid'][$i]);
               $this->db->bind(':qty',$data['qtys'][$i]);
               $this->db->bind(':rate',$data['rates'][$i]);
               $this->db->execute();

               $accountname = $this->GetGlDetails($data['booksid'][$i])[0];
               $accountid = $this->GetGlDetails($data['booksid'][$i])[1];
               $amountwithvat = calculatevat($data['vattype'],$data['gross'][$i],$data['vatrate'])[2];
               savetoledger($this->db->dbh,$data['invoicedate'],$accountname,$amountwithvat,0,
                            strtolower($data['description']),$accountid,3,$data['id'],$_SESSION['centerid']);
            }

            $this->db->query('UPDATE invoice_payments SET TransactionDate=:tdate,SupplierId=:supplier,Credit=:credit,
                                                          Narration=:narr 
                              WHERE (HeaderId =:id)');
            $this->db->bind(':tdate',!empty($data['invoicedate']) ? $data['invoicedate'] : null);
            $this->db->bind(':supplier',!empty($data['supplier']) ? $data['supplier'] : null);
            $this->db->bind(':credit',$amountinc);
            $this->db->bind(':narr',!empty($data['description']) ? strtolower($data['description']) : null);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            savetoledger($this->db->dbh,$data['invoicedate'],'accounts payable',0,$amountinc,
                            strtolower($data['description']),4,3,$data['id'],$_SESSION['centerid']);

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            throw $e;
            return false;
        }
    }

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
           return $this->Save($data);
        }else{
            return $this->Update($data);
        }
    }

    public function GetInvoiceHeader($id)
    {
        $this->db->query('SELECT * FROM invoice_header WHERE ID = :id');
        $this->db->bind(':id',(int)$id);
        return $this->db->single();
    }

    public function GetInvoiceDetails($id)
    {
        $this->db->query('SELECT 
                            ProductId,
                            BookTitle,
                            Qty,
                            Rate,
                            Gross
                          FROM 
                            vw_invoiceheader
                          WHERE 
                            HeaderId = :id');
        $this->db->bind(':id',(int)$id);
        return $this->db->resultset();                  
    }

    public function GetInvoiceDetail($id)
    {
        $this->db->query('SELECT * FROM vw_invoices WHERE ID = :id');
        $this->db->bind(':id',(int)$id);
        return $this->db->single();
    }

    public function CheckPaymethodAvailability($ref)
    {
        $this->db->query('SELECT 
                            COUNT(*) 
                          FROM 
                            invoice_payments
                          WHERE (PaymentReference=:ref) AND (Deleted=0)');
        $this->db->bind(':ref',strtolower($ref));
        if((int)$this->db->getvalue() > 0){
            return false;
        }else{
            return true;
        }
    }

    public function PayInvoice($data)
    {
        try {
            $this->db->dbh->beginTransaction();
                
            $this->db->query('UPDATE invoice_header SET PayStatus = :pstatus
                              WHERE (ID = :id)');
            $this->db->bind(':pstatus', floatval($data['currentbalance']) == 0 ? 1 : 2);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('INSERT INTO invoice_payments (TransactionDate,HeaderId,SupplierId,Debit,
                                                            Narration,PaymentMethod,PaymentReference,CenterId) 
                              VALUES(:tdate,:hid,:supplier,:debit,:narr,:pmethod,:ref,:cid)');
            $this->db->bind(':tdate',!empty($data['paydate']) ? $data['paydate'] : null);
            $this->db->bind(':hid',$data['id']);
            $this->db->bind(':supplier',!empty($data['supplierid']) ? $data['supplierid'] : null);
            $this->db->bind(':debit',$data['currentamount']);
            $this->db->bind(':narr',!empty($data['narration']) ? strtolower($data['narration']) : null);
            $this->db->bind(':pmethod',!empty($data['paymethod']) ? strtolower($data['paymethod']) : null);
            $this->db->bind(':ref',!empty($data['reference']) ? strtolower($data['reference']) : null);
            $this->db->bind(':cid',$_SESSION['centerid']);
            $this->db->execute();
            $tid = $this->db->dbh->lastInsertId();
            
            savetoledger($this->db->dbh,$data['paydate'],'accounts payable',$data['currentamount'],0,
                         strtolower($data['narration']),4,4,$tid,$_SESSION['centerid']);
            if((int)$data['paymethod'] === 1){
                savetoledger($this->db->dbh,$data['paydate'],'cash at hand',0,$data['currentamount'],
                             strtolower($data['narration']),3,4,$tid,$_SESSION['centerid']);
            }else{
                savetoledger($this->db->dbh,$data['paydate'],'cash at bank',0,$data['currentamount'],
                             strtolower($data['narration']),3,4,$tid,$_SESSION['centerid']);
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            throw $e;
            return false;
        }
    }
}