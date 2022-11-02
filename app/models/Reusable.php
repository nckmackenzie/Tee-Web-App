<?php

class Reusable 
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetCenters()
    {
        $sql = 'SELECT ID,UCASE(CenterName) AS CenterName 
                FROM centers WHERE Active =1 AND Deleted = 0 ORDER BY CenterName';
        return loadresultset($this->db->dbh,$sql,[]);
    }

    public function GetAuthorizedCenters()
    {
        if((int)$_SESSION['usertypeid'] === 1){
            return $this->GetCenters();
        }else{
            $sql = 'SELECT u.CenterId AS ID,ucase(c.CenterName) AS CenterName
                    FROM user_centers u join centers c on u.CenterId = c.ID WHERE u.UserId = ? ORDER BY CenterName';
            return loadresultset($this->db->dbh,$sql,[$_SESSION['userid']]);
        }
    }

    public function GetCenterDetails($id)
    {
       $this->db->query('SELECT * FROM centers WHERE ID = :id');
       $this->db->bind(':id',$id);
       return $this->db->single();
    }

    public function GetGlAccounts()
    {
        return loadresultset($this->db->dbh,'SELECT ID,UCASE(AccountName) AS AccountName FROM accounttypes 
                                             ORDER BY AccountName',[]);
    }

    public function GetYears($showall)
    {
        $sql = 'SELECT ID,UCASE(YearName) AS YearName FROM years WHERE (Deleted = 0)';
        if($showall === false){
            $sql .= ' WHERE (Closed = 0)';
        }
        return loadresultset($this->db->dbh,$sql,[]);
    }
}