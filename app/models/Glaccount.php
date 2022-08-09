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
        $this->db->query('SELECT 
                            c.ID,
                            UCASE(c.AccountName) AS AccountName,
                            UCASE(p.AccountName) As AccountType
                          FROM `accounttypes` p JOIN accounttypes c on p.ID = c.AccountTypeId
                          WHERE c.IsBank = 0
                          ORDER BY AccountType,AccountName');
        return $this->db->resultset();
    }
}
