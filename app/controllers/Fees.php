<?php
class Fees extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->feemodel = $this->model('Fee');
    }    
}