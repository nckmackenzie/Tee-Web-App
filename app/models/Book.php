<?php
class Book
{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    //get books list from database
    public function GetBooks(){
        $this->db->query('CALL sp_bookslist(:tdate)');
        $this->db->bind(':tdate',date('Y-m-d'));
        return $this->db->resultSet();
    }
}