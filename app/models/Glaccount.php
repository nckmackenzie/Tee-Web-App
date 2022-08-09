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

    public function GetAccountTypes()
    {
        $this->db->query('SELECT ID,UCASE(AccountName) As AccountName FROM accounttypes WHERE AccountTypeId IS NULL');
        return $this->db->resultset();
    }

    public function CheckNameAvailability($name,$id)
    {
        $this->db->query('SELECT COUNT(*) 
                          FROM accounttypes 
                          WHERE (ID <> :id) AND (AccountName = :accname)');
        $this->db->bind(":id",$id);
        $this->db->bind(':accname',strtolower($name));
        if((int)$this->db->getvalue() > 0){
            return false;
        }else{
            return true;
        }
    }

    public function CreateUpdate($data)
    {
        $this->db->query('INSERT INTO accounttypes (AccountName,AccountTypeId) VALUEStyp,:atype)');
        $this->db->bind(':aname',!empty($data['accountname']) ? strtolower($data['accountname']) : null);
        $this->db->bind(':atype',!empty($data['accounttypee']) ? strtolower($data['accounttypee']) : null);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}
