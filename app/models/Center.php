<?php
class Center
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetCenters()
    {
        $this->db->query('CALL sp_getcenters()');
        return $this->db->resultSet();
    }

    public function CheckAvailability($field,$id,$param)
    {
        $this->db->query('SELECT COUNT(ID) 
                          FROM centers 
                          WHERE (ID <> :id) AND (Deleted = 0) AND ('.$field.' = :field)');
        $this->db->bind(":id",$id);
        $this->db->bind(':field',strtolower($param));
        if((int)$this->db->getvalue() > 0){
            return false;
        }else{
            return true;
        }
    }

    function Save($data){
        try {
            $this->db->dbh->beginTransaction();

            $this->db->query('INSERT INTO centers (CenterName,Contact,Email,ExamCenter) 
                              VALUES(:cname,:contact,:email,:ecenter)');
            $this->db->bind(':cname',strtolower($data['name']));
            $this->db->bind(':contact',$data['contact']);
            $this->db->bind(':email',!empty($data['email']) ? $data['email'] : null);
            $this->db->bind(':ecenter',$data['examcenter']);
            $this->db->execute();
            $tid = $this->db->dbh->lastInsertId();

            $this->db->query('INSERT INTO users (UserName,`Password`,Contact,UserTypeId,CenterId) 
                              VALUES(:uname,:pwd,:contact,:utype,:cid)');
            $this->db->bind(':uname','administrator');
            $this->db->bind(':pwd',password_hash(123456,PASSWORD_DEFAULT));
            $this->db->bind(':contact',trim($data['contact']));
            $this->db->bind(':utype',2);
            $this->db->bind(':cid',(int)$tid);
            $this->db->execute();

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            throw $e;
            return false;
        }
    }

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            return $this->Save($data);
        }else{
            $this->db->query('UPDATE centers SET CenterName=:cname,Contact=:contact,Email=:email,ExamCenter=:ecenter 
                              WHERE (ID=:id)');
            $this->db->bind(':cname',strtolower($data['name']));
            $this->db->bind(':contact',$data['contact']);
            $this->db->bind(':email',!empty($data['email']) ? $data['email'] : null);
            $this->db->bind(':ecenter',$data['examcenter']);
            $this->db->bind(':id',$data['id']);
            if(!$this->db->execute()){
                return false;
            }else{
                return true;
            }
        }
    }

    public function GetCenter($id)
    {
        $this->db->query('SELECT * FROM centers WHERE (ID=:id)');
        $this->db->bind(':id',trim($id));
        return $this->db->single();
    }

    public function Delete($id)
    {
        $this->db->query('UPDATE centers SET Deleted = 1 WHERE (ID=:id)');
        $this->db->bind(':id',$id);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}