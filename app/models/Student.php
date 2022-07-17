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
        $this->db->query('CALL sp_getstudentbystatus(:sid,:cid)');
        $this->db->bind(':sid',1);
        $this->db->bind(':cid',$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function CreateUpdate($data)
    {
        // $encrypted = encrypt($data['sname'],ENCRYPTION_KEY);
        // $decrypted = decrypt($encrypted,ENCRYPTION_KEY);
     
        if(!$data['isedit']){
            $this->db->query('INSERT INTO students (StudentName,IdNumber,AdmisionNo,Contact,GenderId,RegistrationDate,CenterId) 
                              VALUES(:sname,:idno,:admno,:contact,:gender,:regdate,:cid)');
        }else{
            $this->db->query('UPDATE students StudentName=:sname,IdNumber=:idno,AdmisionNo=:admno,
                                     Contact=:contact,GenderId=:gender,RegistrationDate=:regdate
                              WHERE  (ID = :id)');
        }
        $this->db->bind(':sname',strtolower($data['sname']));
        $this->db->bind(':idno',!empty($data['idno']) ? encrypt($data['idno'],ENCRYPTION_KEY) : null);
        $this->db->bind(':admno',!empty($data['admno']) ? strtolower($data['admno']) : null);
        $this->db->bind(':contact',encrypt($data['contact'],ENCRYPTION_KEY));
        $this->db->bind(':gender',intval($data['gender']));
        $this->db->bind(':regdate',!empty($data['admdate']) ? $data['admdate'] : null);
        if($data['isedit']){
            $this->db->bind(':id',intval($data['id']));
        }else{
            $this->db->bind(':cid',intval($_SESSION['centerid']));
        }
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}