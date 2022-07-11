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
}