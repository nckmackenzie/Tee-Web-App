<?php

class Semister
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetSemisters()
    {
        return loadresultset($this->db->dbh,'SELECT * FROM vw_semisters',[]);
    }
}