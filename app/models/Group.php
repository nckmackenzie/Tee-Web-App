<?php

class Group
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetGroups()
    {
        $this->db->query('SELECT * FROM vw_groups');
        return $this->db->resultSet();
    }

    public function CheckGroupName($name,$parish,$id)
    {
        $this->db->query('SELECT COUNT(*) FROM groups 
                          WHERE (GroupName = :gname) AND (ParishName = :parish) AND (ID <> :id) AND (Deleted = 0)');
        $this->db->bind(':gname',strtolower(trim($name)));
        $this->db->bind(':parish',strtolower(trim($parish)));
        $this->db->bind(':id',trim($id));
        if(intval($this->db->getvalue()) > 0){
            return false;
        }else{
            return true;
        }
    }

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            $this->db->query('INSERT INTO groups (GroupName,ParishName) VALUES(:gname,:parish)');
        }else{
            $this->db->query('UPDATE groups SET GroupName = :gname,ParishName = :parish,Active = :active 
                              WHERE (ID = :id)');
        }
        $this->db->bind(':gname',strtolower($data['groupname']));
        $this->db->bind(':parish',strtolower($data['parishname']));
        if($data['isedit']){
            $this->db->bind(':active',$data['active']);
            $this->db->bind(':id',$data['id']);
        }
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }

    public function GetGroup($id)
    {
        $this->db->query("SELECT * FROM groups WHERE (ID = :id)");
        $this->db->bind(':id',trim($id));
        return $this->db->single();
    }
}