<?php

class Auth extends Controller {
    public function __construct()
    {
       $this->authmodel = $this->model('Auths');
    }

    public function index()
    {
       $data = [
            'title' => 'Log In',
            'centers' => $this->authmodel->LoadCenters(),
            'userid' => '',
            'password' => '',
            'center' => '',
            'userid_err' => '',
            'password_err' => '',
            'center_err' => '',
        ];       
       $this->view('auth/index',$data);
    }

    public function forbidden()
    {
        $data = ['title' => '403 Forbidden!',];
        $this->view('auth/forbidden',$data);
    }

    public function reset_password()
    {
        $data = [
            'title' => 'Reset Password',
            'contact' => ''
        ];
        $this->view('auth/reset_password',$data); 
    }

    public function reset_password_act()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            redirect('auth');
        } else {
            redirect('/login');
        }
    }

    public function login_act()
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST,FILTER_UNSAFE_RAW);
            $data = [
                'title' => 'Log In',
                'centers' => $this->authmodel->LoadCenters(),
                'userid' => $_POST['userid'],
                'password' => $_POST['password'],
                'center' => !empty($_POST['center']) ? $_POST['center'] : '',
                'userid_err' => '',
                'password_err' => '',
                'center_err' => '',
            ];

            //validation
            if(empty($data['userid'])){
                $data['userid_err'] = 'Enter your userid';
            }

            if(empty($data['password'])){
                $data['password_err'] = 'Enter your password';
            }

            if(empty($data['center'])){
                $data['center_err'] = 'Select center';
            }

            if(!empty($data['userid']) && !empty($data['center']) && 
               !$this->authmodel->CheckUserAvailability($data['userid'], $data['center'])){
                $data['userid_err'] = 'Userid not available or user is deactivated';
            }

            if(!empty($data['userid_err']) || !empty($data['password_err']) || !empty($data['center_err'])){
                $this->view('auth/index',$data);
                exit();
            }else{
                redirect('auth/forbidden');
            }

            // flash('test_msg',null,'Created Successfully!',flashclass('toast','danger'));
        }
    }
    
}