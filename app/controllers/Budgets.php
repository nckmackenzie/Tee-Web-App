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

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => !converttobool(trim($_POST['isedit'])) ? 'Add Budget' : 'Edit Budget',
                'years' => $this->budgetmodel->GetOpenYears(),
                'glaccounts' => $this->budgetmodel->GetExpenseAccounts(),
                'id' => trim($_POST['id']),
                'isedit' => converttobool(trim($_POST['isedit'])),
                'touched' => true,
                'budgetname' => !empty(trim($_POST['budgetname'])) ? trim($_POST['budgetname']) : '',
                'year' => !empty($_POST['year']) ? trim($_POST['year']) : '',
                'accountsid' => isset($_POST['accountsid']) ? $_POST['accountsid'] : '',
                'accountsname' => isset($_POST['accountsname']) ? $_POST['accountsname'] : '',
                'amounts' => isset($_POST['amounts']) ? $_POST['amounts'] : '',
                'table' => [],
                'budgetname_err' => '',
                'year_err' => '',
                'save_err' => '',
            ];

            if(!isset($data['accountsid']) || !is_countable($data['accountsid'])){
                $data['save_err'] = 'No accounts specified';
            }

            if(empty($data['save_err'])){
                for($i = 0; $i < count($data['accountsid']); $i++){
                    array_push($data['table'],[
                        'aid' => $data['accountsid'][$i],
                        'name' => $data['accountsname'][$i],
                        'amount' => !empty($data['amounts'][$i]) ? $data['amounts'][$i] : 0,
                    ]);
                }
            }
            
            if(empty($data['budgetname'])){
                $data['budgetname_err'] = 'Enter budget name';
            }
            if(!empty($data['budgetname']) && !$this->budgetmodel->CheckFieldExists('BudgetName',$data['id'],$data['budgetname'])){
                $data['budgetname_err'] = 'Budget name exists';
            }
            if(empty($data['year'])){
                $data['year_err'] = 'Enter budget name';
            }
            if(!empty($data['year']) && !$this->budgetmodel->CheckFieldExists('YearId',$data['id'],$data['year'])){
                $data['year_err'] = 'Budget exists for this year';
            }
            if(!empty($data['year_err']) || !empty($data['budgetname_err']) || !empty($data['save_err'])){
                $this->view('budgets/add',$data);
                exit();
            }

            if(!$this->budgetmodel->CreateUpdate($data)){
                flash('budget_msg',null,'Unable to save this budget. Please try again or contact admin',flashclass('alert','danger'));
                redirect('budgets');
                exit();
            }

            flash('budget_flash_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('budgets');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        $header = $this->budgetmodel->GetBudgetHeader($id);
        $details = $this->budgetmodel->GetBudgetDetails($id);
        $data = [
            'title' => 'Edit Budget',
            'years' => $this->budgetmodel->GetOpenYears(),
            'id' => $header->ID,
            'isedit' => true,
            'touched' => false,
            'budgetname' => strtoupper($header->BudgetName),
            'year' => $header->YearId,
            'table' => [],
            'budgetname_err' => '',
            'year_err' => '',
            'save_err' => '',
        ];
        foreach($details as $detail){
            array_push($data['table'],[
                'aid' => $detail->AccountId,
                'name' => $detail->AccountName,
                'amount' => $detail->Amount
            ]);
        }
            
        if((int)$header->CenterId !== (int)$_SESSION['centerid']){
            redirect('auth/unauthorized');
            exit();
        }
        $this->view('budgets/add',$data);
        exit();
    }

    public function delete()
    {
        delete('budget',$this->budgetmodel);
        exit();
    }
}