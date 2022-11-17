<?php

class Student
{
    private $db;
    private $OpensslEncryption;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetActiveStudents()
    {
        $this->db->query('CALL sp_getstudentbystatus(:sid)');
        $this->db->bind(':sid',1);
        return $this->db->resultset();
    }

    public function GetCourses()
    {
        $this->db->query('SELECT 
                            ID,
                            UCASE(CourseName) AS CourseName 
                          FROM 
                            courses 
                          WHERE 
                            (Active = 1) AND (Deleted = 0)
                          ORDER BY CourseName');
        return $this->db->resultset();
    }

    public function CreateUpdate($data)
    {
        // $encrypted = encrypt($data['sname'],ENCRYPTION_KEY);
        // $decrypted = decrypt($encrypted,ENCRYPTION_KEY);
     
        if(!$data['isedit']){
            $this->db->query('INSERT INTO students (StudentName,IdNumber,AdmisionNo,Contact,GenderId,
                                                    RegistrationDate,CourseId,Email,GuidNCouns,TrainerOfTrainers) 
                              VALUES(:sname,:idno,:admno,:contact,:gender,:regdate,:course,:email,:guidance,:trainer)');
        }else{
            $this->db->query('UPDATE students SET StudentName=:sname,IdNumber=:idno,AdmisionNo=:admno,
                                     Contact=:contact,GenderId=:gender,RegistrationDate=:regdate,
                                     CourseId = :course,Email=:email,GuidNCouns = :guidance,TrainerOfTrainers = :trainer 
                              WHERE  (ID = :id)');
        }
        $this->db->bind(':sname',strtolower($data['sname']));
        $this->db->bind(':idno',!empty($data['idno']) ? $data['idno'] : null);
        $this->db->bind(':admno',!empty($data['admno']) ? strtolower($data['admno']) : null);
        $this->db->bind(':contact',$data['contact']);
        $this->db->bind(':gender',intval($data['gender']));
        $this->db->bind(':regdate',!empty($data['admdate']) ? $data['admdate'] : null);
        $this->db->bind(':course',$data['course']);
        $this->db->bind(':email',!empty($data['email']) ? $data['email'] : null);
        $this->db->bind(':guidance',$data['guidance']);
        $this->db->bind(':trainer',$data['trainer']);
        if($data['isedit']){
            $this->db->bind(':id',intval($data['id']));
        }
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }

    public function CheckFieldsAvailability($field,$param,$id)
    {
        $sql = 'SELECT COUNT(*) FROM students WHERE ID <> :id AND (Deleted = 0) AND ('.$field.' = :param)';
        $this->db->query($sql);
        $this->db->bind(':id',intval($id));
        $this->db->bind(':param',($field === 'Contact' || $field === 'IdNumber') ? encrypt($param) : $param);
        if(intval($this->db->getvalue()) > 0){
            return false;
        }else{
            return true;
        }
    }

    public function GetStudent($id)
    {
        $this->db->query('SELECT * FROM students WHERE ID = :id');
        $this->db->bind(':id',intval($id));
        return $this->db->single();
    }

    public function Delete($id)
    {
        $this->db->query('UPDATE students SET Deleted = 1 WHERE ID = :id');
        $this->db->bind(':id',intval($id));
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}