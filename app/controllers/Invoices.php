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

    public function add()
    {
        $data = [
            'title' => 'Add Invoice',
            'suppliers' => $this->invoicemodel->GetSuppliers(),
            'vattype' => $this->invoicemodel->GetVatTypes(),
            'vat' => $this->invoicemodel->GetVats(),
            'books' => $this->invoicemodel->GetBooks(),
            'touched' => false,
            'isedit' => '',
            'id' => '',
            'invoicedate' => date('Y-m-d'),
            'duedate' => '',
            'vattype' => '',
            'vat' => '',
            'invoiceno' => '',
            'invoicedate_err' => '',
            'duedate_err' => '',
            'vattype_err' => '',
            'vat_err' => '',
            'invoiceno_err' => '',
            'table' => [],
            'save_err'=> ''
        ];
        $this->view('invoices/add', $data);
        exit();
    }
}