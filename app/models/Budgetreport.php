<?php
class Budgetreport
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetBudgetVsExpense($yearid,$type)
    {
        if($type === 'summary'){
            return loadresultset($this->db->dbh,'CALL sp_budgetvsexpensesummary(?)',[$yearid]);
        }elseif ($type === 'detailed') {
            return loadresultset($this->db->dbh,'CALL sp_budgetvsexpensedetailed(?)',[$yearid]);
        }
    }
    
}