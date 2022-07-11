<?php
class Prices extends Controller
{
    public function __construct(){
        adminonly($_SESSION['userid'],$_SESSION['usertypeid']);
        $this->pricemodel = $this->model('Price');
    }
}