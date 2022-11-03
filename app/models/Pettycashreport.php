<?php
class Pettycashreport
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetPettyCashReport($data)
    {
        $values = array_values($data);
        return loadresultset($this->db->dbh,'CALL sp_pettycashreport(?,?,?)',[...$values,$_SESSION['centerid']]);
    }
}