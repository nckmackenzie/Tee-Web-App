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
        $this->db->query('SELECT * FROM groups WHERE (Deleted = 0) AND (Active = 1) ORDER BY GroupName');
        return $this->db->resultSet();
    }
}