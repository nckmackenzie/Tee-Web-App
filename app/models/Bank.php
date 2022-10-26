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

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            return $this->save($data);
        }
    }
}