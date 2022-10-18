<?php
class Fee
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetFees()
    {
        $this->db->query('SELECT * FROM vw_feepayments WHERE CenterId = :cid');
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function GetStudents()
    {
        $this->db->query('SELECT 
                            ID,
                            UCASE(StudentName) As StudentName 
                          FROM 
                            students 
                          WHERE 
                            Deleted = 0
                          ORDER BY StudentName');
        return $this->db->resultset();
    }

    public function GetAccounts()
    {
        $this->db->query('SELECT 
                            ID,
                            UCASE(AccountName) As AccountName 
                          FROM 
                            accounttypes 
                          WHERE 
                            AccountTypeId = 1
                          ORDER BY AccountName');
        return $this->db->resultset();
    }

    public function GetReceiptNo()
    {
        return getuniqueid($this->db->dbh,'ReceiptNo','fees_payment',(int)$_SESSION['centerid']);
    }

    public function CheckRefExists($ref,$id)
    {
        $this->db->query('SELECT 
                            COUNT(*)
                          FROM 
                            fees_payment 
                          WHERE 
                            (Reference = :ref) AND (ID <> :id) AND (Deleted = 0)');
        $this->db->bind(':ref',strtolower($ref));
        $this->db->bind(':id',$id);
        if((int)$this->db->getvalue() > 0){
            return false;
        }else{
            return true;
        }
    }

    function GetAccountDetails($id){
        $this->db->query('SELECT AccountName,AccountTypeId FROM accounttypes WHERE ID = :id');
        $this->db->bind(':id',(int)$id);
        $account = $this->db->single();
        return [$account->AccountName,$account->AccountTypeId];
    }

    public function CreateUpdate($data)
    {
        try {
            $this->db->dbh->beginTransaction();

            if(!$data['isedit']){
                $sql = 'INSERT INTO fees_payment (PaymentDate,ReceiptNo,StudentId,AmountPaid,GlAccountId,
                                                  PaymentMethodId,Reference,Narration,CenterId) 
                        VALUES(:pdate,:receipt,:student,:amount,:account,:paymethod,:reference,:narr,:cid)';
            }else{
                $sql = 'UPDATE fees_payment SET PaymentDate=:pdate,ReceiptNo=:receipt,StudentId=:student,AmountPaid
                                                =:amount,GlAccountId=:account,PaymentMethodId=:paymethod,Reference=:reference
                                                ,Narration=:narr 
                        WHERE (ID = :id)';
            }
            $this->db->query($sql);
            $this->db->bind(':pdate',!empty($data['pdate']) ? $data['pdate'] : null);
            $this->db->bind(':receipt',!empty($data['receiptno']) ? $data['receiptno'] : null);
            $this->db->bind(':student',!empty($data['student']) ? $data['student'] : null);
            $this->db->bind(':amount',!empty($data['amount']) ? $data['amount'] : null);
            $this->db->bind(':account',!empty($data['account']) ? $data['account'] : null);
            $this->db->bind(':paymethod',!empty($data['paymethod']) ? $data['paymethod'] : null);
            $this->db->bind(':reference',!empty($data['reference']) ? strtolower($data['reference']) : null);
            $this->db->bind(':narr',!empty($data['narration']) ? strtolower($data['narration']) : null);
            if(!$data['isedit']){
                $this->db->bind(':cid',$_SESSION['centerid']);
            }else{
                $this->db->bind(':id',$data['id']);
            }
            $this->db->execute();
            $tid = !$data['isedit'] ? $this->db->dbh->lastInsertId() : $data['id'];

            if($data['isedit']){
                $this->db->query('DELETE FROM ledger WHERE TransactionType = 5 AND TransactionId = :id');
                $this->db->bind(':id',$data['id']);
                $this->db->execute(); 
            }

            savetoledger($this->db->dbh,$data['pdate'],$this->GetAccountDetails($data['account'])[0],0,$data['amount'],
                         strtolower($data['narration']),$this->GetAccountDetails($data['account'])[1],5,$tid,$_SESSION['centerid']);
            if((int)$data['paymethod'] === 1){
                savetoledger($this->db->dbh,$data['pdate'],'cash at hand',$data['amount'],0,
                         strtolower($data['narration']),3,5,$tid,$_SESSION['centerid']);
            }else{
                savetoledger($this->db->dbh,$data['pdate'],'cash at bank',$data['amount'],0,
                         strtolower($data['narration']),3,5,$tid,$_SESSION['centerid']);
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
            
        } catch (\Exception $e) {
            if(!$this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            throw $e;
            return false;
        }
    }

    public function GetPayment($id)
    {
        $this->db->query('SELECT * FROM fees_payment WHERE ID = :id');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    public function Delete($id)
    {
        try {
            $this->db->dbh->beginTransaction();

            $sql = 'UPDATE fees_payment SET Deleted = 1
                    WHERE (ID = :id)';
            $this->db->query($sql);
            $this->db->bind(':id',$id);
            $this->db->execute();
            
            $this->db->query('UPDATE ledger SET Deleted = 1 WHERE TransactionType = 5 AND TransactionId = :id');
            $this->db->bind(':id',$id);
            $this->db->execute(); 

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
            
        } catch (\Exception $e) {
            if(!$this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            throw $e;
            return false;
        }
    }

    public function GetFeeStructures()
    {
        $sql = 'SELECT 
                    f.ID,
                    ucase(s.SemisterName) As SemisterName,
                    FORMAT(f.TotalAmount,2) As TotalAmount
                FROM fee_structure f
                     join semisters s on f.SemisterId = s.ID
                WHERE 
                  (f.Deleted = 0)
                ORDER BY f.ID DESC';
        return loadresultset($this->db->dbh,$sql,[]);
    }

    public function GetSemisters()
    {
        return loadresultset($this->db->dbh,'SELECT ID,UCASE(SemisterName) AS SemisterName FROM semisters WHERE Deleted = 0 ORDER BY SemisterName',[]);
    }
}