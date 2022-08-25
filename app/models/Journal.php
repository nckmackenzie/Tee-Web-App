<?php
class Journal
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetGlAccounts()
    {
        $this->db->query('SELECT 
                            ID,
                            UCASE(AccountName) As AccountName
                          FROM
                            accounttypes
                          WHERE
                            AccountTypeId IS NOT NULL AND (IsBank = 0)
                          ORDER BY AccountName ASC');
        return $this->db->resultset();
    }

    public function GetJournalNo()
    {
        $this->db->query('SELECT fn_getjournalno(:cid) AS journalno');
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->getvalue();
    }

    function GetAccountDetails($id){
        $this->db->query('SELECT AccountName,AccountTypeId FROM accounttypes WHERE ID = :id');
        $this->db->bind(':id',(int)$id);
        $account = $this->db->single();
        return [$account->AccountName,$account->AccountTypeId];
    }

    public function CreateUpdate($data)
    {
        try{
            $this->db->dbh->beginTransaction();
            $test = [];

            for($i = 0; $i < count($data['accountsid']); $i++){
                $accountname = $this->GetAccountDetails($data['accountsid'][$i])[0];
                $accountid = $this->GetAccountDetails($data['accountsid'][$i])[1];
                
                $this->db->query('INSERT INTO ledger (TransactionDate,Account,Debit,Credit,Narration,AccountId,
                                                      IsJournal,JournalNo,CenterId) 
                                  VALUES(:jdate,:account,:debit,:credit,:narr,:aid,:isjournal,:jno,:cid)');
                $this->db->bind(':jdate',!empty($data['jdate']) ? $data['jdate'] : null);
                $this->db->bind(':account', $accountname);
                $this->db->bind(':debit',$data['debits'][$i]);
                $this->db->bind(':credit',$data['credits'][$i]);
                $this->db->bind(':narr',!empty($data['description']) ? strtolower($data['description']) : null);
                $this->db->bind(':aid', $accountid);
                $this->db->bind(':isjournal', 1);
                $this->db->bind(':jno', $data['journalno']);
                $this->db->bind(':cid', $_SESSION['centerid']);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }
                
        }catch(\Exception $e){
            if(!$this->db->dbh->inTransaction()){
                $this->db->dbh->rollback();
            }
            throw $e;
            return false;
        }
    }
}