<?php
class Years extends Controller
{
    public function __construct(){
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }elseif(isset($_SESSION['userid']) && $_SESSION['usertypeid'] > 2 || (int)$_SESSION['ishead'] !== 1){
            redirect('auth/unauthorized');
            exit();
        }else{
            $this->yearmodel = $this->model('Year');
        }
    }

    public function index()
    {
        $years = $this->yearmodel->GetYears();
        $data = [
            'title' => 'Financial Years',
            'has_datatable' => true,
            'years' => $years
        ];
        $this->view('years/index',$data);
    }
}