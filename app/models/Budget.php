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
}