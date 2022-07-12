<?php
class Stock
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
}