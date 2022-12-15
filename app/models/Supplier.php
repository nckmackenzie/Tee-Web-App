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

    public function CheckFieldAvailability($field,$value,$id)
    {
        $sql = 'SELECT COUNT(*) FROM suppliers WHERE  (Deleted = 0) AND '.$field.' = :val AND ID <> :id';
        $this->db->query($sql);
        $this->db->bind(':val',strtolower($value));
        $this->db->bind(':id',$id);
        if(intval($this->db->getvalue()) > 0){
            return false;
        }else{
            return true;
        }
    }

    function Save($data) 
    {
        try {
            $this->db->dbh->beginTransaction();

            $this->db->query('INSERT INTO suppliers (SupplierName,Contact,`Address`,Email,PIN,ContactPerson)
                              VALUES(:sname,:contact,:add,:email,:pin,:cperson)');
            $this->db->bind(':sname',!empty($data['suppliername']) ? strtolower($data['suppliername']) : null);
            $this->db->bind(':contact',!empty($data['contact']) ? strtolower($data['contact']) : null);
            $this->db->bind(':add',!empty($data['address']) ? strtolower($data['address']) : null);
            $this->db->bind(':email',!empty($data['email']) ? $data['email'] : null);
            $this->db->bind(':pin',!empty($data['pin']) ? strtolower($data['pin']) : null);
            $this->db->bind(':cperson',!empty($data['contactperson']) ? strtolower($data['contactperson']) : null);
            $this->db->execute();
            $tid = $this->db->dbh->lastInsertId();

            if(floatval($data['openingbal']) !== 0){
                $this->db->query('INSERT INTO invoice_header (InvoiceDate,SupplierId,`Description`,InclusiveVat,PayStatus,CenterId) 
                              VALUES(:idate,:supplier,:narr,:incl,:pstatus,:cid)');
                $this->db->bind(':idate',$data['asof']);
                $this->db->bind(':supplier',$tid);
                $this->db->bind(':narr','opening bal');
                $this->db->bind(':incl',$data['openingbal']);
                $this->db->bind(':pstatus',3);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();
                $iid = $this->db->dbh->lastInsertId();

                $this->db->query('INSERT INTO invoice_payments (TransactionDate,HeaderId,SupplierId,Debit,Credit,Narration,
                                                                TransactionType,TransactionId,CenterId) 
                                  VALUES(:tdate,:hid,:supplier,:debit,:credit,:narr,:ttype,:tid,:cid)');
                $this->db->bind(':tdate',$data['asof']);
                $this->db->bind(':hid',$iid);
                $this->db->bind(':supplier',$tid);
                $this->db->bind(':debit',0);
                $this->db->bind(':credit',$data['openingbal']);
                $this->db->bind(':narr','opening bal');
                $this->db->bind(':ttype',2);
                $this->db->bind(':tid',$tid);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();

                if($data['openingbal'] > 0) {
                    savetoledger($this->db->dbh,$data['asof'],'uncategorized expenses',$data['openingbal'],0,
                                'supplier opening balance',2,2,$tid,$_SESSION['centerid']);
                    savetoledger($this->db->dbh,$data['asof'],'accounts payable',0,$data['openingbal'],
                                'supplier opening balance',4,2,$tid,$_SESSION['centerid']);
                }elseif(floatval($data['openingbal']) < 0){
                    savetoledger($this->db->dbh,$data['asof'],'uncategorized expenses',0,$data['openingbal'],
                                'supplier opening balance',2,2,$tid,$_SESSION['centerid']);
                    savetoledger($this->db->dbh,$data['asof'],'accounts payable',$data['openingbal'],0,
                                'supplier opening balance',4,2,$tid,$_SESSION['centerid']);
                }
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
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
            $this->db->query('UPDATE suppliers SET SupplierName=:sname,Contact=:contact,`Address`=:add,
                                                   Email=:email,PIN=:pin,ContactPerson=:cperson
                              WHERE (ID = :id)');
            $this->db->bind(':sname',!empty($data['suppliername']) ? strtolower($data['suppliername']) : null);
            $this->db->bind(':contact',!empty($data['contact']) ? strtolower($data['contact']) : null);
            $this->db->bind(':add',!empty($data['address']) ? strtolower($data['address']) : null);
            $this->db->bind(':email',!empty($data['email']) ? $data['email'] : null);
            $this->db->bind(':pin',!empty($data['pin']) ? strtolower($data['pin']) : null);
            $this->db->bind(':cperson',!empty($data['contactperson']) ? strtolower($data['contactperson']) : null);
            $this->db->bind(':id',$data['id']);
            if(!$this->db->execute()){
                return false;
            }else{
                return true;
            }
        }
    }
    public function GetSupplier($id)
    {
        $this->db->query('SELECT * FROM suppliers WHERE (ID = :id)');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }
    public function ValidateDelete($id)
    {
        $this->db->query('SELECT COUNT(*) FROM invoice_payments WHERE (Deleted = 0) AND (SupplierId = :id)');
        $this->db->bind(':id',intval($id));
        if(intval($this->db->getvalue()) > 0){
            return false;
        }else{
            return true;
        }
    }
    public function Delete($id)
    {
        $this->db->query('UPDATE suppliers SET Deleted = 1 WHERE ID = :id');
        $this->db->bind(':id',intval($id));
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}