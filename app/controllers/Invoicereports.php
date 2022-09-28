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

    public function due_with_balance_rpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $type = trim($_GET['type']);
            $results = $this->reportmodel->GetDueOrWithBalances($type);
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

    public function bydate_bysupplier()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'type' => trim($_GET['type']),
                'sdate' => !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : '',
                'edate' => !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : '',
                'supplier' => isset($_GET['supplier']) ? trim($_GET['supplier']) : '',
                'results' => []
            ];
            $results = $this->reportmodel->GetInvoicesByDateAndSupplier($data);
            foreach($results as $result){
                array_push($data['results'],[
                    'invoiceDate' => $result->InvoiceDate,
                    'dueDate' => $result->DueDate,
                    'supplierName' => $result->SupplierName,
                    'invoiceNo' => $result->InvoiceNo,
                    'invoiceValue' => $result->InclusiveVat,
                    'amountPaid' => $result->AmountPaid,
                    'balance' => $result->Balance
                ]);
            }
            echo json_encode($data['results']);
        }else{
            redirect('auth/forbidden');
            exit;
        }
    }

    //invoice payments
    public function payments()
    {
        $data = [
            'title' => 'Invoices Payments',
            'has_datatable' => true,
            'suppliers' => $this->reportmodel->GetSuppliers(),
        ];
        $this->view('invoicereports/payments', $data);
        exit;
    }

    //get invoices or supplier
    public function get_invoice_or_supplier()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $type = trim($_GET['type']);
            $results = $type === 'bysupplier' ? $this->reportmodel->GetSuppliers() : $this->reportmodel->GetInvoices();
            $data = [];
            foreach($results as $result){
                array_push($data,[
                    'id' => $result->ID,
                    'field' => $result->FieldName,
                ]);
            }
            echo json_encode($data);
        }else{
            redirect('auth/forbidden');
            exit;
        }
    }

    //get invoice payments
    public function getpaymentsrpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'type' => trim($_GET['type']),
                'sdate' => isset($_GET['sdate']) ? trim($_GET['sdate']) : null,
                'edate' => isset($_GET['edate']) ? trim($_GET['edate']) : null,
                'supplier' => isset($_GET['supplier']) ? trim($_GET['supplier']) : null,
                'invoiceno' => isset($_GET['invoiceno']) ? trim($_GET['invoiceno']) : null,
                'results' => []
            ];
            
            $results = $this->reportmodel->GetPayments($data);
            foreach($results as $result){
                array_push($data['results'],[
                    'paymentDate' => date('d-m-Y',strtotime($result->PaymentDate)),
                    'invoiceNo' => $result->InvoiceNo,
                    'supplierName' => $result->SupplierName,
                    'amount' => $result->Debit,
                    'paymentReference' => $result->PaymentReference
                ]);
            }
            echo json_encode($data['results']);
        }else{
            redirect('auth/forbidden');
            exit;
        }
    }
}