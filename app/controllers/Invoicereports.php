<?php

class Invoicereports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        if((int)$_SESSION['usertypeid'] > 3){
            redirect('auth/unauthorized');
            exit();
        }
        $this->reportmodel = $this->model('Invoicereport');
    }

    public function due()
    {
        $data = [
            'title' => 'Due Invoices',
            'has_datatable' => true,
            'suppliers' => $this->reportmodel->GetSuppliers()
        ];
        $this->view('invoicereports/due', $data);
        exit;
    }

    public function duerpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){

        }else{
            redirect('auth/forbidden');
            exit;
        }
    }
}