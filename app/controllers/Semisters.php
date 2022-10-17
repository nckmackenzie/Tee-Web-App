<?php
class Semisters extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'semisters');
        $this->semistermodel = $this->model('Semister');
    }

    public function index()
    {
        $data = [
            'title' => 'Semisters',
            'has_datatable' => true,
            'semisters' => $this->semistermodel->GetSemisters()
        ];
        $this->view('semisters/index',$data);
        exit;
    }
}