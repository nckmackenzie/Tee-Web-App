<?php

class Bank
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    //get all banks
    public function GetBanks()
    {
        $sql = 'SELECT ID,UCASE(BankName) AS BankName,AccountNo FROM banks WHERE Deleted = 0 ORDER BY BankName';
        return loadresultset($this->db->dbh,$sql,[]);
    }
}