<?php
class User
{
    private $db;

    public function __construct(){
        $this->db = new Database;
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
}