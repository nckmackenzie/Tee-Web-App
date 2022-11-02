<?php
class Glaccounts extends Controller 
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'g/l accounts');
        $this->glaccountmodel = $this->model('Glaccount');
    }

    public function index()
    {
        $glaccounts = $this->glaccountmodel->GetGLAccounts();
        $data = [
            'title' => 'G/L Accounts',
            'has_datatable' => true,
            'glaccounts' => $glaccounts
        ];
        $this->view('glaccounts/index',$data);
    }

    public function add()
    {
        $accounttypes = $this->glaccountmodel->GetAccountTypes();
        $data = [
            'title' => 'Add G/L Account',
            'accounttypes' => $accounttypes,
            'touched' => false,
            'isedit' => false,
            'id' => '',
            'accountname' => '',
            'accounttype' => '',
            'accountname_err' => '',
            'accounttype_err' => '',
        ];
        $this->view('glaccounts/add',$data);
        exit();
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $accounttypes = $this->glaccountmodel->GetAccountTypes();
            $data = [
                'title' => converttobool(trim($_POST['isedit'])) ? 'Edit G/L Account' : 'Add G/L Account',
                'accounttypes' => $accounttypes,
                'touched' => true,
                'isedit' => converttobool(trim($_POST['isedit'])),
                'id' => !empty(trim($_POST['id'])) ? trim($_POST['id']) : '',
                'accountname' => !empty(trim($_POST['accountname'])) ? trim($_POST['accountname']) : '',
                'accounttype' => !empty($_POST['accounttype']) ? $_POST['accounttype'] : '',
                'accountname_err' => '',
                'accounttype_err' => '',
            ];

            if(empty($data['accountname'])){
                $data['accountname_err'] = 'Enter accountname';
            }else{
                if(!$this->glaccountmodel->CheckNameAvailability($data['accountname'],$data['id'])){
                    $data['accountname_err'] = 'Accountname already exists';    
                }
            }

            if(empty($data['accounttype'])){
                $data['accounttype_err'] = 'Select account type';
            }

            if(!empty($data['accountname_err']) || !empty($data['accounttype_err'])){
                $this->view('glaccounts/add',$data);
                exit();
            }

            if(!$this->glaccountmodel->CreateUpdate($data)){
                flash('glaccount_msg',null,'Unable to save this account. Retry or contact admin',flashclass('alert','danger'));
                redirect('glaccounts');
                exit();
            }

            flash('glaccount_flash_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('glaccounts');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}