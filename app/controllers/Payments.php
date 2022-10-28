<?php

class Payments extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'supplier payments');
        $this->paymentmodel = $this->model('Payment');
    }

    public function index()
    {
        $data= [
            'title' => 'Supplier payments',
            'has_datatable' => true
        ];
        $this->view('payments/index',$data);
        exit;
    }

    public function add()
    {
        $data = [
            'title' => 'New payments',
        ];
        $this->view('payments/add',$data);
        exit;
    }
}