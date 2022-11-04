<?php
class Expensereport 
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetExpenseReport($data)
    {
        $values = array_values($data);
        return loadresultset($this->db->dbh,'CALL sp_expensereport(?,?,?,?)',[...$values]);
    }
}