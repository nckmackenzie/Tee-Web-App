<?php
class Prices extends Controller
{
    public function __construct(){
        adminonly($_SESSION['userid'],$_SESSION['usertypeid']);
        $this->pricemodel = $this->model('Price');
    }

    public function index()
    {
        $prices = $this->pricemodel->GetPrices();
        $data = [
            'title' => 'Prices',
            'has_datatable' => true,
            'prices' => $prices
        ];
        $this->view('prices/index',$data);
    }
}