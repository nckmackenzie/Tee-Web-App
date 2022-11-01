<?php
class Feereport
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }
}