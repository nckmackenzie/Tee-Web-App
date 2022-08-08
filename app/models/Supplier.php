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

            if($data['openingbal'] > 0){
                $this->db->query('INSERT INTO invoice_payments (TransactionDate,SupplierId,Debit,Credit,
                                                                TransactionType,TransactionId,CenterId) 
                                  VALUES(:tdate,:supplier,:debit,:credit,:ttype,:tid,:cid)');
                $this->db->bind(':tdate',$data['asof']);
                $this->db->bind(':supplier',$tid);
                $this->db->bind(':debit',0);
                $this->db->bind(':credit',$data['openingbal']);
                $this->db->bind(':ttype',2);
                $this->db->bind(':tid',$tid);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();

                savetoledger($this->db->dbh,$data['asof'],'stocks',$data['openingbal'],0,
                             'supplier opening balance',3,2,$tid,$_SESSION['centerid']);
                savetoledger($this->db->dbh,$data['asof'],'accounts payable',0,$data['openingbal'],
                             'supplier opening balance',4,2,$tid,$_SESSION['centerid']);
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
}