<?php
class Book
{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function GetItems(){
        $this->db->query('CALL sp_bookslist(:tdate)');
        $this->db->bind(':tdate',date('Y-m-d'));
        return $this->db->resultSet();
    }
}