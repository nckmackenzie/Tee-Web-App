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
}