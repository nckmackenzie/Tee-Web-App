<?php
class Report
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function Getfeepayments($data)
    {
        $this->db->query('CALL sp_feepayment_by_date(:sdate,:edate,:cid)');
        $this->db->bind(':sdate',date('Y-m-d',strtotime($data['sdate'])));
        $this->db->bind(':edate',date('Y-m-d',strtotime($data['edate'])));
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function GetSalesReport($data)
    {
        if($data['type'] === 'bycenter'){
            return loadresultset($this->db->dbh,'CALL sp_get_sales(?,?,?)',[$data['sdate'],$data['edate'],(int)$data['criteria']]);
        }elseif ($data['type'] === 'all') {
            return loadresultset($this->db->dbh,'CALL sp_get_sales_all(?,?)',[$data['sdate'],$data['edate']]);
        }elseif ($data['type'] === 'bycourse') {
            $sql = 'SELECT 
                        c.CourseName,
                        IFNULL(SUM(d.SellingValue),0) AS SumOfValue
                    FROM `sales_details` d join sales_header h on d.HeaderId = h.ID join books b 
                        on d.BookId = b.ID join courses c on b.CourseId = c.ID
                    WHERE (h.Deleted = 0) AND (h.SalesDate BETWEEN ? AND ?)';
            return loadresultset($this->db->dbh,$sql,[$data['sdate'],$data['edate']]);
        }
    }

    public function GetSalesCriterias($type)
    {
        if($type === 'bycenter'){
            $sql = 'SELECT ID,UCASE(CenterName) AS CriteriaName FROM centers WHERE (Deleted = 0) AND (Active = 1)';
        }elseif ($type === 'bycourse') {
            $sql = 'SELECT ID,UCASE(CourseName) AS CriteriaName FROM courses WHERE (Deleted = 0) AND (Active = 1)';
        }
        return loadresultset($this->db->dbh,$sql,[]);
    }
}