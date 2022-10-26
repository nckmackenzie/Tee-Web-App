<?php

class Banks extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'banks');
        $this->bankmodel = $this->model('Bank');
    }

    public function index()
    {
        $data = [
            'title' => 'Banks',
            'has_datatable' => true,
            'banks' => $this->bankmodel->GetBanks()
        ];
        $this->view('banks/index',$data);
        exit;
    }
    //add bank
    public function add()
    {
        $data = [
            'title' => 'Add bank',
            'id' => 0,
            'isedit' => false,
            'bankname' => '',
            'accountno' => '',
        ];
        $this->view('banks/add',$data);
        exit;
    }
}