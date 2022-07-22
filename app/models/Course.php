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

    public function CheckFieldAvailability($field,$value,$id)
    {
        $sql = 'SELECT COUNT(*) FROM courses WHERE '.$field.' = :val AND ID <> :id';
        $this->db->query($sql);
        $this->db->bind(':val',strtolower($value));
        $this->db->bind(':id',$id);
        if(intval($this->db->getvalue()) > 0){
            return false;
        }else{
            return true;
        }
    }

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            $this->db->query('INSERT INTO courses (CourseName,CourseCode) VALUES(:cname,:code)');
        }else{
            $this->db->query('UPDATE courses SET CourseName=:cname,CourseCode=:code,Active = :active 
                              WHERE (ID = :id)');
        }
        $this->db->bind(':cname',strtolower($data['coursename']));
        $this->db->bind(':code',!empty($data['coursecode']) ? strtolower($data['coursecode']) : null);
        if($data['isedit']){
            $this->db->bind(':active',$data['active']);
            $this->db->bind(':id',$data['id']);
        }

        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }

    public function GetCourse($id)
    {
        $this->db->query('SELECT * FROM courses WHERE ID = :id');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    public function Delete($id)
    {
        $this->db->query('UPDATE courses SET Deleted = 1 WHERE id = :id');
        $this->db->bind(':id',$id);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}