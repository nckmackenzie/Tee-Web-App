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
        $this->db->query('INSERT INTO user (UserName,`Password`,Contact,UserType) VALUES(:uname,:pwd,:contact,:utype)');
        $this->db->bind(':uname',strtotime(trim($data['username'])));
        $this->db->bind(':pwd',password_hash(trim($data['password']),PASSWORD_DEFAULT));
        $this->db->bind(':contact',trim($data['contact']));
        $this->db->bind(':utype',trim($data['usertype']));
        if (!$this->db->execute()) {
            return false;
        }else{
            return true;
        }
    }
}