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

    public function GetBooks($id)
    {
        $this->db->query('SELECT ID,UCASE(Title) AS BookName 
                          FROM books 
                          WHERE (CourseId = :cid) AND (Active = 1) AND (Deleted =0)
                          ORDER BY BookName');
        $this->db->bind(':cid',intval($id));
        return $this->db->resultset();
    }

    public function CheckExamName($name,$id)
    {
        $this->db->query('SELECT COUNT(*) FROM exams WHERE (ExamName = :ename) AND (ID <> :id) AND (Deleted = 0)');
        $this->db->bind(':ename',strtolower($name));
        $this->db->bind(':id',intval($id));
        if(intval($this->db->getvalue()) > 0) {
            return false;
        }else{
            return true;
        }
    }

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            $this->db->query('INSERT INTO exams (ExamName,CourseId,BookId) 
                              VALUES(:ename,:course,:bookid)');
        }else{
            $this->db->query('UPDATE exams SET ExamName=:ename,CourseId=:course,BookId=:bookid
                              WHERE  (ID = :id)');
        }
        $this->db->bind(':ename',$data['examname']);
        $this->db->bind(':course',$data['course']);
        $this->db->bind(':bookid',$data['bookid']);
        if($data['isedit']){
            $this->db->bind(':id',$data['id']);
        }
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }

    public function GetExam($id)
    {
        $this->db->query('SELECT * FROM exams WHERE ID = :id');
        $this->db->bind(':id',trim($id));
        return $this->db->single();
    }

    public function Delete($id)
    {
        $this->db->query('UPDATE exams SET Deleted = 1 WHERE ID = :id');
        $this->db->bind(':id',trim($id));
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }

    public function GetGroups()
    {
        $this->db->query('SELECT ID, UCASE(GroupName ) AS GroupName
                          FROM groups 
                          WHERE (Active = 1) AND (Deleted = 0) ORDER BY GroupName');
        return $this->db->resultSet();
    }

    public function GetStudentsByGroup($id)
    {
        $this->db->query('SELECT ID,StudentName 
                          FROM vw_membersbygroup
                          WHERE GroupId = :gid
                          ORDER BY StudentName');
        $this->db->bind(':gid',$id);
        return $this->db->resultSet();
    }

    public function CheckExamSubmission($group,$exam)
    {
        $this->db->query('SELECT COUNT(*) 
                          FROM exam_marking_header
                          WHERE (GroupId = :group) AND (ExamId = :exam)');
        $this->db->bind(':group',$group);
        $this->db->bind(':exam',$exam);
        if(intval($this->db->getvalue()) > 0){
            return false;
        }else{
            return true;
        }
    }
}