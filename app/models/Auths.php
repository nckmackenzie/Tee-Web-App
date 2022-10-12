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

    public function CheckUserAvailability($contact,$center,$id)
    {
        $count = getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM users WHERE (contact = ?)',[$contact]);
        if($count === 0 ){
            return false;
        }
        $usertype = getdbvalue($this->db->dbh,'SELECT UserTypeId FROM users WHERE (Contact = ?)',[$contact]);
        if((int)$usertype === 1){
            return true;
        }

        $arr = array();
        array_push($arr,$contact);
        array_push($arr,(int)$center);
        array_push($arr,$id);
        if((int)getdbvalue($this->db->dbh,"SELECT fn_checkuseravailability(?,?,?)",$arr) === 0){
           return false; 
        }else{
            return true;
        }
    }

    public function Login($contact,$password,$center)
    {
        //check if super admin
        $count = getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM users WHERE (contact = ?)',[$contact]);
        if($count === 0 ){
            return false;
        }
        $usertype = getdbvalue($this->db->dbh,'SELECT UserTypeId FROM users WHERE (Contact = ?)',[$contact]);

        if((int)$usertype === 1){
            $this->db->query('CALL `sp_userdetails_sa`(:cont)');
            $this->db->bind(':cont',trim($contact));
        }else{
            $this->db->query('CALL `sp_userdetails`(:cont, :center)');
            $this->db->bind(':cont',trim($contact));
            $this->db->bind(':center',trim($center));
        }
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

    public function GetCenterDetails($center)
    {
        $this->db->query('SELECT * FROM centers WHERE ID = :id');
        $this->db->bind(':id',$center);
        $center = $this->db->single();
        return [$center->IsHead,$center->CenterName,$center->ExamCenter];
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