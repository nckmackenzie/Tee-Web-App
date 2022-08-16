<?php
class Invoices extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->invoicemodel = $this->model('Invoice');
    }

    public function index()
    {
        $data = [
            'title' => 'Invoices',
            'has_datatable' => true,
            'invoices' => $this->invoicemodel->GetInvoices(),
        ];
        $this->view('invoices/index', $data);
        exit();
    }
}