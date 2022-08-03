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
                'examdate' => !empty(trim($_POST['examdate'])) ? date('Y-m-d',strtotime(trim($_POST['examdate']))) : '',
                'course' => !empty($_POST['course']) ? trim($_POST['course']) : '',
                'totalmarks' => !empty(trim($_POST['totalmarks'])) ? trim($_POST['totalmarks']) : '',
                'passmark' => !empty(trim($_POST['passmark'])) ? trim($_POST['passmark']) : '',
                'examname_err' => '',
                'examdate_err' => '',
                'course_err' => '',
                'totalmarks_err' => '',
                'passmark_err' => '',
            ];
            
            if(empty($data['examname'])){
                $data['examname_err'] = 'Enter exam name';
            }elseif (!empty($data['examname']) && !$this->exammodel->CheckExamName($data['examname'],$data['id'])){ 
               $data['examname_err'] = 'Exam Name Already Exists';
            }

            if(empty($data['examdate'])){
                $data['examdate_err'] = 'Select exam date';
            }

            if(!empty($data['examdate']) && $data['examdate'] < date('Y-m-d')){
                $data['examdate_err'] = 'Invalid exam date';
            }

            if(empty($data['course'])){
                $data['course_err'] = 'Select Course Name';
            }

            if(empty($data['totalmarks'])){
                $data['totalmarks_err'] = 'Enter total mark';
            }

            if(empty($data['passmark'])){
                $data['passmark_err'] = 'Enter pass mark';
            }

            if(intval($data['passmark']) > intval($data['totalmarks'])){
                $data['passmark_err'] = 'Invalid pass mark';
            }

            if(!empty($data['examname_err']) || !empty($data['course_err']) || !empty($data['totalmarks_err'])
               || !empty($data['passmark_err']) || !empty($data['examdate_err'])){
                $this->view('exams/add',$data);
                exit();
            }

            if(!$this->exammodel->CreateUpdate($data)){
                flash('sale_msg',null,'Unable to create exam! Retry or contact admin',flashclass('alert','danger'));
                redirect('sales');
                exit();
            }

            flash('sale_flash_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('sales');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}
