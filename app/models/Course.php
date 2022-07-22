<?php 

class Course
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetCourses()
    {
        $this->db->query('SELECT * FROM vw_courses');
        return $this->db->resultSet();
    }
}