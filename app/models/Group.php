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
        return $this->db->resultset();
    }

    public function GetStudents()
    {
        $this->db->query("SELECT 
                            ID,
                            CONCAT(UCASE(StudentName),IFNULL(CONCAT(' - ',Contact),'')) AS StudentName 
                          FROM students 
                          WHERE (StatusId = 1) AND (Deleted = 0)
                          ORDER BY StudentName");
        return $this->db->resultset();
    }

    public function GetGroupMembers()
    {
        $this->db->query('SELECT * FROM vw_groupmembers');
        return $this->db->resultset();
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
            $this->db->query('INSERT INTO groups (GroupName,ParishName,GroupLeaderId) VALUES(:gname,:parish,:gl)');
        }else{
            $this->db->query('UPDATE groups SET GroupName = :gname,ParishName = :parish,GroupLeaderId=:gl, Active = :active 
                              WHERE (ID = :id)');
        }
        $this->db->bind(':gname',strtolower($data['groupname']));
        $this->db->bind(':parish',strtolower($data['parishname']));
        $this->db->bind(':gl',$data['groupleader']);
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
                            m.MemberId AS ID,
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
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('DELETE FROM group_members WHERE (GroupId = :group)');
            $this->db->bind(':group',intval($data['group']));
            $this->db->execute();

            for ($i=0; $i < count($data['studentsid']); $i++) { 
                $this->db->query('INSERT INTO group_members (GroupId,MemberId) VALUES(:gid,:student)');
                $this->db->bind(':gid',$data['group']);
                $this->db->bind(':student',$data['studentsid'][$i]);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
            
        } catch (\Exception $e) {
            if ($this->db->dbh->inTransaction()) {
                $this->db->dbh->rollBack();
            }
            throw $e;
            return false;
        }
    }

    public function ManageDelete($id)
    {
        $this->db->query('DELETE FROM group_members WHERE (GroupId = :group)');
        $this->db->bind(':group',intval($id));
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}