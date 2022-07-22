<?php 

class Course
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }
}