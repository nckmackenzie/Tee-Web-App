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
}