<?php
class Feereports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        $this->reportmodel = $this->model('Feereport');
    }

    public function index()
    {
        $data = ['title' => 'Page not found!'];
        $this->view('auth/notfound',$data);
        exit;
    }
}