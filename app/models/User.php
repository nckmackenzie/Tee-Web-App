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

    function Save($data){
        try {
            $this->db->dbh->beginTransaction();

            if(!$data['isedit']){
                $this->db->query('INSERT INTO users (UserName,`Password`,Contact,UserTypeId) VALUES(:uname,:pwd,:contact,:utype)');
                $this->db->bind(':uname',strtolower(trim($data['username'])));
                $this->db->bind(':pwd',password_hash(trim($data['password']),PASSWORD_DEFAULT));
                $this->db->bind(':contact',trim($data['contact']));
                $this->db->bind(':utype',trim($data['usertype']));
            }else{
                $this->db->query('UPDATE users SET UserName=:uname,Contact=:contact,UserTypeId=:utype,Active=:active WHERE ID=:id');
                $this->db->bind(':uname',strtolower(trim($data['username'])));
                $this->db->bind(':contact',trim($data['contact']));
                $this->db->bind(':utype',trim($data['usertype']));
                $this->db->bind(':active',$data['active']);
                $this->db->bind(':id',(int)$data['id']);
            }
            $this->db->execute();
            $uid = $data['isedit'] ? $data['id'] : $this->db->dbh->lastInsertId();
            if($data['isedit']){
                $this->db->query('DELETE FROM user_centers WHERE UserId=:id');
                $this->db->bind(':id',(int)$data['id']);
                $this->db->execute();
            }

            for($i = 0; $i < count($data['center']); $i++){
                $this->db->query('INSERT INTO user_centers (UserId,CenterId) VALUES(:usid,:cid)');
                $this->db->bind(':usid',(int)$uid);
                $this->db->bind(':cid',(int)$data['center'][$i]);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
            
        } catch (PDOException $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            error_log($e->getMessage(),0);
            return false;
        }
    }

    //create user
    public function CreateUser($data)
    {
        return $this->Save($data);
    }

    #Get single user information
    public function GetUser($id)
    {
        $this->db->query('SELECT * FROM users WHERE ID=:id');
        $this->db->bind(':id' , trim($id));
        return $this->db->single();
    }

    public function GetUserCenters($id)
    {
        return loadresultset($this->db->dbh,'SELECT CenterId FROM user_centers WHERE UserId = ?',[$id]);
    }

    #delete user
    public function Delete($id)
    {
        $this->db->query('UPDATE users SET Deleted = 1 WHERE ID=:id');
        $this->db->bind(':id',$id);
        if($this->db->execute()) {
            return true;
        }else{
            return false;
        }
    }

    public function GetLogs($data)
    {
        $sql = "SELECT * FROM vw_sales_logs WHERE (EditDate BETWEEN ? AND ?) AND (CenterId = ?) ORDER BY EditDate";
        return loadresultset($this->db->dbh,$sql,[$data['startdate'],$data['enddate'],(int)$_SESSION['centerid']]);
    }
}