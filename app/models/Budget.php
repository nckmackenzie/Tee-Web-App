<?php
class Budget
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
}