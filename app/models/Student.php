<?php
class Student
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetActiveStudents()
    {
        $this->db->query('CALL sp_getstudentbystatus(:sid,:cid)');
        $this->db->bind(':sid',1);
        $this->db->bind(':cid',$_SESSION['centerid']);
        return $this->db->resultset();
    }
}