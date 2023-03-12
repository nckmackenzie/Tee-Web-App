<?php

class Semister
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetSemisters()
    {
        return loadresultset($this->db->dbh,'SELECT * FROM vw_semisters',[]);
    }

    public function GetClasses()
    {
        $sql = 'SELECT ID,UCASE(ClassName) As ClassName FROM classes WHERE (Deleted = 0) ORDER BY ClassName';
        return loadresultset($this->db->dbh,$sql,[]);
    }

    public function CheckExists($data,$type)
    {
        if($type === 'name'){
            $sql = 'SELECT COUNT(*) FROM semisters WHERE SemisterName = ? AND (ID <> ?) AND (Deleted = 0)';
            if((int) getdbvalue($this->db->dbh,$sql,[strtolower($data['semistername']),$data['id']]) > 0) {
                return false;
            }else{
                return true;
            }
        }elseif($type === 'date'){
            $sql = 'SELECT COUNT(*) FROM semisters WHERE (? BETWEEN StartDate AND EndDate) AND (Deleted = 0) AND (ID <> ?) AND (ClassId <> ?)';
            if((int) getdbvalue($this->db->dbh,$sql,[strtolower($data['startdate']),$data['id'],$data['class']]) > 0) {
                return false;
            }else{
                return true;
            }
        }
    }

    public function CreateUpdate($data)
    {
        try {
            if($data['isedit']){
                $this->db->query('UPDATE semisters SET SemisterName=:sname,StartDate=:sdate,EndDate=:edate,ClassId=:cid 
                                  WHERE (ID = :id)');
            }else{
                $this->db->query('INSERT INTO semisters (SemisterName,StartDate,EndDate,ClassId) VALUES(:sname,:sdate,:edate,:cid)');
            }
            $this->db->bind(':sname',strtolower($data['semistername']));
            $this->db->bind(':sdate',$data['startdate']);
            $this->db->bind(':edate',$data['enddate']);
            $this->db->bind(':cid',$data['class']);
            if($data['isedit']){
                $this->db->bind(':id',$data['id']);
            }
            if(!$this->db->execute()){
                return false;
            }else{
                return true;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage(),0);
            return false;
        } catch (Exception $e) {
            error_log($e->getMessage(),0);
            return false;
        }
    }

    public function GetSemister($id)
    {
        $this->db->query("SELECT * FROM semisters WHERE ID = :id");
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    public function Delete($id)
    {
        try {
            $this->db->query('UPDATE semisters SET Deleted = 1 WHERE (ID = :id)'); 
            $this->db->bind(':id',$id);
            
            if(!$this->db->execute()){
                return false;
            }else{
                return true;
            }
            
        } catch (PDOException $e) {
            error_log($e->getMessage(),0);
            return false;
        } catch (Exception $e) {
            error_log($e->getMessage(),0);
            return false;
        }
    }

    public function Close($id)
    {
        try {
                $this->db->query('UPDATE semisters SET Closed = 1 WHERE (ID = :id)'); 
                $this->db->bind(':id',$id);
                
                if(!$this->db->execute()){
                    return false;
                }else{
                    return true;
                }
                
            } catch (PDOException $e) {
                error_log($e->getMessage(),0);
                return false;
            } catch (Exception $e) {
                error_log($e->getMessage(),0);
                return false;
            }
        }
}