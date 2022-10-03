<?php

class Userright
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    //get users from center
    public function GetUsers()
    {
        $this->db->query('SELECT ID,
                                 UCASE(UserName) As UserName 
                          FROM users 
                          WHERE (Active = 1) AND (Deleted = 0) 
                                AND (UserTypeId > 3) AND (CenterId = :cid)');
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }

    //get forms
    public function GetForms()
    {
        $sql = 'SELECT ID,
                       UCASE(FormName) As FormName 
                FROM forms';
        if(!converttobool($_SESSION['ishead'])){
            $sql .= ' WHERE (ForCenter = 1)';
        }
        $this->db->query($sql);
        return $this->db->resultset();
    }

    public function CreateUpdate($data)
    {
        $count = 0;
        for ($i=0; $i < count($data['forms']); $i++) {
            if((int)$data['access'][$i] === 1) {
                $this->db->query('INSERT INTO userrights (UserId,FormId,Access) VALUES(:usid,:fid,:access)');
                $this->db->bind(':usid',!empty($data['user']) ? $data['user'] : null);
                $this->db->bind(':fid',$data['forms'][$i]);
                $this->db->bind(':access',$data['access'][$i]);
                if($this->db->execute()){
                    $count++;
                }
            }
        }
        if($count > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Clone($data)
    {
        $this->db->query('CALL sp_clonerights(:from,:to)');
        $this->db->bind(':from',$data['from']);
        $this->db->bind(':to',$data['to']);
        if(!$this->db->execute()){
            return false;
        }
        return true;
    }
}