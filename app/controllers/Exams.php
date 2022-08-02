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
            'touched' => '',
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

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST,FILTER_UNSAFE_RAW);
            $courses = $this->exammodel->GetCourses();
            $data = [
                'title' => converttobool(trim($_POST['isedit'])) ? 'Edit Exam' : 'Create Exam',
                'courses' => $courses,
                'id' => trim($_POST['id']),
                'isedit' => converttobool(trim($_POST['isedit'])),
                'touched' => true,
                'examname' => !empty(trim($_POST['examname'])) ? trim($_POST['examname']) : '',
                'examdate' => !empty(trim($_POST['examdate'])) ? trim($_POST['examdate']) : '',
                'course' => !empty($_POST['course']) ? trim($_POST['course']) : '',
                'totalmarks' => !empty(trim($_POST['totalmarks'])) ? trim($_POST['totalmarks']) : '',
                'passmarks' => !empty(trim($_POST['passmark'])) ? trim($_POST['passmark']) : '',
                'examname_err' => '',
                'examdate_err' => '',
                'course_err' => '',
                'totalmarks_err' => '',
                'passmarks_err' => '',
            ];
            
            if(empty($data['examname'])){
                $data['examname_err'] = 'Enter exam name';
            }

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}
