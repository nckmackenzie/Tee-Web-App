<?php
class Home extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
        }
        $this->reusemodel = $this->model('Reusable');
    }

    public function index()
    {
        $data = [
           'title' => 'Dashboard',
           'centers' => $this->reusemodel->GetCenters()
        ];
        $this->view('home/index', $data);
    }

    public function changecenter()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $centerid = htmlentities(trim($_POST['center']));
            $centerdetails = $this->reusemodel->GetCenterDetails($centerid);
            $centername = strtoupper($centerdetails->CenterName);
            $ishead = converttobool($centerdetails->IsHead);
            $isexam = converttobool($centerdetails->ExamCenter);

            unset($_SESSION['centerid']);
            unset($_SESSION['centername']);
            unset($_SESSION['ishead']);
            unset($_SESSION['examcenter']);

            $_SESSION['centerid'] = $centerid;
            $_SESSION['centername'] = $centername;
            $_SESSION['ishead'] = $ishead;
            $_SESSION['examcenter'] = $isexam;

            flash('home_msg',null,'Successfully changed center to '.ucwords($centername) ,flashclass('alert','success'));
            redirect('home');

        }else{
            redirect('auth/forbidden');
            exit;
        }
    }
}