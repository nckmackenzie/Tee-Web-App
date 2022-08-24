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
}