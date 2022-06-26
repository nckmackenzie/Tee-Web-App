<?php

class Auth extends Controller {
    public function __construct()
    {
       
    }

    public function index()
    {
       $data = [
        'PageName' => 'Log In'
    ];       
       $this->view('auth/index',$data);
    }
}