<?php

class Year
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    //get created financial years
    public function GetYears()
    {
        $this->db->query('CALL sp_getyears()');
        return $this->db->resultset();
    }

    //check if year exists
    public function CheckAvailability($field,$id,$param)
    {
        if($field === 'name'){
            $this->db->query('SELECT COUNT(ID) 
                              FROM   years 
                              WHERE  (ID <> :id) AND (Deleted = 0) AND (YearName = :field)');
        }else{
            $this->db->query('SELECT COUNT(ID) 
                              FROM   years 
                              WHERE  (ID <> :id) AND (Deleted = 0) AND (:field BETWEEN StartDate AND EndDate)');
        }
        $this->db->bind(":id",$id);
        $this->db->bind(':field', $field === 'name' ? strtolower(trim($param)) : $param);
        if((int)$this->db->getvalue() > 0){
            return false;
        }else{
            return true;
        }
    }

    //save and update
    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            $this->db->query('INSERT INTO years (YearName,StartDate,EndDate) VALUES(:yname,:startd,:endd)');
        }else{
            $this->db->query('UPDATE years SET YearName=:yname,StartDate=:startd,EndDate=:endd WHERE (ID=:id)');
        }
        $this->db->bind(':yname',strtolower(trim($data['name'])));
        $this->db->bind(':startd',$data['start']);
        $this->db->bind(':endd',$data['end']);
        if($data['isedit']){
            $this->db->bind(':id',$data['id']);
        }
        if(!$this->db->execute()) {
            return false;
        }else{
            return true;
        }
    }

    //close or delete year based on criteria
    public function DeleteClose($id,$action)
    {
        if($action === 'delete'){
            $this->db->query('UPDATE years SET Deleted=1 WHERE (ID=:id)');
        }elseif($action === 'close'){
            $this->db->query('UPDATE years SET Closed=1 WHERE (ID=:id)');
        }
        $this->db->bind(':id',$id);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }

    //get single year based on ID
    public function GetYear($id)
    {
        $this->db->query('SELECT * FROM years WHERE ID=:id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}