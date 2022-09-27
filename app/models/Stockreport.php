<?php
class Stockreport
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
    //get receipts
    public function GetReceipts($data)
    {
        $this->db->query('CALL sp_get_receipts(:sdate,:edate,:rtype,:cid)');
        $this->db->bind(':sdate',$data['sdate']);
        $this->db->bind(':edate',$data['edate']);
        $this->db->bind(':rtype',(int)$data['type']);
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultSet();
    }
}