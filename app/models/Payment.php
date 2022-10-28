<?php

class Payment
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }
}