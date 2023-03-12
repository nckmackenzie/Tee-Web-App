<?php

class Classs
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetClasses()
    {
        $sql = 'SELECT ID,UCASE(ClassName) AS ClassName FROM classes WHERE (Deleted = 0) ORDER BY ClassName';
        return loadresultset($this->db->dbh,$sql,[]);
    }

    public function CheckName($classname,$id)
    {
        $sql = 'SELECT COUNT(*) FROM classes WHERE ClassName = ? AND (Deleted = 0) AND (ID <> ?)';
        $count =  getdbvalue($this->db->dbh,$sql,[$classname,$id]);
        if((int)$count > 0){
            return false;
        }
        return true;
    }

    public function CreateUpdate($data)
    {
        if($data['isedit']){
            $this->db->query('UPDATE classes SET ClassName=:class WHERE (ID=:id)');
            $this->db->bind(':class',$data['classname']);
            $this->db->bind(':id',$data['id']);
        }else{
            $this->db->query('INSERT INTO classes (ClassName) VALUES(:class)');
            $this->db->bind(':class',$data['classname']);
        }
        if(!$this->db->execute()) return false;
        return true;
    }

    public function GetClass($id)
    {
        $this->db->query('SELECT * FROM classes WHERE (ID=:id) AND (Deleted=0)');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    public function Delete($id)
    {
        $this->db->query('UPDATE classes SET Deleted = 1 WHERE (ID=:id)');
        $this->db->bind(':id',$id);
        if(!$this->db->execute()) return false;
        return true;
    }
}