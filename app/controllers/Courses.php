<?php
class Courses extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'courses');
        $this->coursemodel = $this->model('Course');
    }

    public function index()
    {
        $courses = $this->coursemodel->GetCourses();
        $data = [
            'title' => 'Courses',
            'has_datatable' => true,
            'courses' => $courses
        ];
        $this->view('courses/index',$data);
    }

    public function add()
    {
        $data = [
            'title' => 'Add Course',
            'touched' => false,
            'isedit' => false,
            'id' => '',
            'coursename' => '',
            'coursecode' => '',
            'active' => true,
            'coursename_err' => '',
            'coursecode_err' => '',
        ];
        $this->view('courses/add',$data);
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => converttobool($_POST['isedit']) ? 'Edit Course' : 'Add Course',
                'touched' => true,
                'isedit' => converttobool($_POST['isedit']),
                'id' => trim($_POST['id']),
                'coursename' => trim($_POST['coursename']),
                'coursecode' => trim($_POST['coursecode']),
                'active' => converttobool($_POST['isedit']) ? isset($_POST['active']) : true,
                'coursename_err' => '',
                'coursecode_err' => '',
            ];

           

            if(empty($data['coursename'])){
                $data['coursename_err'] = 'Enter course name';
            }else{
                if(!$this->coursemodel->CheckFieldAvailability('CourseName',$data['coursename'],$data['id'])){
                    $data['coursename_err'] = 'This course already exists';
                }
            }

            if(!empty($data['coursecode']) && !$this->coursemodel->CheckFieldAvailability('CourseCode',$data['coursecode'],$data['id'])){
                $data['coursecode_err'] = 'This course code already exists';
            }

            if(!empty($data['coursecode_err']) || !empty($data['coursename_err'])){
                $this->view('courses/add',$data);
                exit();
            }

            if(!$this->coursemodel->CreateUpdate($data)){
                flash('course_msg',null,'Unable to save course. Contact admin',flashclass('alert','danger'));
                redirect('courses');
                exit();
            }

            flash('course_flash_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('courses');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        $course = $this->coursemodel->GetCourse($id);
        $data = [
            'title' => 'Edit Course',
            'touched' => false,
            'isedit' => true,
            'id' => $course->ID,
            'coursename' => strtoupper($course->CourseName),
            'coursecode' => strtoupper($course->CourseCode),
            'active' => converttobool($course->Active),
            'coursename_err' => '',
            'coursecode_err' => '',
        ];
        $this->view('courses/add',$data);
    }

    public function delete()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = trim($_POST['id']);

            if(empty($id)){
                flash('course_msg',null,'Unable to get selected course. Contact admin',flashclass('alert','danger'));
                redirect('courses');
                exit();
            }

            if(!$this->coursemodel->Delete($id)){
                flash('course_msg',null,'Unable to delete course. Contact admin',flashclass('alert','danger'));
                redirect('courses');
                exit();
            }

            flash('course_flash_msg',null,'Deleted successfully',flashclass('toast','success'));
            redirect('courses');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}