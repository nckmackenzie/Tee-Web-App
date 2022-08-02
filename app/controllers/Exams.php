<?php 
class Exams extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->exammodel = $this->model('Exam');
    }

    public function index()
    {
        $exams = $this->exammodel->GetExams();
        $data = [
            'title' => 'Exams',
            'has_datatable' => true,
            'exams' => $exams
        ];
        $this->view('exams/index', $data);
        exit();
    }

    public function add()
    {
        $courses = $this->exammodel->GetCourses();
        $data = [
            'title' => 'Create Exam',
            'courses' => $courses,
            'id' => '',
            'isedit' => '',
            'touch' => '',
            'examname' => '',
            'examdate' => date('Y-m-d'),
            'course' => '',
            'totalmarks' => '',
            'passmarks' => '',
            'examname_err' => '',
            'examdate_err' => '',
            'course_err' => '',
            'totalmarks_err' => '',
            'passmarks_err' => '',
        ];
        $this->view('exams/add', $data);
        exit();
    }
}
