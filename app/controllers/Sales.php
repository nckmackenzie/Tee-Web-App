<?php
class Sales extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth/login');
            exit();
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'sales');
        $this->salemodel = $this->model('Sale');
        $this->exammodel = $this->model('Exam');
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
            'sdate' => '',
            'pdate' => '',
            'type' => '',
            'studentorgroup' => '',
            'paymethod' => '',
            'reference' => '',
            'subtotal' => '',
            'discount' => '',
            'net' => '',
            'paid' => '',
            'deliveryfee' => 0,
            'balance' => '',
            'table' => [],
            'students' => [],
            'sdate_err' => '',
            'pdate_err' => '',
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
            $fields = json_decode(file_get_contents('php://input'));
            $header = $fields->header;
            $data = [
                'id' => !empty($header->id) ? $header->id : '',
                'isedit' => converttobool($header->isEdit),
                'sdate' => !empty($header->sdate) ? date('Y-m-d',strtotime($header->sdate)) : '',
                'pdate' => !empty($header->pdate) ? date('Y-m-d',strtotime($header->pdate)) : '',
                'saletype' => !empty($header->saleType) ? $header->saleType : '',
                'buyer' => !empty($header->buyer) ? $header->buyer : '',
                'paymethod' => !empty($header->paymethod) ? (int)$header->paymethod : '',
                'reference' => !empty($header->reference) ? $header->reference : '',
                'subtotal' => !empty($header->subtotal) ? floatval($header->subtotal) : '',
                'discount' => !empty($header->discount) ? $header->discount : 0,
                'deliveryfee' => !empty($header->deliveryfee) ? floatval($header->deliveryfee) : 0,
                'net' => !empty($header->net) ? floatval($header->net) : '',
                'paid' => !empty($header->paid) ? floatval($header->paid) : '',
                'balance' => !empty($header->balance) ? floatval($header->balance) : '',
                'books' => $fields->table,
                'students' => $header->saleType === 'group' ? $fields->students : null,
                'reason' => converttobool(trim($header->isEdit)) ? (!empty(trim($header->reason)) ? $header->reason : '') : '',
            ];

            if(empty($data['sdate']) || empty($data['pdate']) || empty($data['saletype']) 
              || empty($data['buyer']) || empty($data['paymethod']) || empty($data['reference'])
              || empty($data['paid'])){
                http_response_code(400);
                echo json_encode(['message' => 'Fill all required fields']);
                exit;
            }

            if($data['sdate'] < $data['pdate']){
                http_response_code(400);
                echo json_encode(['message' => 'Sale date cannot be earlier than payment date']);
                exit;
            }

            if($data['saletype'] === 'group' && !is_countable($data['students'])){
                http_response_code(400);
                echo json_encode(['message' => 'No students selected']);
                exit;
            }

            if($data['paid'] > $data['net']){
                http_response_code(400);
                echo json_encode(['message' => 'Payment more than sale value']);
                exit;
            }

            if($data['isedit'] && empty($data['reason'])){
                http_response_code(400);
                echo json_encode(['message' => 'Enter reason for editing sale']);
                exit;
            }

            if(!$this->salemodel->CheckRefExists($data['reference'],$data['id'])){
                http_response_code(400);
                echo json_encode(['message' => 'Payment reference already exists']);
                exit;
            }

            if(!$this->salemodel->CreateUpdate($data)){
                http_response_code(400);
                echo json_encode(['message' => 'Unable to save! Retry or contact admin']);
                exit;
            }

            http_response_code(200);
            echo json_encode(['message' => 'Saved successfully!']);
            exit;
       
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
            'pdate' => $saleheader->PayDate,
            'type' => $saleheader->SaleType,
            'studentorgroup' => $saleheader->SaleType === 'student' ? $saleheader->StudentId : $saleheader->GroupId,
            'paymethod' => $saleheader->PaymentMethodId,
            'reference' => strtoupper($saleheader->Reference),
            'subtotal' => $saleheader->SubTotal,
            'discount' => $saleheader->Discount,
            'deliveryfee' => $saleheader->DeliveryFee,
            'net' => $saleheader->NetAmount,
            'paid' => $saleheader->AmountPaid,
            'balance' => $saleheader->Balance,
            'reason' => '',
            'table' => [],
            'students' => [],
            'sdate_err' => '',
            'pdate_err' => '',
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
                'softcopy' => $detail->IsSoftCopy
            ]);
        }

        if($saleheader->SaleType === 'group')
        {
            $students = $this->salemodel->GetStudentSales($saleheader->ID);
            foreach($students as $student){
                array_push($data['students'],[
                    'sid' => $student->StudentId,
                    'studentname' => $student->StudentName,
                    'paid' => $student->Paid,
                    'contact' => $student->Contact,
                ]);
            }
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

    public function getgroupmembers()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $id = isset($_GET['groupid']) ? htmlentities(trim($_GET['groupid'])) : null;

            if(is_null($id)) :
                http_response_code(404);
                echo json_encode(['message' => 'Select group']);
                exit;
            endif;
            $students = $this->salemodel->GetStudentsByGroup($id);
            $results = [];
            foreach($students as $student) :
                array_push($results,[
                    'id' => $student->ID,
                    'studentName' => $student->StudentName,
                    'contact' => $student->Contact
                ]);
            endforeach;

            echo json_encode($results);

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function getnewid()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            echo json_encode($this->salemodel->GetSaleId());
        }
    }

    public function saleswithbalances()
    {
        $data = [
            'title' => 'Sales With Balances',
            'sales' => $this->salemodel->GetSalesWithBalances(),
            'has_datatable' => true
        ];
        $this->view('sales/salewithbalances',$data);
        exit;
    }

    public function balancepayment($id)
    {
        $details = $this->salemodel->GetBalanceDetails($id);
       
        if(floatval($details[0] === 0 || empty($details[0])))
        {
            redirect('sales/saleswithbalances');
            exit;    
        }
        $data = [
            'title' => 'Balance Receipt',
            'saleid' => $id,
            'isedit' => false,
            'paymethod' => '',
            'paydate' => date('Y-m-d'),
            'balance' => $details[0],
            'soldto' => $details[1]
            // 'sales' => $this->salemodel->GetSalesWithBalances(),
        ];
        $this->view('sales/balancereceipt',$data);
        exit;
    }

    public function receivepayment()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $formdetails = json_decode(file_get_contents('php://input'));
            $data = [
                'saleid' => isset($formdetails->id) && !empty(trim($formdetails->id)) ? (int)trim($formdetails->id): null,
                'isedit' => false,
                'paymethod' => isset($formdetails->paymethod) && !empty($formdetails->paymethod) ? (int)trim($formdetails->paymethod) : null,
                'paydate' => isset($formdetails->paydate) && !empty(trim($formdetails->paydate)) ? date('Y-m-d',strtotime(trim($formdetails->paydate))) : null,
                'balance' => isset($formdetails->balance) && !empty(trim($formdetails->balance)) ? floatval(trim($formdetails->balance)) : null,
                'payment' => isset($formdetails->payment) && !empty(trim($formdetails->payment)) ? floatval(trim($formdetails->payment)) : null,
                'reference' => isset($formdetails->reference) && !empty(trim($formdetails->reference)) ? strtolower(trim($formdetails->reference)) : null,
            ];

            //validate
            if(is_null($data['paydate']) || is_null($data['payment']) || is_null($data['paymethod']) 
               || is_null($data['reference']) || is_null($data['saleid']) || is_null($data['balance'])){
                
                http_response_code(400);
                echo json_encode(['message' => 'Fill all required fields']);
                exit;
            }

            if($data['paydate'] > date('Y-m-d')){
                http_response_code(400);
                echo json_encode(['message' => 'Invalid date selected']);
                exit;
            }

            if($data['payment'] > $data['balance']){
                http_response_code(400);
                echo json_encode(['message' => 'Payment more than balance']);
                exit;
            }

            // $balance = $data['balance'] - $data['payment'];
            // echo json_encode(['balance' => $balance,'saleid' => $data['saleid']]);

            if(!$this->salemodel->ReceivePayment($data)){
                http_response_code(500);
                echo json_encode(['message' => 'Unable to save. Retry or contact admin']);
                exit;
            }
            //saved successfully
            echo json_encode(['success' => true]);
            exit;
        }
        else
        {
            redirect('auth/forbidden');
            exit();
        }
    }
}