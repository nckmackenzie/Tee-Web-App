<?php
class Book
{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function index(){
        $this->db->query('CALL sp_booklist(:tdate)');
        $this->db->bind(':tdate',date('Y-m-d'));
        return $this->db->resultSet();
    }
}