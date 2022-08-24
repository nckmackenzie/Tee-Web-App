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
}