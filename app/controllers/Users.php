<?php

class Users extends Controller {
    public function __construct()
    {
       
    }

    public function index()
    {
        $data = ['title' => 'Welcome'];
       
       $this->view('users/index',$data);
    }
}