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
}