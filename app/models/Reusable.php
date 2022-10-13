<?php

class Reusable 
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetCenters()
    {
        $sql = 'SELECT ID,UCASE(CenterName) AS CenterName 
                FROM centers WHERE Active =1 AND Deleted = 0 ORDER BY CenterName';
        return loadresultset($this->db->dbh,$sql,[]);
    }

    public function GetCenterDetails($id)
    {
       $this->db->query('SELECT * FROM centers WHERE ID = :id');
       $this->db->bind(':id',$id);
       return $this->db->single();
    }
}