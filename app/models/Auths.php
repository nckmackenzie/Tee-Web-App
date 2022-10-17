<?php

class Auths 
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function CheckRights($form)
    {
        return checkuserrights($this->db->dbh,$_SESSION['userid'],$form);
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
        $count = getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM users WHERE (Contact = ?)',[$contact]);
        if($count === 0 ){
            return false;
        }
        $usertype = getdbvalue($this->db->dbh,'SELECT UserTypeId FROM users WHERE (Contact = ?)',[$contact]);
        if((int)$usertype === 1){
            return true;
        }

        //get userid from contact
        $userid = getdbvalue($this->db->dbh,'SELECT ID FROM users WHERE Contact = ?',[$contact]);

        //check if user set for center selected
        $sql = 'SELECT COUNT(*) FROM user_centers WHERE UserId = ? AND (CenterId = ?)';
        $usercentercount = getdbvalue($this->db->dbh,$sql,[$userid,$center]);

        if((int)$usercentercount === 0){
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

        $this->db->query('CALL `sp_userdetails_sa`(:cont)');
        $this->db->bind(':cont',trim($contact));
        $row = $this->db->single();
        // if((int)$usertype === 1){
        //     $this->db->bind(':cont',trim($contact));
        // }else{
        //     $this->db->query('CALL `sp_userdetails`(:cont, :center)');
            
        //     $this->db->bind(':center',trim($center));
        // }
        
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