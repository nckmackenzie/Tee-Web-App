<?php

class Auths 
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function LoadCenters()
    {
        $this->db->query("SELECT ID,
                                 UCASE(CenterName) As CenterName
                          FROM   centers WHERE Deleted = 0 ORDER BY CenterName");
        return $this->db->resultset();
    }

    public function CheckUserAvailability($userid,$center)
    {
        $arr = array();
        array_push($arr,$userid);
        array_push($arr,(int)$center);
        if((int)getdbvalue($this->db->dbh,"SELECT checkuseravailability(?,?)",$arr) === 0){
           return false; 
        }else{
            return true;
        }
    }
}