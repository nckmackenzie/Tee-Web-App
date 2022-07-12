<?php
class Stocks extends Controller
{
    public function __construct()
    {
        if(!is_authenticated($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->stockmodel = $this->model('Stock');
    }
}