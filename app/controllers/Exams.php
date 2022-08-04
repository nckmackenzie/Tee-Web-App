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
            'touched' => false,
            'examname' => '',
            'course' => '',
            'bookid' => '',
            'examname_err' => '',
            'bookid_err' => '',
            'course_err' => '',
        ];
        $this->view('exams/add', $data);
        exit();
    }

    public function getbooks()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $course = trim($_GET['course']);
            $books = $this->exammodel->GetBooks($course);
            $output = '<option value="">Select Book</option>';
            foreach ($books as $book){
                $output .= '<option value="'.$book->ID.'">'.$book->BookName.'</option>';
            }
            echo json_encode($output);
        }else{
            redirect('auth/forbidden');
            exit();
        }
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
                'books' => '',
                'isedit' => converttobool(trim($_POST['isedit'])),
                'touched' => true,
                'examname' => !empty(trim($_POST['examname'])) ? trim($_POST['examname']) : '',
                'bookid' => !empty(trim($_POST['book'])) ? date('Y-m-d',strtotime(trim($_POST['book']))) : '',
                'course' => !empty($_POST['course']) ? trim($_POST['course']) : '',
                'examname_err' => '',
                'bookid_err' => '',
                'course_err' => '',
            ];

            if(!empty($data['course'])){
                $data['books'] = $this->exammodel->GetBooks($data['course']);
            }
            
            if(empty($data['examname'])){
                $data['examname_err'] = 'Enter exam name';
            }elseif (!empty($data['examname']) && !$this->exammodel->CheckExamName($data['examname'],$data['id'])){ 
               $data['examname_err'] = 'Exam Name Already Exists';
            }

            if(empty($data['bookid'])){
                $data['bookid_err'] = 'Select book exam based on';
            }

            if(empty($data['course'])){
                $data['course_err'] = 'Select Course Name';
            }


            if(!empty($data['examname_err']) || !empty($data['course_err']) || !empty($data['bookid_err'])){
                $this->view('exams/add',$data);
                exit();
            }

            if(!$this->exammodel->CreateUpdate($data)){
                flash('exam_msg',null,'Unable to create exam! Retry or contact admin',flashclass('alert','danger'));
                redirect('exams');
                exit();
            }

            flash('exam_flash_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('exams');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        $courses = $this->exammodel->GetCourses();
        $exam = $this->exammodel->GetExam($id);
        $data = [
            'title' => 'Edit Exam',
            'courses' => $courses,
            'id' => $exam->ID,
            'isedit' => true,
            'touched' => false,
            'examname' => strtoupper($exam->ExamName),
            'bookid' => $exam->BookId,
            'course' => $exam->CourseId,
            'examname_err' => '',
            'bookid_err' => '',
            'course_err' => '',
        ];
        $this->view('exams/add', $data);
        exit();
    }

    public function delete()
    {
        delete('exam',$this->exammodel);
    }
}
