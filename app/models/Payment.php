<?php

class Payment
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }
    
    public function GetPayments()
    {
        return loadresultset($this->db->dbh,'SELECT * FROM vw_invoice_payments',[]);
    }
    
    public function GetPayId()
    {
        return getuniqueid($this->db->dbh,'PayId','invoice_payments',$_SESSION['centerid'],false);
    }

    public function GetInvoicesWithBalances()
    {
        return loadresultset($this->db->dbh,'SELECT * FROM vw_invoice_with_balances',[]);
    }

    public function Create($data)
    {
        try {
            $this->db->dbh->beginTransaction();
            $payid = $this->GetPayId();

        for ($i=0; $i < count($data['details']); $i++) { 
            $narr = 'invoice payment reference' .$data['details'][$i]->cheque;
            $this->db->query('INSERT INTO invoice_payments(TransactionDate,HeaderId,SupplierId,PayId,Debit,Narration,PaymentMethod,
                                                           PaymentReference,TransactionType,CenterId) 
                              VALUES(:tdate,:hid,:supplier,:payid,:debit,:narr,:paymethod,:ref,:ttype,:cid)');
            $this->db->bind(':tdate',$data['paydate']);
            $this->db->bind(':hid',$data['details'][$i]->invoiceid);
            $this->db->bind(':supplier',$data['details'][$i]->sid);
            $this->db->bind(':payid',$payid);
            $this->db->bind(':debit',floatval($data['details'][$i]->payment));
            $this->db->bind(':narr',$narr);
            $this->db->bind(':paymethod',$data['paymethod']);
            $this->db->bind(':ref',strtolower($data['details'][$i]->cheque));
            $this->db->bind(':ttype',4);
            $this->db->bind(':cid',$_SESSION['centerid']);
            $this->db->execute();
            $tid = $this->db->dbh->lastInsertId();

            $status = 2;
            if(floatval($data['details'][$i]->payment) == floatval($data['details'][$i]->balance)){
                $status = 1;
            }
            //update header table
            $this->db->query('UPDATE invoice_header SET PayStatus = :pstatus WHERE (ID = :id)');
            $this->db->bind(':pstatus',$status);
            $this->db->bind(':id',$data['details'][$i]->invoiceid);
            $this->db->execute();

            savetoledger($this->db->dbh,$data['paydate'],'accounts payable',$data['details'][$i]->payment,0
                         ,$narr,4,4,$tid,$_SESSION['centerid']);
            if((int)$data['paymethod'] === 1){
                savetoledger($this->db->dbh,$data['paydate'],'cash at hand',0,$data['details'][$i]->payment,
                             $narr,3,4,$tid,$_SESSION['centerid']);
            }else{
                savetoledger($this->db->dbh,$data['paydate'],'cash at bank',0,$data['details'][$i]->payment,
                             $narr,3,4,$tid,$_SESSION['centerid']);
                savebankposting($this->db->dbh,$data['paydate'],(int)$data['paymethod'] === 2 ? 1 : 0,null,0,
                                floatval($data['details'][$i]->payment),$data['details'][$i]->cheque,$narr,4,$tid,$_SESSION['centerid']);
            }
        }

        if(!$this->db->dbh->commit()){
            return false;
        }else{
            return true;
        }
            
        } catch (PDOException $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            error_log($e->getMessage(),0);
            return false;
        }
    }

    public function GetPaymentDetails($pid,$sid)
    {
        $count = getdbvalue($this->db->dbh,'SELECT COUNT(*) from invoice_payments 
                                            WHERE (SupplierId=?) AND (PayId = ?) AND (Deleted = 0)',[$sid,$pid]);
        if((int)$count === 0){
            return false;
            exit;
        }
        $this->db->query('SELECT * FROM invoice_payments WHERE PayId = :id AND (Deleted = 0) AND (TransactionType = 4)');
        $this->db->bind(':id',(int)$pid);
        return $this->db->single();
    }

    public function GetSupplierName($id)
    {
        return getdbvalue($this->db->dbh,"SELECT IFNULL(SupplierName,'N/A') As supplier FROM suppliers WHERE (ID = ?)",[$id]);
    }

    public function GetPaymentsDetail($pid,$sid,$date)
    {
        $sql = 'SELECT 
                    h.InvoiceNo,
                    h.InvoiceDate,
                    h.InclusiveVat As InvoiceValue,
                    fn_get_invoice_balance_by_date(h.ID,?) As Balance,
                    p.Debit As Payment
                FROM `invoice_payments` p join invoice_header h on p.HeaderId = h.ID
                WHERE (p.PayId = ?) AND (p.SupplierId = ?)';
        return loadresultset($this->db->dbh,$sql,[$date,$pid,$sid]);
    }
}