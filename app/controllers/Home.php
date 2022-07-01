<?php
class Home extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
        }
    }
    public function index()
    {
        $data = ['title' => 'Dashboard'];
        $this->view('home/index', $data);
    }
}