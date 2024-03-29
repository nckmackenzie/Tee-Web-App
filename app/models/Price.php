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

    //get if product is available or date range for price is set
    public function CheckPriceExists($bid,$sdate,$id)
    {
        $this->db->query('SELECT fn_checkprice(:bid,:sdate,:tid)');
        $this->db->bind(':bid',$bid);
        $this->db->bind(':sdate',$sdate);
        $this->db->bind(':tid',$id);
        if((int)$this->db->getvalue() > 0){
            return false;
        }else{
            return true;
        }
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
            $this->db->query('INSERT INTO prices (BookId,BuyingPrice,SellingPrice,StartDate,EndDate) VALUES(:bid,:bprice,:price,:sdate,:edate)');
        }else{
            $this->db->query('UPDATE prices SET BookId=:bid,BuyingPrice=:bprice,SellingPrice=:price,StartDate=:sdate,EndDate=:edate WHERE (ID=:id)');
            $this->db->bind(':id',$data['id']);
        }
        $this->db->bind(':bid',$data['bookid']);
        $this->db->bind(':bprice',$data['bprice']);
        $this->db->bind(':price',$data['price']);
        $this->db->bind(':sdate',$data['startdate']);
        $this->db->bind(':edate',$data['enddate']);
        if(!$this->db->execute()) {
            return false;
        }else{
            return true;
        }
    }

    //get single price  
    public function GetPrice($id)
    {
        $this->db->query('SELECT * FROM prices WHERE ID= :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    //delete price
    public function Delete($id)
    {
        $this->db->query('UPDATE prices SET Deleted = 1 WHERE ID = :id');
        $this->db->bind(':id', $id);
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }
}