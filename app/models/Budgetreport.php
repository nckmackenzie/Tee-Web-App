<?php
class Budgetreport
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetBudgetVsExpenseSummary($yearid)
    {
        return loadresultset($this->db->dbh,'CALL sp_budgetvsexpensesummary(?)',[$yearid]);
    }
    
}