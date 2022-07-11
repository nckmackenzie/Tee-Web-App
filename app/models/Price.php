<?php
class Price
{
    private $db;
    public function __construct(){
        $this->db = new Database;
    }
    
    //get saved prices
    public function GetPrices()
    {
        $this->db->query('SELECT * FROM vw_prices');
        return $this->db->resultset();
    }

    //get created books
    public function GetBooks()
    {
        $this->db->query('SELECT ID,UCASE(Title) As Title FROM books WHERE Deleted = 0 ORDER BY Title');
        return $this->db->resultset();
    }

    //create and update prices
    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            $this->db->query('INSERT INTO prices (BookId,Price,StartDate,EndDate) VALUES(:bid,:price,:sdate,:edate)');
        }else{
            $this->db->query('UPDATE prices SET BookId=:bid,Price=:price,StartDate=:sdate,EndDate=:edate WHERE (ID=:id)');
            $this->db->bind(':id',$data['id']);
        }
        $this->db->bind(':bid',$data['bookid']);
        $this->db->bind(':price',$data['price']);
        $this->db->bind(':sdate',$data['startdate']);
        $this->db->bind(':edate',$data['enddate']);
        if(!$this->db->execute()) {
            return false;
        }else{
            return true;
        }
    }
}