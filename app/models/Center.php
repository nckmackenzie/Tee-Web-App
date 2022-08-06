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

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            $this->db->query('INSERT INTO centers (CenterName,Contact,Email,ExamCenter) VALUES(:cname,:contact,:email,:ecenter)');
        }else{
            $this->db->query('UPDATE centers SET CenterName=:cname,Contact=:contact,Email=:email,ExamCenter=:ecenter 
                              WHERE (ID=:id)');
        }
        $this->db->bind(':cname',strtolower($data['name']));
        $this->db->bind(':contact',$data['contact']);
        $this->db->bind(':email',!empty($data['email']) ? $data['email'] : null);
        $this->db->bind(':ecenter',$data['examcenter']);
        if($data['isedit']){$this->db->bind(':id',$data['id']);}
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
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