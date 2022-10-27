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
        $this->db->query('SELECT * FROM vw_feepayments');
        // $this->db->bind(':cid',(int)$_SESSION['centerid']);
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

    public function GetGroups()
    {
        $this->db->query('SELECT 
                            ID,
                            UCASE(GroupName) As GroupName 
                          FROM 
                            groups 
                          WHERE 
                            Deleted = 0
                          ORDER BY GroupName');
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
        return getuniqueid($this->db->dbh,'ReceiptNo','fees_payment',$_SESSION['centerid'],false);
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

    function CheckStudentSemister($student,$semister){
        return getdbvalue($this->db->dbh,
                         'SELECT COUNT(*) FROM payment_summary WHERE (StudentId=?) AND (SemisterId=?)',[$student,$semister]);
    }

    //check if other payments for same semister exists
    function CheckExistingSemisterPayment($student,$semister,$id)
    {
        $sql = 'SELECT COUNT(*) AS PaymentCount FROM fees_payment WHERE (StudentId = ?) AND (SemisterId = ?) AND (ID < ?)';
        $count = getdbvalue($this->db->dbh,$sql,[$student,$semister,$id]);
        if((int)$count > 0){
            return true;
        }else{
            return false;
        }
    }

    function Save($data){
        try {
            $this->db->dbh->beginTransaction();

            $sql = 'INSERT INTO fees_payment (PaymentDate,ReceiptNo,StudentId,SemisterId,AmountPaid,GlAccountId,
                                              PaymentMethodId,Reference,Narration) 
                    VALUES(:pdate,:receipt,:student,:semister,:amount,:account,:paymethod,:reference,:narr)';
  
            $this->db->query($sql);
            $this->db->bind(':pdate',$data['pdate']);
            $this->db->bind(':receipt',$data['receiptno']);
            $this->db->bind(':student',$data['student']);
            $this->db->bind(':semister',$data['semister']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':account',$data['account']);
            $this->db->bind(':paymethod',$data['paymethod']);
            $this->db->bind(':reference',$data['reference']);
            $this->db->bind(':narr',$data['narration']);
            $this->db->execute();
            $tid = $this->db->dbh->lastInsertId();

            //if first record for semister
            if((int)$this->CheckStudentSemister($data['student'],$data['semister']) === 0){
                $this->db->query('INSERT INTO payment_summary (StudentId,SemisterId,TotalDue) 
                                  VALUES(:student,:semister,:total)');
                $this->db->bind(':student',$data['student']);
                $this->db->bind(':semister',$data['semister']);
                $this->db->bind(':total',$data['balancebf'] + $data['semisterfees']);
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
                savebankposting($this->db->dbh,$data['pdate'],(int)$data['paymethod'] === 4 ? 1 : 0,null,$data['amount'],
                                0,$data['reference'],strtolower($data['narration']),5,$tid,$_SESSION['centerid']);
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
            
        }catch (PDOException $e) {
            if(!$this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            error_log($e->getMessage(),0);
            return false;
        }catch (Exception $e) {
            error_log($e->getMessage(),0);
            return false;
        }
    }

    function Update($data)
    {
        try {
            $this->db->dbh->beginTransaction();

            $sql = 'UPDATE fees_payment SET PaymentDate=:pdate,AmountPaid=:amount,GlAccountId=:account,
                                            PaymentMethodId=:paymethod,Reference=:reference,Narration=:narr 
                    WHERE (ID = :id)';
  
            $this->db->query($sql);
            $this->db->bind(':pdate',$data['pdate']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':account',$data['account']);
            $this->db->bind(':paymethod',$data['paymethod']);
            $this->db->bind(':reference',$data['reference']);
            $this->db->bind(':narr',$data['narration']);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();
           

            //if first record for semister
            if(!$this->CheckExistingSemisterPayment($data['student'],$data['semister'],$data['id'])){
                $this->db->query('UPDATE payment_summary SET TotalDue = :total 
                                  WHERE (StudentId = :student) AND (SemisterId = :semister)');
                $this->db->bind(':total',$data['balancebf'] + $data['semisterfees']);
                $this->db->bind(':student',$data['student']);
                $this->db->bind(':semister',$data['semister']);
                $this->db->execute();
            }

            $this->db->query('DELETE FROM bankpostings WHERE (TransactionType = 5) AND (TransactionId = :id)');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM ledger WHERE (TransactionType = 5) AND (TransactionId = :id)');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();
            
            savetoledger($this->db->dbh,$data['pdate'],$this->GetAccountDetails($data['account'])[0],0,$data['amount'],
                         strtolower($data['narration']),$this->GetAccountDetails($data['account'])[1],5,$data['id'],$_SESSION['centerid']);
            if((int)$data['paymethod'] === 1){
                savetoledger($this->db->dbh,$data['pdate'],'cash at hand',$data['amount'],0,
                         strtolower($data['narration']),3,5,$data['id'],$_SESSION['centerid']);
            }else{
                savetoledger($this->db->dbh,$data['pdate'],'cash at bank',$data['amount'],0,
                         strtolower($data['narration']),3,5,$data['id'],$_SESSION['centerid']);
                savebankposting($this->db->dbh,$data['pdate'],(int)$data['paymethod'] === 4 ? 1 : 0,null,$data['amount'],
                         0,$data['reference'],strtolower($data['narration']),5,$data['id'],$_SESSION['centerid']);
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
            
        }catch (PDOException $e) {
            if(!$this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            error_log($e->getMessage(),0);
            return false;
        }catch (Exception $e) {
            error_log($e->getMessage(),0);
            return false;
        }
    }

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            return $this->Save($data);
        }else {
            return $this->Update($data);
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

            $this->db->query('UPDATE bankpostings SET Deleted = 1 WHERE (TransactionType = 5) AND (TransactionId = :id)');
            $this->db->bind(':id',$id);
            $this->db->execute();

            $this->db->query('SELECT StudentId,SemisterId FROM fees_payment WHERE ID = :id');
            $this->db->bind(':id',$id);
            $paydetails = $this->db->single();
            if(!$this->CheckExistingSemisterPayment($paydetails->StudentId,$paydetails->SemisterId,$id)){
                $this->db->query('DELETE FROM payment_summary WHERE StudentId = :student AND SemisterId = :semister');
                $this->db->bind(':student',$paydetails->StudentId);
                $this->db->bind(':semister',$paydetails->SemisterId);
                $this->db->execute();
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
        return loadresultset($this->db->dbh,'SELECT ID,UCASE(SemisterName) AS SemisterName FROM semisters WHERE Deleted = 0 AND Closed = 0 ORDER BY SemisterName',[]);
    }

    public function CheckSemisterDefined($semister,$id)
    {
        return getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM fee_structure WHERE (SemisterId = ?) AND (Deleted = 0) AND (ID <> ?)',[$semister, $id]);
    }

    public function CreateUpdateStructure($data)
    {
        try {
            if($data['isedit']){
                $this->db->query('UPDATE fee_structure SET SemisterId=:semid,TotalAmount=:amount 
                                  WHERE (ID = :id)');
            }else{
                $this->db->query('INSERT INTO fee_structure (SemisterId,TotalAmount) VALUES(:semid,:amount)');
            }
            $this->db->bind(':semid',(int)$data['semister']);
            $this->db->bind(':amount',$data['amount']);
            if($data['isedit']){
                $this->db->bind(':id',$data['id']);
            }

            if(!$this->db->execute()){
                return false;
            }else{
                return true;
            }

        } catch (PDOException $e) {
            error_log($e->getMessage(),0);
            return false;
        } catch (Exception $e) {
            error_log($e->getMessage(),0);
            return false;
        }
    }

    public function GetFeeStructure($id)
    {
        $this->db->query('SELECT * FROM fee_structure WHERE (ID = :id)');
        $this->db->bind(':id',$id);
        return  $this->db->single();
    }

    public function DeleteStructure($id)
    {
        $this->db->query('UPDATE fee_structure SET Deleted  = 1 WHERE ID = :id');
        $this->db->bind(':id',$id);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }

    public function GetFeePaymentDetails($student,$semister)
    {
        $balancebf = getdbvalue($this->db->dbh,'SELECT IFNULL(fn_getbalancebf(?,?),0) AS bf',[$student,$semister]);
        $semfees = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(TotalAmount),0) FROM fee_structure WHERE (SemisterId = ?) AND (Deleted = 0)',[$semister]);
        $sempaid = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(AmountPaid),0) AS Paid FROM fees_payment 
                                              WHERE (StudentId = ?) AND (SemisterId = ?) AND (Deleted = 0)',[$student,$semister]); 
        return [$balancebf,$semfees,$sempaid];
    }
}