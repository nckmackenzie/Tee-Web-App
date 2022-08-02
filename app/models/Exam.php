<?php
class Exam
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetExams()
    {
        $this->db->query('SELECT * FROM vw_exams');
        return $this->db->resultSet();
    }

    public function GetCourses()
    {
        $this->db->query('SELECT ID,UCASE(CourseName) AS CourseName 
                          FROM courses
                          WHERE (Deleted = 0) AND (Active = 1)
                          ORDER BY CourseName');
        return $this->db->resultSet();
    }

    public function CheckExamName($name,$id)
    {
        $this->db->query('SELECT COUNT(*) FROM exams WHERE (ExamName = :ename) AND (ID <> :id) AND (Deleted = 0)');
        $this->db->bind(':ename',strtolower($name));
        $this->db->bind(':ename',intval($id));
        if(intval($this->db->getvalue()) > 0) {
            return false;
        }else{
            return true;
        }
    }
}