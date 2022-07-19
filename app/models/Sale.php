<?php

class Sale
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetSales()
    {
        $this->db->query('SELECT * FROM vw_sales WHERE (CenterId = :cid)');
        $this->db->bind(':cid',intval($_SESSION['centerid']));
        return $this->db->resultset();
    }

    public function GetStudents()
    {
        $this->db->query('SELECT ID,UCASE(StudentName) AS StudentName 
                          FROM   students 
                          WHERE  (CenterId = :cid) AND (Deleted =0)');
        $this->db->bind(':cid',intval($_SESSION['centerid']));
        return $this->db->resultset();
    }
    
    public function GetSaleId()
    {
        $this->db->query("SELECT COUNT(*) FROM sales_header WHERE (CenterId = :cid) AND (Deleted = 0)");
        $this->db->bind(':cid',intval($_SESSION['centerid']));
        if(intval($this->db->getvalue()) === 0){
            return 1;
        }else{
            $this->db->query('SELECT SalesID FROM sales_header 
                              WHERE (CenterId = :cid) AND (Deleted = 0) 
                              ORDER BY SalesID DESC LIMIT 1');
            $this->db->bind(':cid',intval($_SESSION['centerid']));
            return intval($this->db->getvalue()) + 1;
        }
    }

    public function GetBooks()
    {
        $this->db->query("SELECT ID,UCASE(Title) AS Title 
                          FROM books 
                          WHERE (Deleted = 0) AND (Active = 1)
                          ORDER BY Title");
        return $this->db->resultset();
    }

    public function GetStockAndRate($date,$id)
    {
        $this->db->query("SELECT fn_getprice(b.ID,:cdate) As Rate,
                                 fn_getstock(b.ID,:cdate,:cid) As Stock 
                          FROM `books` b WHERE b.ID = :bid");
        $this->db->bind(':cdate',$date);
        $this->db->bind(':cid',intval($_SESSION['centerid']));
        $this->db->bind(':bid',$id);
        return $this->db->single();
    }
}