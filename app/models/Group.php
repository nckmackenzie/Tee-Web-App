<?php

class Group
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetGroups()
    {
        $this->db->query('SELECT * FROM vw_groups');
        return $this->db->resultSet();
    }
}