<?php
class Centers extends Controller 
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }elseif(isset($_SESSION['userid']) && $_SESSION['usertypeid'] > 2 || (int)$_SESSION['ishead'] !=1 ){
            redirect('auth/forbidden');
            exit();
        }else{
            $this->centermodel = $this->model('Center');
        }
    }
    public function index()
    {
        $data = [
            'title' => 'Centers',
            'has_datatable' => true,
            'centers' => $this->centermodel->GetCenters()
        ];
        $this->view('centers/index', $data);
    }
}