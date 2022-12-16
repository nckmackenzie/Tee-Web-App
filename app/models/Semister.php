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
            $sql = 'SELECT COUNT(*) FROM semisters WHERE (? BETWEEN StartDate AND EndDate) AND (Deleted = 0) AND (ID <> ?)';
            if((int) getdbvalue($this->db->dbh,$sql,[strtolower($data['startdate']),$data['id']]) > 0) {
                return false;
            }else{
                return true;
            }
        }
    }

    public function SemisterSetAsPrevious($id,$sem)
    {
        $count = getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM semisters WHERE (ID <> ?) AND (Deleted = 0) AND (PreviousSemister = ?)',[$id,$sem]);
        if((int)$count > 0) return false;
        return true;
    }

    public function CreateUpdate($data)
    {
        try {
            if($data['isedit']){
                $this->db->query('UPDATE semisters SET SemisterName=:sname,StartDate=:sdate,EndDate=:edate,PreviousSemister=:prev 
                                  WHERE (ID = :id)');
            }else{
                $this->db->query('INSERT INTO semisters (SemisterName,StartDate,EndDate,PreviousSemister) VALUES(:sname,:sdate,:edate,:prev)');
            }
            $this->db->bind(':sname',strtolower($data['semistername']));
            $this->db->bind(':sdate',$data['startdate']);
            $this->db->bind(':edate',$data['enddate']);
            $this->db->bind(':prev',$data['previoussemister']);
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

    public function ValidateDelete($id)
    {
        $feestructurecount = getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM fee_structure WHERE SemisterId=? AND Deleted = 0',[(int)$id]);
        $feepaymentcount = getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM fees_payment WHERE SemisterId=? AND Deleted = 0',[(int)$id]);
        $initialbalcount = getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM initial_balances_header WHERE SemisterId=?',[(int)$id]);
        $semistercount = getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM semisters WHERE PreviousSemister=? AND Deleted = 0',[(int)$id]);
        //count validation
        if((int)$feestructurecount > 0 || (int)$feepaymentcount > 0 || (int)$initialbalcount > 0 || $semistercount > 0)
        {
            return false;
        }
        return true;
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