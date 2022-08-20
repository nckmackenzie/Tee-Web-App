<?php
class Fee
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetFees()
    {
        $this->db->query('SELECT * FROM vw_feepayments WHERE CenterId = :cid');
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function GetStudents()
    {
        $this->db->query('SELECT 
                            ID,
                            UCASE(StudentName) As StudentName 
                          FROM 
                            students 
                          WHERE 
                            Deleted = 0
                          ORDER BY StudentName');
        return $this->db->resultset();
    }

    public function GetAccounts()
    {
        $this->db->query('SELECT 
                            ID,
                            UCASE(AccountName) As AccountName 
                          FROM 
                            accounttypes 
                          WHERE 
                            AccountTypeId = 1
                          ORDER BY AccountName');
        return $this->db->resultset();
    }

    public function GetReceiptNo()
    {
        return getuniqueid($this->db->dbh,'ReceiptNo','fees_payment',(int)$_SESSION['centerid']);
    }
}