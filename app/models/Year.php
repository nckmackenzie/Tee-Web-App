<?php

class Year
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    //get created financial years
    public function GetYears()
    {
        $this->db->query('CALL sp_getyears()');
        return $this->db->resultset();
    }
}