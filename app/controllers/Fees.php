<?php
class Fees extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
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
}