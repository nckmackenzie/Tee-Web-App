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
        checkrights($this->authmodel,'fee payments');
        $this->feemodel = $this->model('Fee');
    }
    
    public function index()
    {
        $data = [
            'title' => 'Fees',
            'has_datatable' => true,
            'fees' => $this->feemodel->GetFees(),
        ];
        $this->view('fees/index',$data);
    }

    public function add()
    {
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
}