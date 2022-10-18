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
            'touched' => false,
            'isedit' => false,
            'id' => '',
            'pdate' => '',
            'receiptno' => $this->feemodel->GetReceiptNo(),
            'student' => '',
            'amount' => '',
            'account' => '',
            'paymethod' => '',
            'reference' => '',
            'narration' => '',
            'pdate_err' => '',
            'student_err' => '',
            'amount_err' => '',
            'account_err' => '',
            'paymethod_err' => '',
            'reference_err' => '',
        ];
        $this->view('fees/add',$data);
        exit();
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => !converttobool(trim($_POST['isedit'])) ? 'Add fee payment' : 'Edit fee payment',
                'students' => $this->feemodel->GetStudents(),
                'accounts' => $this->feemodel->GetAccounts(),
                'touched' => true,
                'isedit' => converttobool(trim($_POST['isedit'])),
                'id' => trim($_POST['id']),
                'pdate' => !empty(trim($_POST['pdate'])) ? date('Y-m-d', strtotime(trim($_POST['pdate']))) : '',
                'receiptno' => !empty(trim($_POST['receiptno'])) ? trim($_POST['receiptno']) : '',
                'student' => !empty($_POST['student']) ? trim($_POST['student']) : '',
                'amount' => !empty(trim($_POST['amount'])) ? floatval(trim($_POST['amount'])) : '',
                'account' => !empty($_POST['account']) ? trim($_POST['account']) : '',
                'paymethod' => !empty($_POST['paymethod']) ? trim($_POST['paymethod']) : '',
                'reference' => !empty(trim($_POST['reference'])) ? trim($_POST['reference']) : '',
                'narration' => !empty(trim($_POST['narration'])) ? trim($_POST['narration']) : '',
                'pdate_err' => '',
                'student_err' => '',
                'amount_err' => '',
                'account_err' => '',
                'paymethod_err' => '',
                'reference_err' => '',
            ];

            if(empty($data['pdate'])){
                $data['pdate_err'] = 'Select date';
            }
            if(!empty($data['pdate']) && !validatedate($data['pdate'])){
                $data['pdate_err'] = 'Invalid date selected';
            }
            if(empty($data['student'])){
                $data['student_err'] = 'Select student';
            }
            if(empty($data['amount'])){
                $data['amount_err'] = 'Enter amount';
            }
            if(empty($data['account'])){
                $data['account_err'] = 'Select G/L account';
            }
            if(empty($data['paymethod'])){
                $data['paymethod_err'] = 'Select payment method';
            }
            if(empty($data['reference'])){
                $data['reference_err'] = 'Enter payment reference';
            }
            if(!empty($data['reference']) && !$this->feemodel->CheckRefExists($data['reference'],$data['id'])){
                $data['reference_err'] = 'Enter payment reference';
            }

            if(!empty($data['pdate_err']) || !empty($data['amount_err']) || !empty($data['account_err']) 
               || !empty($data['student_err']) || !empty($data['reference_err']) || !empty($data['paymethod_err'])){
                $this->view('fees/add',$data);
                exit();
            }

            if(!$this->feemodel->CreateUpdate($data)){
                flash('fee_msg',null,'Unable to save transaction. Retry or contact admin',flashclass('alert','danger'));
                redirect('fees');
                exit();
            }

            flash('fee_flash_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('fees');
            exit();
        
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
            'touched' => false,
            'isedit' => true,
            'id' => $payment->ID,
            'pdate' => $payment->PaymentDate,
            'receiptno' => $payment->ReceiptNo,
            'student' => $payment->StudentId,
            'amount' => $payment->AmountPaid,
            'account' => $payment->GlAccountId,
            'paymethod' => $payment->PaymentMethodId,
            'reference' => strtoupper($payment->Reference),
            'narration' => isset($payment->Narration) ? strtoupper($payment->Narration) : '',
            'pdate_err' => '',
            'student_err' => '',
            'amount_err' => '',
            'account_err' => '',
            'paymethod_err' => '',
            'reference_err' => '',
        ];
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