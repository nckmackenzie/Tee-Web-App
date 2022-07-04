<?php
class Center
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetCenters()
    {
        $this->db->query('CALL sp_getcenters()');
        return $this->db->resultSet();
    }
}