<?php
class Students extends Controller
{
    public function __construct()
    {
        if(!is_authenticated($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->studentmodel =  $this->model('Student');
    }

    public function index()
    {
        $activestudents = $this->studentmodel->GetActiveStudents();
        $data = [
            'title' => 'Students',
            'has_datatable' => true,
            'activestudents' => $activestudents
        ];
        $this->view('students/index',$data);
    }
}
