<?php

class Users extends Controller {
    public function __construct()
    {
       if(!isset($_SESSION['userid'])){
            redirect('auth');
       }elseif (isset($_SESSION['userid']) && (int)$_SESSION['usertypeid'] > 2) {
          redirect('auth/unauthorized');
          exit();
       }else{
          $this->usermodel = $this->model('User');
          $this->authmodel = $this->model('Auths');
       }
    }

    public function index()
    {
       $data = [
        'title' => 'Users',
        'has_datatable' => true,
        'users' => $this->usermodel->GetUsers()
       ];
       $this->view('users/index',$data);
    }

    public function add()
    {
        $data = [
            'title' => 'Add Users',
            'userid' => '',
            'username' => '',
            'contact' => '',
            'password' => '',
            'usertype' => '',
            'confirmpassword' => '',
            'userid_err' => '',
            'username_err' => '',
            'contact_err' => '',
            'password_err' => '',
            'usertype_err' => '',
            'confirmpassword_err' => '',
        ];
        $this->view('users/add',$data);
    }

    public function profile()
    {
       $data = [
        'title' => 'My Profile',
        'username' => $_SESSION['username'],
        'username_err' => ''
       ];
       $this->view('users/profile', $data);
    }

    public function profile_act()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => 'My Profile',
                'username' => trim($_POST['username']),
                'username_err' => ''
            ];
            //validation
            if(empty($data['username'])){
                $data['username_err'] = 'Please enter your full name';
            }

            if(!empty($data['username_err'])){
                $this->view('users/profile',$data);
            }else{
                if(!$this->usermodel->ChangeProfile($data['username'])){
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
                'title' => 'Add Users',
                'username' => trim($_POST['username']),
                'contact' => trim($_POST['contact']),
                'password' => $_POST['password'],
                'usertype' => (int)$_POST['usertype'],
                'confirmpassword' => $_POST['confirmpassword'],
                'username_err' => '',
                'contact_err' => '',
                'password_err' => '',
                'usertype_err' => '',
                'confirmpassword_err' => '', 
            ];

            //validation
            if (empty($data['username'])){
                $data['username_err'] = 'Enter username';
            }

            if(empty($data['contact'])){
                $data['contact_err'] = 'Enter user contact';
            }else{
                if($this->authmodel->CheckUserAvailability($data['contact'],$_SESSION['centerid'])){
                    $data['contact_err'] = 'Contact already exists';
                }
            }

            if(empty($data['password'])){
                $data['password_err'] = 'Enter password';
            }

            if(empty($data['confirmpassword'])){
                $data['confirmpassword_err'] = 'Confirm password';
            }

            if(!empty($data['password']) && !empty($data['confirmpassword']) && 
                strcmp($data['password'], $data['confirmpassword']) != 0){
                $data['password_err'] = 'Passwords do not match';
                $data['confirmpassword_err'] = 'Passwords do not match';  
            }

            if(!empty($data['username_err']) || !empty($data['password_err']) || !empty($data['confirmpassword_err']) || 
               !empty($data['contact_err'])){
               $this->view('users/add',$data);
               exit();
            }else{
                if(!$this->usermodel->CreateUser($data)){
                    flash('user_msg',null,'Something went wrong creating the user.',flashclass('alert','danger'));
                    redirect('users');
                    exit();
                }else{
                    flash('user_toast_msg',null,'User created successfully!',flashclass('toast','success'));
                    redirect('users');
                    exit();
                }
            }


        }else {
            redirect('auth/forbidden');
            exit();
        }
    }
}