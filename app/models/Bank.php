<?php

class Bank
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    //get all banks
    public function GetBanks()
    {
        $sql = 'SELECT ID,UCASE(BankName) AS BankName,AccountNo FROM banks WHERE Deleted = 0 ORDER BY BankName';
        return loadresultset($this->db->dbh,$sql,[]);
    }

    function Save($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('INSERT INTO banks (BankName,AccountNo) VALUES(:bname,:accno)');
            $this->db->bind(':bname',$data['bankname']);
            $this->db->bind(':accno',$data['accountno']);
            $this->db->execute();
            $tid= $this->db->dbh->lastInsertId();

            if($data['openingbal'] > 0){
                $narr = $data['bankname'] . ' opening balance';
                savetoledger($this->db->dbh,$data['asof'],'cash at bank',$data['openingbal'],0,
                             $narr,3,8,$tid,$_SESSION['centerid']);

                savetoledger($this->db->dbh,$data['asof'],'opening balance equity',0,$data['openingbal'],
                             $narr,6,8,$tid,$_SESSION['centerid']);

                savebankposting($this->db->dbh,$data['asof'],$tid,$data['openingbal'],0,null,$narr,8,$tid,$_SESSION['centerid']);
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
            
        } catch (PDOException $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            error_log($e->getMessage(),0);
            return false;
        }
    }

    function update($data){
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE banks SET BankName =:bname,AccountNo=:accno
                              WHERE (ID = :id)');
            $this->db->bind(':bname',$data['bankname']);
            $this->db->bind(':accno',$data['accountno']);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
            
        } catch (PDOException $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            error_log($e->getMessage(),0);
            return false;
        }
    }

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            return $this->save($data);
        }else{
            return $this->update($data);
        }
    }

    public function GetBank($id)
    {
        $this->db->query('SELECT * FROM banks WHERE (ID = :id) AND (Deleted = 0)');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    public function ValidateDelete($id)
    {
        $bankcount = getdbvalue($this->db->dbh,'SELECT COUNT(*) FROM bankpostings 
                                                WHERE (BankId = ?) AND (TransactionType <> ?) AND (Deleted = 0)',[$id,8]);
        if((int)$bankcount > 0){
            return false;
        }else{
            return true;
        }
    }

    public function Delete($id)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE banks SET Deleted = 1
                              WHERE (ID = :id)');
            $this->db->bind(':id',$id);
            $this->db->execute();

            $this->db->query('UPDATE bankpostings SET Deleted = 1
                              WHERE (BankId = :id)');
            $this->db->bind(':id',$id);
            $this->db->execute();

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
            
        } catch (PDOException $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            error_log($e->getMessage(),0);
            return false;
        }
    }
}