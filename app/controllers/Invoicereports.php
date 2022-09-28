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

    public function index()
    {
        $data = [
            'title' => 'Invoices Reports',
            'has_datatable' => true,
            'suppliers' => $this->reportmodel->GetSuppliers()
        ];
        $this->view('invoicereports/index', $data);
        exit;
    }

    public function duerpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $results = $this->reportmodel->GetDue();
            $data = [];
            foreach($results as $result){
                array_push($data,[
                    'invoiceDate' => $result->InvoiceDate,
                    'dueDate' => $result->DueDate,
                    'supplierName' => $result->SupplierName,
                    'invoiceNo' => $result->InvoiceNo,
                    'invoiceValue' => $result->InclusiveVat,
                    'amountPaid' => $result->AmountPaid,
                    'balance' => $result->Balance
                ]);
            }
            echo json_encode($data);
        }else{
            redirect('auth/forbidden');
            exit;
        }
    }
}