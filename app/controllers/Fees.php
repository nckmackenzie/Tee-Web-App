<?php
class Fees extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->authmodel = $this->model('Auths');
        $this->feemodel = $this->model('Fee');
    }
    
    public function index()
    {
        checkrights($this->authmodel,'fee payments');
        $data = [
            'title' => 'Fees',
            'has_datatable' => true,
            'fees' => $this->feemodel->GetFees(),
        ];
        $this->view('fees/index',$data);
    }

    public function add()
    {
        checkrights($this->authmodel,'fee payments');
        $data = [
            'title' => 'Add fee payment',
            'students' => $this->feemodel->GetStudents(),
            'accounts' => $this->feemodel->GetAccounts(),
            'semisters' => $this->feemodel->GetSemisters(),
            'touched' => false,
            'isedit' => false,
            'id' => '',
            'pdate' => '',
            'receiptno' => $this->feemodel->GetReceiptNo(),
            'student' => '',
            'semister' => '',
            'balancebf' => '',
            'semisterfees' => '',
            'totalpaid' => '',
            'balance' => '',
            'amount' => '',
            'account' => '',
            'paymethod' => '',
            'reference' => '',
            'narration' => '',
        ];
        $this->view('fees/add',$data);
        exit();
    }

    public function getfeepaymentdetails()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $data = [
                'semister' => isset($_GET['semister']) && !empty(trim($_GET['semister'])) ? (int)trim($_GET['semister']) : null,
                'student' => isset($_GET['student']) && !empty(trim($_GET['student'])) ? (int)trim($_GET['student']) : null,
            ];

            if(is_null($data['semister']) || is_null($data['student'])) exit;
            $feepaymentdetails = $this->feemodel->GetFeePaymentDetails($data['student'],$data['semister']);
            $results = [
                'balanceBf' => $feepaymentdetails[0],
                'semisterFee' => $feepaymentdetails[1],
                'semisterPaid' => $feepaymentdetails[2],
            ];

            echo json_encode($results);

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //extract json data from POST request
            $fields = json_decode(file_get_contents('php://input'));
            
            $data = [
                'title' => !converttobool(trim($fields->isedit)) ? 'Add fee payment' : 'Edit fee payment',
                'students' => $this->feemodel->GetStudents(),
                'accounts' => $this->feemodel->GetAccounts(),
                'isedit' => converttobool(trim($fields->isedit)),
                'id' => !empty(trim($fields->id)) ? trim($fields->id) : null,
                'pdate' => !empty(trim($fields->pdate)) ? date('Y-m-d', strtotime(trim($fields->pdate))) : null,
                'receiptno' => $this->feemodel->GetReceiptNo(),
                'student' => converttobool(trim($fields->isedit)) ? (int)trim($fields->studentid) : (!empty($fields->student) ? (int)trim($fields->student) : null),
                'semister' => converttobool(trim($fields->isedit)) ? (int)trim($fields->semisterid) : (!empty($fields->semister) ? (int)trim($fields->semister) : null),
                'balancebf' => '',
                'semisterfees' => '',
                'totalpaid' => '',
                'balance' => '',
                'amount' => !empty(trim($fields->amount)) ? floatval(numberFormat(trim($fields->amount))) : null,
                'account' => !empty($fields->account) ? (int)trim($fields->account) : null,
                'paymethod' => !empty($fields->paymethod) ? (int)trim($fields->paymethod) : null,
                'reference' => !empty(trim($fields->reference)) ? strtolower(trim($fields->reference)) : null,
                'narration' => !empty(trim($fields->narration)) ? strtolower(trim($fields->narration)) : null,
            ];
            //validation
            if(is_null($data['pdate']) || is_null($data['student']) || is_null($data['semister']) 
               || is_null($data['amount']) || is_null($data['account']) || is_null($data['paymethod']) || is_null($data['reference'])){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields.']);
                exit;
            }
            $feepaymentdetails = $this->feemodel->GetFeePaymentDetails($data['student'],$data['semister']);
            $data['balancebf'] = $feepaymentdetails[0];
            $data['semisterfees'] = $feepaymentdetails[1];
            if(!$data['isedit']){
                $data['totalpaid'] = $feepaymentdetails[2];
                $data['balance'] = (floatval($data['balancebf']) + floatval($data['semisterfees'])) - (floatval($data['totalpaid']) + floatval($data['amount']));
            }
            

            if(!is_null($data['reference']) && !$this->feemodel->CheckRefExists($data['reference'],$data['id'])){
                http_response_code(400);
                echo json_encode(['message' => 'Reference no already exists.']);
                exit;
            }

            if(!$this->feemodel->CreateUpdate($data)){
                http_response_code(500);
                echo json_encode(['message' => 'Unable to save transaction. Retry or contact admin']);
                exit;
            }

            echo json_encode(['success' => true]);
            exit;
        
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function newid()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            echo json_encode($this->feemodel->GetReceiptNo());
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        checkrights($this->authmodel,'fee payments');
        $payment = $this->feemodel->GetPayment($id);
        $data = [
            'title' => 'Edit fee payment',
            'students' => $this->feemodel->GetStudents(),
            'accounts' => $this->feemodel->GetAccounts(),
            'semisters' => $this->feemodel->GetSemisters(),
            'isedit' => true,
            'id' => $payment->ID,
            'pdate' => $payment->PaymentDate,
            'receiptno' => $payment->ReceiptNo,
            'student' => $payment->StudentId,
            'semister' => $payment->SemisterId,
            'balancebf' => '',
            'semisterfees' => '',
            'totalpaid' => '',
            'balance' => '',
            'amount' => $payment->AmountPaid,
            'account' => $payment->GlAccountId,
            'paymethod' => $payment->PaymentMethodId,
            'reference' => strtoupper($payment->Reference),
            'narration' => isset($payment->Narration) ? strtoupper($payment->Narration) : '',
        ];
        //get balances
        $feepaymentdetails = $this->feemodel->GetFeePaymentDetails($payment->StudentId,$payment->SemisterId);
        $data['balancebf'] = $feepaymentdetails[0];
        $data['semisterfees'] = $feepaymentdetails[1];
        $data['totalpaid'] = $feepaymentdetails[2] - $payment->AmountPaid;
        $data['balance'] = (floatval($data['balancebf']) + floatval($data['semisterfees'])) - floatval($data['totalpaid']);
        $this->view('fees/add',$data);
        exit();
    }

    public function delete()
    {
        delete('fee',$this->feemodel);
        exit();
    }

    public function structure()
    {
        checkrights($this->authmodel,'fee structure');
        $data = [
            'title' => 'Fee structures',
            'structures' => $this->feemodel->GetFeeStructures(),
            'has_datatable' => true,
        ];
        $this->view('fees/structure',$data);
        exit;
    }

    public function addstructure()
    {
        checkrights($this->authmodel,'fee structure');
        $data = [
            'title' => 'Add Fee Structure',
            'semisters' => $this->feemodel->GetSemisters(),
            'id' => '',
            'isedit' => false,
            'amount' => '',
            'semister' => ''
        ];
        $this->view('fees/addstructure',$data);
        exit;
    }

    public function checksemister()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $semister = isset($_GET['semister']) && !empty(trim($_GET['semister'])) ? trim($_GET['semister']) : '';
            $id = isset($_GET['id']) && !empty(trim($_GET['id'])) ? (int)trim($_GET['id']) : '';

            if(empty($semister)){
                http_response_code(400);
                echo json_encode(['message' => 'Select semister']);
                exit;
            }
            //get
            echo json_encode($this->feemodel->CheckSemisterDefined($semister,$id));
   
        }else{
            redirect('auth/forbidden');
            exit;
        }
    }

    public function createupdatestructure()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $fields = json_decode(file_get_contents('php://input')); //get json data
            $data = [
                'title' => converttobool($fields->isedit) ? 'Edit Fee Structure' : 'Add Fee Structure',
                'semisters' => $this->feemodel->GetSemisters(),
                'id' => $fields->id,
                'isedit' => converttobool($fields->isedit),
                'amount' => !empty(trim($fields->amount)) ? floatval(trim($fields->amount)) : NULL,
                'semister' => !empty(trim($fields->semister)) ? trim($fields->semister) : NULL,
            ];

            if(is_null($data['amount']) || is_null($data['semister'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Fill all required fields']);
                exit;
            }
            if((int)$this->feemodel->CheckSemisterDefined($data['semister'],$data['id']) > 0) {
                http_response_code(400);
                echo json_encode(['message' => 'Semister fee structure already defined']);
                exit;
            }

            if(!$this->feemodel->CreateUpdateStructure($data)) {
                http_response_code(500);
                echo json_encode(['message' => 'Unable to save fee structure. Retry or contact admin']);
                exit;
            }

            echo json_encode(['success' => true]);
            exit;
          
        }else{
            redirect('auth/forbidden');
            exit;
        }
    }

    public function editstructure($id)
    {
        checkrights($this->authmodel,'fee structure');
        $feestructure = $this->feemodel->GetFeeStructure($id);
        $data = [
            'title' => 'Edit Fee Structure',
            'semisters' => $this->feemodel->GetSemisters(),
            'id' => $feestructure->ID,
            'isedit' => true,
            'amount' => $feestructure->TotalAmount,
            'semister' => $feestructure->SemisterId
        ];
        $this->view('fees/addstructure',$data);
        exit;
    }

    public function deletestructure()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
    
            if(empty($id)){
                flash('structure_msg',null,'Unable to get selected fee structure',flashclass('alert','danger'));
                redirect('fees/structure');
                exit();
            }
                  
            if(!$this->feemodel->DeleteStructure($id)){
                flash('structure_msg',null,'Unable to delete selected fee structure',flashclass('alert','danger'));
                redirect('fees/structure');
                exit();
            }
    
            flash('structure_flash_msg',null,'Deleted successfully',flashclass('toast','success'));
            redirect('fees/structure');
            exit();
    
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}