<?php
class Glaccount
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetGLAccounts()
    {
        $this->db->query('SELECT ID,UCASE(AccountName) AS AccountName
                          FROM   accounttypes
                          WHERE  (AccountTypeId IS NOT NULL) AND (IsBank = 0)
                          ORDER BY AccountName');
        return $this->db->resultset();
    }
}
