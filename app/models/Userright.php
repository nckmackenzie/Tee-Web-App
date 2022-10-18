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
                                AND (UserTypeId > 1)');
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
                WHERE   u.UserId = :usid ";
        $sql .= 'UNION ALL ';
        $sql .= "SELECT  
                    ID,
                    FormName,
                    Module,
                    ModuleId,
                    MenuOrder,
                    0 as access
                FROM  forms
                WHERE ID NOT IN (SELECT FormId FROM userrights WHERE (UserId = :usid))";
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
        try {
            $this->db->dbh->beginTransaction(); //begin transaction

            //delete existing rights
            $this->db->query('DELETE FROM userrights WHERE (UserId = :userid)');
            $this->db->bind(':userid',$data['user']);
            $this->db->execute();

            //loop through data['rights'] and insert new rights
            for ($i=0; $i < count($data['rights']); $i++) {
                if((int)$data['rights'][$i]->access === 1) {
                    $this->db->query('INSERT INTO userrights (UserId,FormId,Access) VALUES(:usid,:fid,:access)');
                    $this->db->bind(':usid',$data['user']);
                    $this->db->bind(':fid',$data['rights'][$i]->formId);
                    $this->db->bind(':access',$data['rights'][$i]->access);
                    $this->db->execute();
                 }
            }

            if(!$this->db->dbh->commit()){
                return false;
            }
            return true;
            
        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            error_log($e->getMessage(),0);
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