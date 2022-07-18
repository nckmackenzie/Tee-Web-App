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

    public function add()
    {
        $data = [
            'title' => 'Add student',
            'isedit' => false,
            'touched' => false,
            'id' => '',
            'sname' => '',
            'idno' => '',
            'contact' => '',
            'admno' => '',
            'gender' => '',
            'admdate' => '',
            'sname_err' => '',
            'contact_err' => '',
            'idno_err' => '',
            'gender_err' => '',
            'admdate_err' => '',
            'admno_err' => '',
        ];
        $this->view('students/add',$data);
        exit();
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST,FILTER_UNSAFE_RAW);
            $data = [
                'title' => converttobool(trim($_POST['isedit'])) ? 'Edit student' : 'Add student',
                'isedit' => converttobool(trim($_POST['isedit'])),
                'touched' => true,
                'id' => trim($_POST['id']),
                'sname' => trim($_POST['sname']),
                'idno' => trim($_POST['idno']),
                'contact' => trim($_POST['contact']),
                'admno' => !empty(trim($_POST['admno'])) ? trim($_POST['admno']) : '',
                'gender' => !empty(trim($_POST['gender'])) ? trim($_POST['gender']) : '',
                'admdate' => !empty(trim($_POST['admdate'])) ? date('Y-m-d',strtotime(trim($_POST['admdate']))) : '',
                'sname_err' => '',
                'contact_err' => '',
                'gender_err' => '',
                'admdate_err' => '',
                'idno_err' => '',
                'admno_err' => '',
            ];

            //validation
            if(empty($data['sname'])){
                $data['sname_err'] = 'Enter student name';
            }

            if(empty($data['contact'])){
                $data['contact_err'] = 'Enter student contact';
            }else{
                if(!$this->studentmodel->CheckFieldsAvailability('Contact',$data['contact'],$data['id'])){
                    $data['contact_err'] = 'Entered contact already exists';
                }
            }

            if(!empty($data['idno']) && !$this->studentmodel->CheckFieldsAvailability('IdNumber',$data['idno'],$data['id'])){
                $data['idno_err'] = 'ID Number already exists';
            }

            if(!empty($data['admno']) && !$this->studentmodel->CheckFieldsAvailability('IdNumber',$data['admno'],$data['id'])){
                $data['admno_err'] = 'Admision Number already exists';
            }

            if(empty($data['gender'])){
                $data['gender_err'] = 'Select student gender';
            }

            if(!empty($data['admdate']) && date('Y-m-d',strtotime($data['admdate'])) > date('Y-m-d')){
                $data['admdate_err'] = 'Invalid date selected';
            }

            if(!empty($data['sname_err']) || !empty($data['contact_err']) || !empty($data['gender_err']) 
               || !empty($data['admdate_err']) || !empty($data['idno_err']) || !empty($data['admno_err'])){
                $this->view('students/add',$data);
                exit();
            }

            if(!$this->studentmodel->CreateUpdate($data)){
                flash('student_msg',null,'Student not saved. Retry or contact admin.',flashclass('alert','danger'));
                redirect('students');
                exit();
            }

            flash('student_flash_msg',null,'Saved successfully!',flashclass('toast','success'));
            redirect('students');
            exit();
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        $student = $this->studentmodel->GetStudent($id);
        $data = [
            'title' => 'Edit student',
            'isedit' => true,
            'touched' => false,
            'id' => $student->ID,
            'sname' => strtoupper($student->StudentName),
            'idno' => decrypt($student->IdNumber),
            'contact' => decrypt($student->Contact),
            'admno' => strtoupper($student->AdmisionNo),
            'gender' => $student->GenderId,
            'admdate' => $student->RegistrationDate,
            'sname_err' => '',
            'contact_err' => '',
            'idno_err' => '',
            'gender_err' => '',
            'admdate_err' => '',
            'admno_err' => '',
        ];
        $this->view('students/add',$data);
        exit();
    }

    public function delete()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = trim(htmlentities($_POST['id']));
            if(empty($id)){
                flash('student_msg',null,'Unable to get selected student',flashclass('alert','danger'));
                redirect('students');
                exit();
            }

            if(!$this->studentmodel->Delete($id)){
                flash('student_msg',null,'Student not deleted! Tryto or contact admin!',flashclass('alert','danger'));
                redirect('students');
                exit();
            }

            flash('student_flash_msg',null,'Deleted successfully!',flashclass('toast','success'));
            redirect('students');
            exit();
            
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}
