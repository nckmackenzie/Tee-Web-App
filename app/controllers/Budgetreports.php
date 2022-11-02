<?php

class Budgetreports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        $this->reusemodel = $this->model('Reusable');
        $this->reportmodel = $this->model('Budgetreport');
    }

    public function index()
    {
        $data= ['title' => 'Page not found'];
        $this->view('auth/notfound',$data);
        exit;
    }
    
    public function summary()
    {
        $data = [
            'title' => 'Budget summary',
            'has_datatable' => true,
            'years' => $this->reusemodel->GetYears(true)
        ];
        $this->view('budgetreports/summary',$data);
        exit;
    }

    public function summaryrpt()
    {
        
    }
}