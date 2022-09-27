<?php
class Stockreport
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
}