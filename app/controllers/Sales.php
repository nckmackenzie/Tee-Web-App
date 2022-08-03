<?php
class Sales extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth/login');
            exit();
        }
        $this->salemodel = $this->model('Sale');
    }

    public function index()
    {
        $sales = $this->salemodel->GetSales();
        $data = [
            'title' => 'Sales',
            'has_datatable' => true,
            'sales' => $sales
        ];
        $this->view('sales/index',$data);
        exit();
    }

    public function add()
    {
        $salesid = $this->salemodel->GetSaleId();
        $books = $this->salemodel->GetBooks();
        $data = [
            'title' => 'Add sale',
            'saleid' => $salesid,
            'books' =>  $books,
            'touched' => false,
            'isedit' => false,
            'id' => '',
            'sdate' => date('Y-m-d'),
            'type' => '',
            'studentorgroup' => '',
            'paymethod' => '',
            'reference' => '',
            'subtotal' => '',
            'discount' => '',
            'net' => '',
            'paid' => '',
            'balance' => '',
            'table' => [],
            'sdate_err' => '',
            'type_err' => '',
            'studentgroup_err' => '',
            'paid_err' => '',
            'paymethod_err' => '',
            'reference_err' => '',
        ];
        $this->view('sales/add',$data);
        exit();
    }

    public function getstockandrate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $bookid = intval(trim($_GET['bookid']));
            $date = date('Y-m-d',strtotime(trim($_GET['date'])));
            $rate_stock = $this->salemodel->GetStockAndRate($date,$bookid);
            $result = ['rate' => $rate_stock->Rate,'stock' => $rate_stock->Stock];
            echo json_encode($result);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    function fetchstudentorgroup($type){
        $studentsorgroups = $this->salemodel->GetStudentsOrGroups($type);
        return $studentsorgroups;
    }

    public function getstudentorgroup()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $saletype = trim($_GET['type']);
            //validate
            if(empty($saletype) || !isset($saletype)){
                flash('sale_msg',null,'Invalid request',flashclass('alert', 'danger'));
                redirect('sales');
                exit();
            }

            $studentsorgroups = $this->fetchstudentorgroup($saletype);
            $output = '<option value="">Select '.ucwords($saletype).'</option>';
            foreach($studentsorgroups as $studentorgroup){
                $output .= '<option value="'.$studentorgroup->ID.'">'.$studentorgroup->CriteriaName.'</option>';
            }

            echo json_encode($output);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST =  filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $books = $this->salemodel->GetBooks();
            $data = [
                'title' => converttobool(trim($_POST['isedit'])) ? 'Edit sale' : 'Add sale',
                'saleid' => trim($_POST['saleid']),
                'books' =>  $books,
                'touched' => true,
                'isedit' => converttobool(trim($_POST['isedit'])),
                'id' => trim($_POST['id']),
                'sdate' => !empty(trim($_POST['sdate'])) ? date('Y-m-d',strtotime(trim($_POST['sdate']))) : '',
                'type' => !empty($_POST['saletype']) ? trim($_POST['saletype']) : '',
                'studentsorgroups' => '',
                'studentorgroup' => !empty($_POST['studentorgroup']) ? trim($_POST['studentorgroup']) : '',
                'paymethod' => !empty($_POST['paymethod']) ? trim($_POST['paymethod']) : '',
                'reference' => !empty($_POST['reference']) ? trim($_POST['reference']) : '',
                'subtotal' => !empty(trim($_POST['subtotal'])) ? trim($_POST['subtotal']) : '',
                'discount' => !empty(trim($_POST['discount'])) ? trim($_POST['discount']) : 0,
                'net' => !empty(trim($_POST['net'])) ? trim($_POST['net']) : 0,
                'paid' => !empty(trim($_POST['paid'])) ? trim($_POST['paid']) : '',
                'balance' => !empty(trim($_POST['balance'])) ? trim($_POST['balance']) : 0,
                'table' => [],
                'booksid' => $_POST['booksid'],
                'booksname' => $_POST['booksname'],
                'rates' => $_POST['rates'],
                'qtys' => $_POST['qtys'],
                'values' => $_POST['values'],
                'sdate_err' => '',
                'type_err' => '',
                'studentgroup_err' => '',
                'paid_err' => '',
                'paymethod_err' => '',
                'reference_err' => '',
            ];

            if(count($data['booksid']) > 0){
                for ($i=0; $i < count($data['booksid']); $i++){ 
                    array_push($data['table'],[
                        'bid' => $data['booksid'][$i],
                        'bookname' => $data['booksname'][$i],
                        'rate' => $data['rates'][$i],
                        'qty' => $data['qtys'][$i],
                        'values' => $data['values'][$i],
                    ]);
                }
            }

            if(!empty($data['type'])){
                $data['studentsorgroups'] = $this->fetchstudentorgroup($data['type']);
            }
            //validate
            if(empty($data['sdate'])){
                $data['sdate_err'] = 'Select sale date';
            }

            if(empty($data['type'])){
                $data['type_err'] = 'Select sale type';
            }
            
            if(empty($data['studentorgroup'])){
                $data['studentgroup_err'] = 'Select student or group';
            }

            if(empty($data['paymethod'])){
                $data['paymethod_err'] = 'Select payment method';
            }

            if(empty($data['reference'])){
                $data['reference_err'] = 'Enter payment reference';
            }

            if(!empty($data['reference']) && !$this->salemodel->CheckRefExists($data['reference'])){
                $data['reference_err'] = 'Payment reference already exists';
            }

            if(empty($data['paid'])){
                $data['paid_err'] = 'Enter amount paid';
            }

            if(!empty($data['paid']) && !empty($data['net']) && floatval($data['paid']) > floatval($data['net'])){
                $data['paid_err'] = 'Paid more than required';
            }

            if(!empty($data['sdate_err']) || !empty($data['type_err']) || !empty($data['paid_err']) 
               || !empty($data['studentgroup_err']) || !empty($data['paymethod_err']) || !empty($data['reference_err'])){
                $this->view('sales/add',$data);
                exit();
            }

            if(!$this->salemodel->CreateUpdate($data)){
                flash('sale_msg',null,'Unable to save! Retry or contact admin',flashclass('alert','danger'));
                redirect('sales');
                exit();
            }

            flash('sale_flash_msg',null,'Saved successfully!',flashclass('toast','success'));
            redirect('sales');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        $saleheader = $this->salemodel->GetSaleHeader($id);
        checkcenter($saleheader->CenterId);
        $saledetails = $this->salemodel->GetSaleDetails($id);
        $studentsorgroups = $this->fetchstudentorgroup($saleheader->SaleType);
        $books = $this->salemodel->GetBooks();
        $data = [
            'title' => 'Edit sale',
            'studentsorgroups' => $studentsorgroups,
            'saleid' => $saleheader->SalesID,
            'books' =>  $books,
            'touched' => false,
            'isedit' => true,
            'id' => $saleheader->ID,
            'sdate' => $saleheader->SalesDate,
            'type' => $saleheader->SaleType,
            'studentorgroup' => $saleheader->SaleType === 'student' ? $saleheader->StudentId : $saleheader->GroupId,
            'paymethod' => $saleheader->PaymentMethodId,
            'reference' => strtoupper($saleheader->Reference),
            'subtotal' => $saleheader->SubTotal,
            'discount' => $saleheader->Discount,
            'net' => $saleheader->NetAmount,
            'paid' => $saleheader->AmountPaid,
            'balance' => $saleheader->Balance,
            'table' => [],
            'sdate_err' => '',
            'type_err' => '',
            'studentgroup_err' => '',
            'paid_err' => '',
            'paymethod_err' => '',
            'reference_err' => '',
        ];

        foreach($saledetails as $detail){
            array_push($data['table'],[
                'bid' => $detail->BookId,
                'bookname' => $detail->BookName,
                'rate' => $detail->Rate,
                'qty' => $detail->Qty,
                'values' => $detail->SellingValue,
            ]);
        }
        $this->view('sales/add',$data);
        exit();
    }

    function paymentstatus($net,$paid){
        $paymentstatus = [];
        if(floatval($net) === floatval($paid)){
            array_push($paymentstatus,'success');
            array_push($paymentstatus,'paid');
        }elseif(floatval($paid > 0) && (floatval($net) - floatval($paid)) > 0){
            array_push($paymentstatus,'warning');
            array_push($paymentstatus,'partially paid');
        }elseif(floatval($net) - floatval($paid) === floatval($net)){
            array_push($paymentstatus,'danger');
            array_push($paymentstatus,'unpaid');
        }
        return $paymentstatus;
    }

    public function print($id)
    {
        $saleheader = $this->salemodel->GetSaleHeader($id);
        $saledetails = $this->salemodel->GetSaleDetails($id);
        $centerdetails = $this->salemodel->GetCenterDetails();
        checkcenter($saleheader->CenterId);
        $data = [
            'title' => 'Sales Receipt',
            'center' => $centerdetails->CenterName,
            'contact' => $centerdetails->Contact,
            'email' => $centerdetails->Email,
            'sdate' => date('d-m-Y',strtotime($saleheader->SalesDate)),
            'paystatus' => $this->paymentstatus($saleheader->NetAmount,$saleheader->AmountPaid)[1],
            'payclass' => $this->paymentstatus($saleheader->NetAmount,$saleheader->AmountPaid)[0],
            'saleid' => $saleheader->SalesID,
            'reference' => strtoupper($saleheader->Reference),
            'details' => $saledetails,
            'subtotal' => number_format($saleheader->SubTotal,2),
            'netamount' => number_format($saleheader->NetAmount,2),
            'paid' => number_format($saleheader->AmountPaid,2),
            'balance' => number_format($saleheader->Balance,2),
            'discount' => number_format(floatval($saleheader->SubTotal) - floatval($saleheader->NetAmount),2),
        ];
        $this->view('sales/print',$data);
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);

            if(empty($id)){
                flash('sale_msg',null,'Unable to get selected sale',flashclass('alert','danger'));
                redirect('sales');
                exit();
            }

            if(!$this->salemodel->Delete($id)){
                flash('sale_msg',null,'Unable to delete selected sale',flashclass('alert','danger'));
                redirect('sales');
                exit();
            }

            flash('sale_flash_msg',null,'Deleted successfully',flashclass('toast','success'));
            redirect('sales');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}