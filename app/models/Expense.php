<?php
class Expense 
{
    private $db;
    public function __construct()
    {
       $this->db = new Database;       
    }

    public function GetExpenses()
    {
        $this->db->query('SELECT * FROM vw_expenses WHERE CenterId = :cid');
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function GetExpenseAccounts()
    {
        $this->db->query('SELECT
                            ID,
                            UCASE(AccountName) AS AccountName
                          FROM 
                            accounttypes
                          WHERE 
                            AccountTypeId = 2
                          ORDER BY AccountName');
        return $this->db->resultset();
    }

    public function CheckFieldAvailability($field,$value,$id)
    {
        $sql = 'SELECT COUNT(*) FROM expenses WHERE  (Deleted = 0) AND '.$field.' = :val AND ID <> :id';
        $this->db->query($sql);
        $this->db->bind(':val',strtolower($value));
        $this->db->bind(':id',$id);
        if(intval($this->db->getvalue()) > 0){
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
                $sql = 'INSERT INTO expenses (ExpenseDate,VoucherNo,AccountId,Amount,
                                              PaymentMethodId,PaymentReference,Narration,CenterId) 
                        VALUES(:edate,:voucher,:account,:amount,:paymethod,:reference,:narr,:cid)';
            }else{
                $sql = 'UPDATE expenses SET ExpenseDate=:edate,VoucherNo=:voucher,Amount=:amount,AccountId 
                                            =:account,PaymentMethodId=:paymethod,PaymentReference=:reference
                                            ,Narration=:narr 
                        WHERE (ID = :id)';
            }
            $this->db->query($sql);
            $this->db->bind(':edate',!empty($data['edate']) ? $data['edate'] : null);
            $this->db->bind(':voucher',!empty($data['voucherno']) ? $data['voucherno'] : null);
            $this->db->bind(':account',!empty($data['account']) ? $data['account'] : null);
            $this->db->bind(':amount',!empty($data['amount']) ? $data['amount'] : null);
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
                $this->db->query('DELETE FROM ledger WHERE TransactionType = 6 AND TransactionId = :id');
                $this->db->bind(':id',$data['id']);
                $this->db->execute(); 
            }

            savetoledger($this->db->dbh,$data['edate'],$this->GetAccountDetails($data['account'])[0],$data['amount'],0,
                         strtolower($data['narration']),$this->GetAccountDetails($data['account'])[1],6,$tid,$_SESSION['centerid']);
            if((int)$data['paymethod'] === 1){
                savetoledger($this->db->dbh,$data['edate'],'cash at hand',0,$data['amount'],
                         strtolower($data['narration']),3,6,$tid,$_SESSION['centerid']);
            }else{
                savetoledger($this->db->dbh,$data['edate'],'cash at bank',0,$data['amount'],
                         strtolower($data['narration']),3,6,$tid,$_SESSION['centerid']);
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

    public function GetExpense($id)
    {
        $this->db->query('SELECT * FROM expenses WHERE ID = :id');
        $this->db->bind(':id',(int)$id);
        return $this->db->single();
    }

    public function Delete($id)
    {
        try {
            $this->db->dbh->beginTransaction();

            $sql = 'UPDATE expenses SET Deleted = 1
                    WHERE (ID = :id)';
            $this->db->query($sql);            
            $this->db->bind(':id',$id);
            $this->db->execute();
              
            $this->db->query('UPDATE ledger SET Deleted = 1 WHERE TransactionType = 6 AND TransactionId = :id');
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
}