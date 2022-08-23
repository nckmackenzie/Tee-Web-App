<?php
class Budget
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetBudgets()
    {
        $this->db->query('SELECT * FROM vw_budgets WHERE CenterId = :cid');
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function GetOpenYears()
    {
        $this->db->query('SELECT 
                            ID,
                            UCASE(YearName) AS YearName
                          FROM
                            years
                          WHERE
                            closed = 0 AND Deleted = 0');
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

    public function CheckFieldExists($field,$id,$value)
    {
        $this->db->query('SELECT
                            COUNT(*)
                          FROM 
                            budget_header
                          WHERE
                            (ID <> :id) AND (CenterId = :cid) AND (Deleted = 0) AND ('.$field.' = :bname)');
        $this->db->bind(':id',$id); 
        $this->db->bind(':cid',(int)$_SESSION['centerid']); 
        $this->db->bind(':bname',$value); 
        if((int)$this->db->getvalue() > 0)                   {
            return false;
        }else{
            return true;
        }
    }
    
    public function Save($data)
    {
        try{
            $this->db->dbh->beginTransaction();

            $this->db->query('INSERT INTO budget_header (BudgetName,YearId,CenterId) 
                              VALUES(:bname,:yid,:cid)');
            $this->db->bind(':bname',!empty($data['budgetname']) ? strtolower($data['budgetname']) : null);
            $this->db->bind(':yid',!empty($data['year']) ? strtolower($data['year']) : null);
            $this->db->bind(':cid',(int)$_SESSION['centerid']);
            $this->db->execute();
            $tid = $this->db->dbh->lastInsertId();

            for($i = 0; $i < count($data['accountsid']); $i++){
                $this->db->query('INSERT INTO budget_details (HeaderId,AccountId,Amount) 
                                  VALUES(:hid,:aid,:amount)');
                $this->db->bind(':hid',$tid);
                $this->db->bind(':aid',$data['accountsid'][$i]);
                $this->db->bind(':amount',$data['amounts'][$i]);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }

        }catch(\Exception $e){
            if(!$this->db->dbh->inTransaction()){
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
        }
    }
}