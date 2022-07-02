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
                $loggeduser = $this->authmodel->Login($data['userid'],$data['password'],$data['center']);
                if(!$loggeduser){
                    $data['password_err'] = 'Invalid Password';
                    $this->view('auth/index',$data);
                }else{
                    $this->createsession($loggeduser); //log user in and create session
                }
            }
        }
    }
    
    public function createsession($user)
    {
        $_SESSION['userid'] = $user->ID;
        $_SESSION['username'] = $user->UserName;
        $_SESSION['usertypeid'] = $user->UserTypeId;
        $_SESSION['usertype'] = $user->UserType;
        $_SESSION['ishead'] = $user->IsHead;
        $_SESSION['centerid'] = $user->CenterId;
        $_SESSION['centername'] = $user->CenterName;
        flash('home_msg',null,'Login Success!',flashclass('toast','success'));
        redirect('home');
    }
    //logout functionality
    public function logout()
    {
        unset($_SESSION['userid']);
        unset($_SESSION['username']);
        unset($_SESSION['usertypeid']);
        unset($_SESSION['usertype']);
        unset($_SESSION['ishead']);
        unset($_SESSION['centerid']);
        unset($_SESSION['centername']);
        session_destroy();
        redirect('home');
    }

    //password change
    public function change_password()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $data = [
            'title' => 'Change Password',
            'oldpassword' => '',
            'newpassword' => '',
            'confirmpassword' => '',
            'oldpassword_err' => '',
            'newpassword_err' => '',
            'confirmpassword_err' => '',
        ];
        $this->view('auth/change_password', $data);
        exit();
    }

    //action for change password
    public function change_password_act()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => 'Change Password',
                'oldpassword' => trim($_POST['oldpassword']),
                'newpassword' => trim($_POST['newpassword']),
                'confirmpassword' => trim($_POST['confirmpassword']),
                'oldpassword_err' => '',
                'newpassword_err' => '',
                'confirmpassword_err' => '',
            ];

            if(empty($data['oldpassword'])) {
                $data['oldpassword_err'] = 'Enter old password';
            }else{
                if(!$this->authmodel->ValidatePassword($data['oldpassword'])){
                    $data['oldpassword_err'] = 'Old password is incorrect';
                }
            }

            if(empty($data['newpassword'])) {
                $data['newpassword_err'] = 'Enter new password';
            }

            if(empty($data['confirmpassword'])) {
                $data['confirmpassword_err'] = 'Confirm password';
            }

            if(!empty($data['newpassword']) && !empty($data['confirmpassword']) && 
                strcmp($data['newpassword'],$data['confirmpassword']) !=0) {
                $data['newpassword_err'] = 'Passwords do not match';    
                $data['confirmpassword_err'] = 'Passwords do not match';    
            }

            //if errors
            if(!empty($data['oldpassword_err']) || !empty($data['newpassword_err']) || 
               !empty($data['confirmpassword_err'])){
                $this->view('auth/change_password',$data);
                exit();
            }else{
                //password not changed
                if(!$this->authmodel->ChangePassword($data['newpassword'])){
                    flash('pwd_msg',null,'Something went wrong! Retry or contact admin',flashclass('alert','danger'));
                    redirect('auth/change_password');
                    exit();
                }else{
                    flash('home_msg',null,'Password changed successfully!',flashclass('toast','success'));
                    redirect('home');
                }
            }

        }else {
            redirect('auth/forbidden');
            exit();
        }
    }
}