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
                'bookid' => !empty($_POST['book']) ? trim($_POST['book']) : '',
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
        $books = $this->exammodel->GetBooks($exam->CourseId);
        $data = [
            'title' => 'Edit Exam',
            'courses' => $courses,
            'id' => $exam->ID,
            'isedit' => true,
            'touched' => false,
            'books' => $books,
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

    public function receiptfromgroup()
    {
        $data = [
            'title' => 'Receive from Group',
            'receiptdate' => date('Y-m-d'),
            'groups' => $this->exammodel->GetGroups(),
            'exams' => $this->exammodel->GetExams(),
            'touched' => false,
            'id' => '',
            'group' => '',
            'exam' => '',
            'submitdate' => '',
            'remarks' => '',
            'table' => [],
            'receiptdate_err' => '',
            'exam_err' => '',
            'group_err' => '',
            'submitdate_err' => '',
            'save_err' => ''
        ];
        $this->view('exams/receiptfromgroup',$data);
        exit();
    }

    public function getstudents()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $gid = intval(trim($_GET['gid']));
            if(empty($gid)){
                exit();
            }
            $output = '';
            $students = $this->exammodel->GetStudentsByGroup($gid);
            foreach($students as $student) {
                $output .= '
                    <tr>
                        <td class="d-none"><input type="text" name="studentsid[]" value="'.$student->ID.'"></td>
                        <td><input type="text" class="table-input w-100" name="names[]" value="'.$student->StudentName.'" readonly></td>
                        <td>
                            <button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button>
                        </td>
                    </tr>
                ';
            }

            echo json_encode($output);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function createreceiptfromgroup()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => 'Receive from Group',
                'groups' => $this->exammodel->GetGroups(),
                'exams' => $this->exammodel->GetExams(),
                'touched' => true,
                'id' => '',
                'receiptdate' => !empty(trim($_POST['receiptdate'])) ? date('Y-m-d',strtotime(trim($_POST['receiptdate']))) : '',
                'group' => !empty($_POST['group']) ? $_POST['group'] : '',
                'exam' => !empty($_POST['exam']) ? $_POST['exam'] : '',
                'submitdate' => !empty(trim($_POST['submitdate'])) ? date('Y-m-d',strtotime(trim($_POST['submitdate']))) : '',
                'remarks' => !empty(trim($_POST['remarks'])) ? trim($_POST['remarks']) : '',
                'table' => [],
                'studentsid' => $_POST['studentsid'],
                'names' => $_POST['names'],
                'receiptdate_err' => '',
                'exam_err' => '',
                'group_err' => '',
                'submitdate_err' => '',
                'save_err' => ''
            ];

            for($i = 0; $i < count($data['studentsid']); $i++){
                array_push($data['table'],[
                    'sid' => $data['studentsid'][$i],
                    'name' => $data['names'][$i],
                ]);
            }

            if(empty($data['receiptdate']) || date('Y-m-d') < $data['receiptdate']){
                $data['receiptdate_err'] = 'Invalid receipt date.';
            }

            if(empty($data['exam'])){
                $data['exam_err'] = 'Select exam';
            }

            if(empty($data['group'])){
                $data['group_err'] = 'Select group';
            }

            if(empty($data['submitdate']) || date('Y-m-d') < $data['submitdate']){
                $data['submitdate_err'] = 'Invalid submit date.';
            }

            if(!empty($data['receiptdate']) && !empty($data['submitdate']) && $data['receiptdate'] > $data['submitdate']){
                $data['submitdate_err'] = 'Submit date earlier than receipt date';
            }

            if(!empty($data['exam']) && !empty($data['group']) 
                && !$this->exammodel->CheckExamSubmission($data['group'],$data['exam'])){
                $data['exam_err'] = 'Exam already submitted for this group';    
            }

            if(!empty($data['exam_err']) || !empty($data['group_err']) || !empty($data['submitdate_err'])
               || !empty($data['receiptdate_err'])){
                $this->view('exams/receiptfromgroup',$data);
                exit();
            }

            if(!$this->exammodel->CreateReceiptFromGroup($data)){
                $data['save_err'] = 'Error creating this transaction';
                $this->view('exams/receiptfromgroup',$data);
                exit();
            }

            flash('home_msg',null,'Exam Receipt successfully!',flashclass('toast','success'));
            redirect('home');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}
