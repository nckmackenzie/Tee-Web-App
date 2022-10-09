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
    
    function RightsQuery(){
        $sql = "SELECT 	
                    u.FormId as ID,
                    f.FormName,
                    f.Module,
                    f.ModuleId,
                    f.MenuOrder,
                    u.access as access
                FROM   `userrights` u join forms f on u.FormId = f.ID
                WHERE   u.UserId = :usid AND f.Module <> 'Admin'";
        $sql .= 'UNION ALL ';
        $sql .= "SELECT  
                    ID,
                    FormName,
                    Module,
                    ModuleId,
                    MenuOrder,
                    0 as access
                FROM  forms
                WHERE (Module <> 'Admin') AND ID NOT IN (SELECT FormId FROM userrights WHERE (UserId = :usid))";
        if((!converttobool($_SESSION['ishead']))) :
            $sql .= " AND (ForCenter = 1) ";
        endif;
        $sql .=" ORDER BY ModuleId,MenuOrder";
        return $sql;
    }

    //get forms
    public function GetForms($user)
    {
        $this->db->query($this->RightsQuery());
        $this->db->bind(':usid',(int)$user);
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

    public function CheckRights($userid)
    {
        $sql = 'SELECT COUNT(*) FROM userrights WHERE UserId = ?';
        $hasrights = (int)getdbvalue($this->db->dbh,$sql,[$userid]);
        if($hasrights === 0) return false;
        return true;
    }
}