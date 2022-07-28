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

    public function GetStudents()
    {
        $this->db->query('SELECT 
                            ID,
                            UCASE(StudentName) AS StudentName 
                          FROM students 
                          WHERE (StatusId = 1) AND (Deleted = 0)
                          ORDER BY StudentName');
        return $this->db->resultSet();
    }

    public function GetGroupMembers()
    {
        $this->db->query('SELECT * FROM vw_groupmembers');
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

    public function Delete($id)
    {
        $this->db->query('UPDATE groups SET Deleted = 1 
                          WHERE (ID = :id)');
        $this->db->bind(':id',intval($id));
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }

    public function GetMembersByGroup($id)
    {
        $this->db->query('SELECT 
                            m.MemberId,
                            UCASE(StudentName) AS StudentName
                          FROM  
                            group_members m
                          INNER JOIN 
                            students s
                            ON m.MemberId = s.ID
                          WHERE (GroupId = :gid)');
        $this->db->bind(':gid',trim($id));
        return $this->db->resultset();
    }

    public function ManageCreateUpdate($data)
    {
        $saved = 0;
        for ($i=0; $i < count($data['studentsid']); $i++) { 
            $this->db->query('INSERT INTO group_members (GroupId,MemberId) VALUES(:gid,:student)');
            $this->db->bind(':gid',$data['group']);
            $this->db->bind(':student',$data['studentsid'][$i]);
            if($this->db->execute()){
                $saved++;
            }
        }

        if($saved === 0){
            return false;
        }else{
            return true;
        }
    }
}