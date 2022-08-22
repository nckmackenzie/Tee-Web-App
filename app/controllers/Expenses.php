<?php
class Expenses extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->expensemodel = $this->model('Expense');
    }

    public function index()
    {
        $data = [
            'title' => 'Expenses',
            'has_datatable' => true,
            'expenses' => $this->expensemodel->GetExpenses()
        ];
        $this->view('expenses/index',$data);
    }

    public function add()
    {
        $data = [
            'title' => 'Add Expense',
            'accounts' => $this->expensemodel->GetExpenseAccounts(),
            'isedit' => false,
            'touched' => false,
            'id' => '',
            'edate' => '',
            'voucherno' => '',
            'account' => '',
            'amount' => '',
            'paymethod' => '',
            'reference' => '',
            'narration' => '',
            'edate_err' => '',
            'voucherno_err' => '',
            'account_err' => '',
            'amount_err' => '',
            'paymethod_err' => '',
            'reference_err' => '',
        ];
        $this->view('expenses/add',$data);
        exit();
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST,FILTER_UNSAFE_RAW);
            $data = [
                'title' => !converttobool(trim($_POST['isedit'])) ? 'Add Expense' : 'Edit Expense',
                'accounts' => $this->expensemodel->GetExpenseAccounts(),
                'isedit' => converttobool(trim($_POST['isedit'])),
                'touched' => true,
                'id' => trim($_POST['id']),
                'edate' => !empty(trim($_POST['edate'])) ? date('Y-m-d',strtotime(trim($_POST['edate']))) : '',
                'voucherno' => !empty(trim($_POST['voucherno'])) ? trim($_POST['voucherno']) : '',
                'account' => !empty($_POST['account']) ? trim($_POST['account']) : '',
                'amount' => !empty(trim($_POST['amount'])) ? floatval(trim($_POST['amount'])) : '',
                'paymethod' => !empty($_POST['paymethod']) ? trim($_POST['paymethod']) : '',
                'reference' => !empty($_POST['reference']) ? trim($_POST['reference']) : '',
                'narration' => !empty($_POST['narration']) ? trim($_POST['narration']) : '',
                'edate_err' => '',
                'voucherno_err' => '',
                'account_err' => '',
                'amount_err' => '',
                'paymethod_err' => '',
                'reference_err' => '',
            ];

            if(empty($data['edate'])){
                $data['edate_err'] = 'Select date';
            }
            if(!empty($data['edate']) && !validatedate($data['edate'])){
                $data['edate_err'] = 'Invalid date selected';
            }
            if(empty($data['voucherno'])){
                $data['voucherno_err'] = 'Enter voucher no';
            }
            if(!empty($data['voucherno']) && !$this->expensemodel->CheckFieldAvailability('VoucherNo',$data['voucherno'],$data['id'])){
                $data['voucherno_err'] = 'Voucher no already exists';
            }
            if(empty($data['account'])){
                $data['account_err'] = 'Select account';
            }
            if(empty($data['amount'])){
                $data['amount_err'] = 'Enter amount';
            }
            if(empty($data['paymethod'])){
                $data['paymethod_err'] = 'Select payment method';
            }
            if(empty($data['reference'])){
                $data['reference_err'] = 'Enter payment reference';
            }
            if(!empty($data['reference']) && !$this->expensemodel->CheckFieldAvailability('PaymentReference',$data['reference'],$data['id'])){
                $data['reference_err'] = 'Payment reference already exists';
            }

            if(!empty($data['edate_err']) || !empty($data['voucher_err']) || !empty($data['account_err']) 
               || !empty($data['amount_err']) || !empty($data['paymethod_err']) || !empty($data['reference_err'])){
                $this->view('expenses/add',$data);
                exit();
            }

            if(!$this->expensemodel->CreateUpdate($data)){
                flash('expense_msg',null,'Unable to save. Retry or contact admin',flashclass('alert','danger'));
                redirect('expenses');
                exit();
            }
            
            flash('expense_flash_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('expenses');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        $expense = $this->expensemodel->GetExpense($id);
        $data = [
            'title' => 'Edit Expense',
            'accounts' => $this->expensemodel->GetExpenseAccounts(),
            'isedit' => true,
            'touched' => false,
            'id' => $expense->ID,
            'edate' => $expense->ExpenseDate,
            'voucherno' => $expense->VoucherNo,
            'account' => $expense->AccountId,
            'amount' => $expense->Amount,
            'paymethod' => $expense->PaymentMethodId,
            'reference' => strtoupper($expense->PaymentReference),
            'narration' => strtoupper($expense->Narration),
            'edate_err' => '',
            'voucherno_err' => '',
            'account_err' => '',
            'amount_err' => '',
            'paymethod_err' => '',
            'reference_err' => '',
        ];
        if((int)$expense->CenterId !== (int)$_SESSION['centerid']){
            redirect('auth/unauthorized');
            exit();
        }
        $this->view('expenses/add',$data);
        exit();
    }
}