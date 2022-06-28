<?php

class Auths 
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
    public function LoadCenters()
    {
        $this->db->query("SELECT ID,
                                 UCASE(CenterName) As CenterName
                          FROM   centers WHERE Deleted = 0 ORDER BY CenterName");
        return $this->db->resultset();
    }
}