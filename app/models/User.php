<?php
class User
{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function GetUsers()
    {
        $this->db->query("CALL sp_getusers(:cid)");
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function ChangeProfile($name)
    {
        $this->db->query("UPDATE users SET UserName= :uname WHERE ID= :id");
        $this->db->bind(':uname',strtolower(trim($name)));
        $this->db->bind(':id',(int)$_SESSION['userid']);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }

    //create user
    public function CreateUser($data)
    {
        if(!$data['isedit']){
            $this->db->query('INSERT INTO users (UserName,`Password`,Contact,UserTypeId,CenterId) VALUES(:uname,:pwd,:contact,:utype,:cid)');
            $this->db->bind(':uname',strtolower(trim($data['username'])));
            $this->db->bind(':pwd',password_hash(trim($data['password']),PASSWORD_DEFAULT));
            $this->db->bind(':contact',trim($data['contact']));
            $this->db->bind(':utype',trim($data['usertype']));
            $this->db->bind(':cid',(int)$_SESSION['centerid']);
        }else{
            $this->db->query('UPDATE users SET UserName=:uname,Contact=:contact,UserTypeId=:utype WHERE ID=:id');
            $this->db->bind(':uname',strtolower(trim($data['username'])));
            $this->db->bind(':contact',trim($data['contact']));
            $this->db->bind(':utype',trim($data['usertype']));
            $this->db->bind(':id',(int)$data['id']);
        }
        
        if (!$this->db->execute()) {
            return false;
        }else{
            return true;
        }
    }

    #Get single user information
    public function GetUser($id)
    {
        $this->db->query('SELECT * FROM users WHERE ID=:id');
        $this->db->bind(':id' , trim($id));
        return $this->db->single();
    }
}