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
            'touch' => false,
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
}