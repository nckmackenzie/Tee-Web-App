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
        return $this->db->resultset();
    }
    //get centers
    public function GetCenters()
    {
        $this->db->query('SELECT 
                            ID,
                            ucase(CenterName) As CenterName
                          FROM
                            centers
                          WHERE
                            (Deleted = 0) AND (ID <> :id)');
        $this->db->bind(':id',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }

    //fetch transfers from db
    public function GetTransfers($data)
    {
        $this->db->query('CALL sp_get_transfers(:sdate,:edate,:tocenter,:cid)');
        $this->db->bind(':sdate',$data['sdate']);
        $this->db->bind(':edate',$data['edate']);
        $this->db->bind(':tocenter',(int)$data['center']);
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }

    //fetch stock movements from db
    public function GetMovements($data)
    {
        $this->db->query('CALL sp_get_stock_movement(:sdate,:edate,:cid)');
        $this->db->bind(':sdate',$data['sdate']);
        $this->db->bind(':edate',$data['edate']);
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }
}