<?php 
class Pettycashreceipt
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetReceipts()
    {
        return loadresultset($this->db->dbh,'SELECT * FROM vw_petty_cash',[]);
    }
}