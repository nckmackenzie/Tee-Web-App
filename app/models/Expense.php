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
}