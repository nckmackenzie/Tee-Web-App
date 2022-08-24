<?php
class Journal
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
}