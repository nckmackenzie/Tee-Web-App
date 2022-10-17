<?php

class Users extends Controller {
    public function __construct()
    {
       if(!isset($_SESSION['userid'])){
            redirect('auth');
       }
       $this->authmodel = $this->model('Auths');
       $this->usermodel = $this->model('User');
       $this->reusemodel = $this->model('Reusable');
    }

    public function index()
    {
       checkrights($this->authmodel,'users');
       $data = [
        'title' => 'Users',
        'has_datatable' => true,
        'users' => $this->usermodel->GetUsers(),
       ];
       $this->view('users/index',$data);
    }

    public function add()
    {
        checkrights($this->authmodel,'users');
        $data = [
            'title' => 'Add Users',
            'centers' => $this->reusemodel->GetCenters(),
            'touched' => false,
            'isedit' => false,
            'id' => '',
            'userid' => '',
            'center' => '',
            'username' => '',
            'contact' => '',
            'password' => '',
            'usertype' => 4,
            'active' => true,
            'confirmpassword' => '',
            'userid_err' => '',
            'username_err' => '',
            'contact_err' => '',
            'password_err' => '',
            'usertype_err' => '',
            'confirmpassword_err' => '',
            'centers_err' => '',
            'selected_centers' => [],
        ];
        $this->view('users/add',$data);
    }

    public function profile()
    {
       $userinfo = $this->usermodel->GetUser($_SESSION['userid']);
       $data = [
        'title' => 'My Profile',
        'username' => strtoupper($userinfo->UserName),
        'contact' => $userinfo->Contact,
        'touched' => false,
        'username_err' => '',
        'contact_err' => '',
       ];
       $this->view('users/profile', $data);
    }

    public function profile_act()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => 'My Profile',
                'touched' => true,
                'username' => trim($_POST['username']),
                'contact' => trim($_POST['contact']),
                'username_err' => '',
                'contact_err' => '',
            ];
            //validation
            if(empty($data['username'])){
                $data['username_err'] = 'Please enter your full name';
            }

            //validation
            if(empty($data['contact'])){
                $data['contact_err'] = 'Please enter your contact';
            }else{
                if($this->authmodel->CheckUserAvailability($data['contact'],$_SESSION['centerid'],$_SESSION['userid'])){
                    $data['contact_err'] = 'This contact is already available';
                }
            }

            if(!empty($data['username_err']) || !empty($data['contact_err'])){
                $this->view('users/profile',$data);
            }else{
                if(!$this->usermodel->ChangeProfile($data['username'],$data['contact'])){
                    flash('home_msg',null,'Not updated.Try again or contact admin if it still doesn\'t work!',flashclass('toast','danger'));
                }else{
                    flash('home_msg',null,'Profile Updated!',flashclass('toast','success'));
                    redirect('home');
                }
            }

        } else {
            redirect('auth/forbidden');
            exit();
        }
    }

    //create user
    public function create()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => $_POST['isedit'] == true ? 'Edit Users' : 'Add Users',
                'id' => trim($_POST['id']),
                'centers' => $this->reusemodel->GetCenters(),
                'touched' => true,
                'isedit' => converttobool($_POST['isedit']),
                'username' => trim($_POST['username']),
                'contact' => trim($_POST['contact']),
                'center' => isset($_POST['centers']) ? $_POST['centers'] : '',
                'password' => $_POST['isedit'] == true ? '' : $_POST['password'],
                'usertype' => (int)$_POST['usertype'],
                'confirmpassword' => $_POST['isedit'] == true ? '' : $_POST['confirmpassword'],
                'active' => !converttobool($_POST['isedit']) ? true : (isset($_POST['active']) ? true : false),
                'username_err' => '',
                'contact_err' => '',
                'password_err' => '',
                'usertype_err' => '',
                'confirmpassword_err' => '',
                'centers_err' => '', 
                'selected_centers' => [],
            ];

           
            //validation
            if (empty($data['username'])){
                $data['username_err'] = 'Enter username';
            }

            if(empty($data['contact'])){
                $data['contact_err'] = 'Enter user contact';
            }else{
                if(!$data['isedit'] && $this->authmodel->CheckUserAvailability($data['contact'],$_SESSION['centerid'],$data['id'])){
                    $data['contact_err'] = 'Contact already exists';
                }
            }

            if(empty($data['password']) && !$data['isedit']){
                $data['password_err'] = 'Enter password';
            }

            if(empty($data['confirmpassword']) && !$data['isedit']){
                $data['confirmpassword_err'] = 'Confirm password';
            }

            if(!empty($data['password']) && !empty($data['confirmpassword']) && 
                strcmp($data['password'], $data['confirmpassword']) != 0 && !$data['isedit']){
                $data['password_err'] = 'Passwords do not match';
                $data['confirmpassword_err'] = 'Passwords do not match';  
            }

            if(empty($data['center'])){
                $data['centers_err'] = 'Select at least one center for this user';
            }else{
                for($i = 0; $i < count($data['center']); $i++){
                    array_push($data['selected_centers'],$data['center'][$i]);
                }
            }

            if(!empty($data['username_err']) || !empty($data['password_err']) || !empty($data['confirmpassword_err']) || 
               !empty($data['contact_err']) || !empty($data['centers_err'])){
               $this->view('users/add',$data);
               exit();
            }else{
                if(!$this->usermodel->CreateUser($data)){
                    flash('user_msg',null,'Something went wrong creating the user.',flashclass('alert','danger'));
                    redirect('users');
                    exit();
                }else{
                    flash('user_toast_msg',null,'Saved successfully!',flashclass('toast','success'));
                    redirect('users');
                    exit();
                }
            }


        }else {
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        checkrights($this->authmodel,'users');
        $user = $this->usermodel->GetUser($id);
        $user_centers = $this->usermodel->GetUserCenters($id);
        $data = [
            'title' => 'Edit User',
            'centers' => $this->reusemodel->GetCenters(),
            'touched' => false,
            'isedit' => true,
            'id' => $user->ID,
            'center' => '',
            'username' => strtoupper($user->UserName),
            'contact' => $user->Contact,
            'usertype' => $user->UserTypeId,
            'active' => $user->Active,
            'username_err' => '',
            'contact_err' => '',
            'usertype_err' => '',
            'centers_err' => '', 
            'selected_centers' => [],
        ];
        foreach($user_centers as $center){
            array_push($data['selected_centers'], $center->CenterId);
        }
        $this->view('users/add', $data);
        exit;
    }

    //delete
    public function Delete()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = trim($_POST['id']);
            if(!empty($id)){
                if(!$this->usermodel->Delete($id)){
                    flash('user_msg',null,'Something went wrong deleting the user.',flashclass('alert','danger'));
                    redirect('users');
                    exit();
                }else{
                    flash('user_toast_msg',null,'Deleted successfully!',flashclass('toast','success'));
                    redirect('users');
                    exit();
                }
            }else{
                flash('user_msg',null,'Something went wrong deleting the user.',flashclass('alert','danger'));
                redirect('users');
                exit();
            }
        }else {
            redirect('auth/forbidden');
            exit();
        }
    }

    public function logs()
    {
        checkrights($this->authmodel,'sale edit logs');
        $data = [
            'title' => 'Sales edit logs',
            'has_datatable' => true,
        ];
        $this->view('users/logs',$data);
    }

    public function fetchlogs()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'startdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'enddate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
                'results' => []
            ];

            if(is_null($data['startdate']) || is_null($data['enddate'])){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }

            foreach($this->usermodel->GetLogs($data) as $result) :
                array_push($data['results'],[
                    'saleDate' => date('d-m-Y',strtotime($result->SaleDate)),
                    'editDate' => date('d-m-Y',strtotime($result->EditDate)),
                    'editedBy' => $result->EditedBy,
                    'reason' => $result->Reason
                ]);
            endforeach;

            echo json_encode($data['results']);

        }else{
            redirect('users/forbidden');
            exit;
        }
    }
}