<?php
class Budgets extends Controller
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
        $this->budgetmodel = $this->model('Budget');
    }

    public function index()
    {
        $data = [
            'title' => 'Budgets',
            'has_datatable' => true,
            'budgets' => $this->budgetmodel->GetBudgets()
        ];
        $this->view('budgets/index',$data);
        exit();
    }

    public function add()
    {
        $data = [
            'title' => 'Add Budget',
            'years' => $this->budgetmodel->GetOpenYears(),
            'glaccounts' => $this->budgetmodel->GetExpenseAccounts(),
            'id' => '',
            'isedit' => false,
            'touched' => false,
            'budgetname' => '',
            'year' => '',
            'table' => [],
            'budgetname_err' => '',
            'year_err' => '',
            'save_err' => '',
        ];
        foreach($data['glaccounts'] as $account){
            array_push($data['table'],[
                'aid' => $account->ID,
                'name' => $account->AccountName,
                'amount' => ''
            ]);
        }
        $this->view('budgets/add',$data);
        exit();
    }
}