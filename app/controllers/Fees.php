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
    
    public function index()
    {
        $data = [
            'title' => 'Fees',
            'has_datatable' => true,
            'fees' => $this->feemodel->GetFees(),
        ];
        $this->view('fees/index',$data);
    }
}