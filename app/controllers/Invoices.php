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
            'vattypes' => $this->invoicemodel->GetVatTypes(),
            'vats' => $this->invoicemodel->GetVats(),
            'books' => $this->invoicemodel->GetBooks(),
            'touched' => false,
            'isedit' => false,
            'id' => '',
            'invoicedate' => '',
            'supplier' => '',
            'duedate' => '',
            'vattype' => 1,
            'vat' => '',
            'invoiceno' => '',
            'description' => '',
            'invoicedate_err' => '',
            'duedate_err' => '',
            'supplier_err' => '',
            'vattype_err' => '',
            'vat_err' => '',
            'invoiceno_err' => '',
            'table' => [],
            'save_err'=> '',
        ];
        $this->view('invoices/add', $data);
        exit();
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => !converttobool(trim($_POST['isedit'])) ?  'Add Invoice' : 'Edit Invoice',
                'suppliers' => $this->invoicemodel->GetSuppliers(),
                'vattypes' => $this->invoicemodel->GetVatTypes(),
                'vats' => $this->invoicemodel->GetVats(),
                'books' => $this->invoicemodel->GetBooks(),
                'touched' => true,
                'isedit' => converttobool(trim($_POST['isedit'])),
                'id' => trim($_POST['id']),
                'invoicedate' => !empty(trim($_POST['invoicedate'])) ? date('Y-m-d', strtotime(trim($_POST['invoicedate']))) : '',
                'supplier' => !empty($_POST['supplier']) ? $_POST['supplier'] : '',
                'duedate' => !empty(trim($_POST['duedate'])) ? date('Y-m-d', strtotime(trim($_POST['duedate']))) : '',
                'vattype' => !empty($_POST['vattype']) ? (int)$_POST['vattype'] : '',
                'vat' => !empty($_POST['vat']) ? (int)$_POST['vat'] : '',
                'vatrate' => '',
                'invoiceno' => !empty(trim($_POST['invoiceno'])) ? trim($_POST['invoiceno']) : '',
                'description' => !empty(trim($_POST['description'])) ? trim($_POST['description']) : '',
                'booksid' => $_POST['booksid'],
                'booksname' => $_POST['booksname'],
                'qtys' => $_POST['qtys'],
                'rates' => $_POST['rates'],
                'gross' => $_POST['gross'],
                'table' => [],
                'total' => 0,
                'invoicedate_err' => '',
                'duedate_err' => '',
                'supplier_err' => '',
                'vattype_err' => '',
                'vat_err' => '',
                'invoiceno_err' => '',
                'save_err'=> '',
            ];

            for($i = 0; $i < count($data['booksid']); $i++){
                array_push($data['table'],[
                    'bid' => $data['booksid'][$i],
                    'name' => $data['booksname'][$i],
                    'qty' => $data['qtys'][$i],
                    'rate' => $data['rates'][$i],
                    'gross' => $data['gross'][$i],
                ]);
            }

            foreach($data['table'] as $entry){
                $data['total'] = floatval($data['total']) + floatval($entry['gross']);
            }

            if(empty($data['supplier'])){
                $data['supplier_err'] = 'Select supplier';
            }

            if(empty($data['invoicedate'])){
                $data['invoicedate_err'] = 'Select invoice date';
            }elseif(!empty($data['invoicedate']) && !validatedate($data['invoicedate'])){
                $data['invoicedate_err'] = 'Invalid invoice date';
            }

            if(!empty($data['duedate']) && $data['duedate'] < $data['invoicedate']){
                $data['duedate_err'] = 'Invalid due date';
            }

            if(empty($data['vattype'])){
                $data['vattype_err'] = 'Select type of VAT';
            }

            if((int)$data['vattype'] > 1 && empty($data['vat'])){
                $data['vat_err'] = 'Select VAT rate';
            }

            if(empty($data['invoiceno'])){
                $data['invoiceno_err'] = 'Enter invoice no';
            }

            if((int)$data['vattype'] >1 && !empty($data['vat'])){
                $data['vatrate'] = floatval($this->invoicemodel->GetVatRate($data['vat']));
            }

            if(!empty($data['invoicedate_err']) || !empty($data['duedate_err']) || !empty($data['supplier_err'])
               || !empty($data['vattype_errr']) || !empty($data['vat_err']) || !empty($data['invoiceno_err'])){
                $this->view('invoices/add',$data);
                exit();
            }

            if(!$this->invoicemodel->CreateUpdate($data)){
                flash('invoice_msg',null,'Unable to save this invoice. Retry or contact admin',flashclass('alert','danger'));
                redirect('invoices');
                exit();
            }

            flash('invoice_flash_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('invoices');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        $header = $this->invoicemodel->GetInvoiceHeader($id);
        $details = $this->invoicemodel->GetInvoiceDetails($id);
        $data = [
            'title' => 'Edit Invoice',
            'suppliers' => $this->invoicemodel->GetSuppliers(),
            'vattypes' => $this->invoicemodel->GetVatTypes(),
            'vats' => $this->invoicemodel->GetVats(),
            'books' => $this->invoicemodel->GetBooks(),
            'touched' => false,
            'isedit' => true,
            'id' => $header->ID,
            'invoicedate' => date('Y-m-d',strtotime($header->InvoiceDate)),
            'supplier' => $header->SupplierId,
            'duedate' => date('Y-m-d',strtotime($header->DueDate)),
            'vattype' => $header->VatType,
            'vat' => $header->VatId,
            'invoiceno' => $header->InvoiceNo,
            'description' => isset($header->Description) ? strtoupper($header->Description) : '',
            'invoicedate_err' => '',
            'duedate_err' => '',
            'supplier_err' => '',
            'vattype_err' => '',
            'vat_err' => '',
            'invoiceno_err' => '',
            'table' => [],
            'save_err'=> '',
        ];
        foreach($details as $detail){
            array_push($data['table'],[
                'bid' => $detail->ProductId,
                'name' => $detail->BookTitle,
                'qty' => $detail->Qty,
                'rate' => $detail->Rate,
                'gross' => $detail->Gross,
            ]);
        }
        if((int)$header->CenterId !== (int)$_SESSION['centerid']){
            redirect('auth/unauthorized');
            exit();
        }
        $this->view('invoices/add', $data);
        exit();
    }

    public function pay($id)
    {
        $invoicedetail = $this->invoicemodel->GetInvoiceDetail($id);
        $data = [
            'title' => 'Pay Invoice',
            'touched' => false,
            'isedit' => false,
            'id' => $invoicedetail->ID,
            'supplierid' => $invoicedetail->SupplierId ,
            'supplier' => $invoicedetail->SupplierName,
            'invoiceno' => $invoicedetail->InvoiceNo,
            'invoiceamount' => floatval($invoicedetail->InvoiceAmount),
            'balance' => floatval($invoicedetail->Balance),
            'amountpaid' => floatval($invoicedetail->InvoiceAmount) - floatval($invoicedetail->Balance),
            'currentamount' => '',
            'currentbalance' => '',
            'paymethod' => 2,
            'reference' => '',
            'paydate' => '',
            'narration' => '',
            'currentamount_err' => '',
            'currentbalance_err' => '',
            'paymethod_err' => '',
            'paydate_err' => '',
            'reference_err' => '',
        ];
        if((int)$invoicedetail->CenterId !== (int)$_SESSION['centerid']){
            redirect('auth/unauthorized');
            exit();
        }
        $this->view('invoices/pay', $data);
        exit();
    }

    
    public function payinvoice()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $id = trim($_POST['id']);
            $invoicedetail = $this->invoicemodel->GetInvoiceDetail($id);
            $data = [
                'title' => 'Pay Invoice',
                'touched' => true,
                'isedit' => false,
                'id' => $invoicedetail->ID,
                'supplierid' => $invoicedetail->SupplierId ,
                'supplier' => $invoicedetail->SupplierName,
                'invoiceno' => $invoicedetail->InvoiceNo,
                'invoiceamount' => floatval($invoicedetail->InvoiceAmount),
                'balance' => floatval($invoicedetail->Balance),
                'amountpaid' => floatval($invoicedetail->InvoiceAmount) - floatval($invoicedetail->Balance),
                'currentamount' => !empty(trim($_POST['currentamount'])) ? floatval(trim($_POST['currentamount'])) : '',
                'currentbalance' => '',
                'paymethod' => !empty($_POST['paymethod']) ? trim($_POST['paymethod']) : '',
                'reference' => !empty(trim($_POST['reference'])) ? trim($_POST['reference']) : '',
                'paydate' => !empty(trim($_POST['paydate'])) ? date('Y-m-d',strtotime(trim($_POST['paydate']))) : '',
                'narration' => !empty(trim($_POST['narration'])) ? trim($_POST['narration']) : '',
                'currentamount_err' => '',
                'currentbalance_err' => '',
                'paymethod_err' => '',
                'paydate_err' => '',
                'reference_err' => '',
            ];

            if(empty($data['currentamount'])){
                $data['currentamount_err'] = 'Enter current payment';
            }
            if(empty($data['paymethod'])){
                $data['paymethod_err'] = 'Select pay method';
            }
            if(empty($data['reference'])){
                $data['reference_err'] = 'Enter payment reference';
            }
            if(empty($data['paydate'])){
                $data['paydate_err'] = 'Select payment date';
            }
            if(!empty($data['reference']) && !$this->invoicemodel->CheckPaymethodAvailability($data['reference'])){
                $data['reference_err'] = 'Reference already exists';
            }
            if(!empty($data['paydate']) && !validatedate($data['paydate'])){
                $data['paydate_err'] = 'Invalid date selected';
            }
            if(!empty($data['currentamount'])){
                $data['currentbalance'] = floatval($data['invoiceamount']) - (floatval($data['amountpaid']) + floatval($data['currentamount']));
            }
            if(!empty($data['paydate_err']) || !empty($data['reference_err']) || !empty($data['paymethod_err']) 
               || !empty($data['currentamount_err'])){
                $this->view('invoices/pay',$data);
                exit();
            }

            if(!$this->invoicemodel->PayInvoice($data)){
                flash('invoice_msg',null,'Unable to save invoice payment!Retry or contact admin!',flashclass('alert','danger'));
                redirect('invoices');
                exit();
            }

            flash('invoice_flash_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('invoices');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function checkglcode()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET, FILTER_UNSAFE_RAW);
            $bookid = (int)trim($_GET['bookid']);
            echo json_encode($this->invoicemodel->CheckGlCode($bookid));
            exit();
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function print($id)
    {
        $header = $this->invoicemodel->GetInvoiceHeader($id);
        $details = $this->invoicemodel->GetInvoiceDetails($id);
        $supplier = $this->invoicemodel->GetSupplier($header->SupplierId);
        $data = [
            'title' => 'Print Invoice',
            'header' => $header,
            'details' => $details,
            'supplier' => $supplier,
        ];

        if((int)$header->CenterId !== (int)$_SESSION['centerid']){
            redirect('auth/unauthorized');
            exit();
        }
        $this->view('invoices/print', $data);
        exit();
    }
}