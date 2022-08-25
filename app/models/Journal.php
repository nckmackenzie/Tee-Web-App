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

    public function GetJournalNo($type = 'current')
    {
        $this->db->query('SELECT 
                            COUNT(*) 
                          FROM
                            ledger
                          WHERE
                            (IsJournal = 1) AND (Deleted = 0) AND (CenterId = :cid)');
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        $count = (int)$this->db->getvalue();
        if((int)$count === 0){
            return 1;
        }elseif ((int)$count > 0) {
            $dir = $type === 'current' ? 'DESC' : 'ASC';
            $this->db->query('SELECT 
                                JournalNo 
                              FROM
                                ledger
                              WHERE
                                (IsJournal = 1) AND (Deleted = 0) AND (CenterId = :cid)
                              ORDER BY JournalNo '.$dir.'
                              LIMIT 1');
            $this->db->bind(':cid',(int)$_SESSION['centerid']);
            if($type === 'current'){
                return (int)$this->db->getvalue() + 1;
            }else{
                return (int)$this->db->getvalue();
            }
            
        }
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