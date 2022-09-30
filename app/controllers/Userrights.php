<?php
class Userrights extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        if((int)$_SESSION['usertypeid'] > 2){
            redirect('auth/unauthorized');
            exit;
        }

        $this->rightsmodel = $this->model('Userright');
    }

    public function index()
    {
        $data = [
            'title' => 'User rights',
            'has_datatable' => false,
            'users' => $this->rightsmodel->GetUsers(),
            'user' => '',
            'forms' => $this->rightsmodel->GetForms()
        ];
        $this->view('userrights/index',$data);
        exit;
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'user' => !empty(trim($_POST['user'])) ? trim($_POST['user']) : '',
                'forms' => $_POST['formsid'],
                'names' => $_POST['formsname'],
                'access' => $_POST['access'],
            ];

            if($this->rightsmodel->CreateUpdate($data)){
                redirect('userrights');
                exit;
            }

        }else {
            redirect('auth/forbidden');
            exit;
        }
    }
}
