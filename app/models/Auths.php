<?php

class Auths 
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function LoadCenters()
    {
        $this->db->query("SELECT ID,
                                 UCASE(CenterName) As CenterName
                          FROM   centers WHERE Deleted = 0 ORDER BY CenterName");
        return $this->db->resultset();
    }

    public function CheckUserAvailability($contact,$center)
    {
        $arr = array();
        array_push($arr,$contact);
        array_push($arr,(int)$center);
        if((int)getdbvalue($this->db->dbh,"SELECT fn_checkuseravailability(?,?)",$arr) === 0){
           return false; 
        }else{
            return true;
        }
    }

    public function Login($userid,$password,$center)
    {
        $this->db->query('CALL `sp_userdetails`(:cont, :center)');
        $this->db->bind(':cont',trim($userid));
        $this->db->bind(':center',trim($center));
        $row = $this->db->single();
        //verify password is correct
        if (password_verify($password,$row->Password)){
            return $row;
        }else{
            return false;
        }
    }

    //validate password entered
    public function ValidatePassword($pwd)
    {
        $arr = array();
        array_push($arr,$_SESSION['userid']);
        $dbpassword = getdbvalue($this->db->dbh,'SELECT fn_getpassword(?)',$arr);
        if(password_verify($pwd,$dbpassword)){
            return true;
        }else{
            return false;
        }
    }

    //change password 
    public function ChangePassword($newpassword)
    {
        $this->db->query("UPDATE users SET `Password` = :pwd WHERE ID = :id");
        $this->db->bind(':pwd',password_hash($newpassword,PASSWORD_DEFAULT));
        $this->db->bind(':id',$_SESSION['userid']);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
   
}