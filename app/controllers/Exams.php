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
            $course = (int)trim($_GET['course']);
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
            $type = trim($_GET['type']);
            if(empty($gid)){
                exit();
            }
            $output = '';
            $students = $this->exammodel->GetStudentsByGroup($gid,$type);
            foreach($students as $student) {
                $output .= '
                    <tr>
                        <td class="d-none"><input type="text" name="studentsid[]" value="'.$student->ID.'"></td>
                        <td><input type="text" class="table-input w-100" name="names[]" value="'.$student->StudentName.'" readonly></td>
                        ';
                        if($type === 'fromgroup'){
                            $output .= '
                            <td>
                                <button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button>
                            </td>';
                        }elseif($type ==='formarking'){
                            $output .= '
                            <td><input type="number" class="table-input" name="marks[]" value=""></td>';    
                        }
                    $output .= '    
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

    public function receiptmarking()
    {
        $data = [
            'title' => 'Exam points entry',
            'fromcenters' => $this->exammodel->GetCentersByStatus(1),
            'exams' => '',
            'groups' => '',
            'touched' => false,
            'id' => '',
            'receiptdate' => '',
            'fromcenter' => '',
            'exam' => '',
            'group' => '',
            'centerremarks' => '',
            'markingremarks' => '',
            'table' => [],
            'receiptdate_err' => '',
            'fromcenter_err' => '',
            'exam_err' => '',
            'group_err' => '',
            'save_err' => '',
        ];
        $this->view('exams/receiptmarking', $data);
    }
    public function getselectoptions()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'type' => trim($_GET['type']),
                'value' => trim($_GET['value']),
                'status' => (int)trim($_GET['status'])
            ];

            $values = $this->exammodel->GetSelectOptions($data['type'], $data['value'],$data['status']);
            $output = '<option value="" selected disabled>Select '.$data['type'].'</option>';
            foreach($values as $value){
                $output .= '<option value="'.$value->ID.'">'.$value->CriteriaName.'</option>';
            }
            echo json_encode($output);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function getheaderid()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET, FILTER_UNSAFE_RAW);
            $data = [
                'examid' => (int)trim($_GET['examId']),
                'groupid' => (int)trim($_GET['groupId']),
                'centerid' => (int)trim($_GET['centerId']),
                'status' => (int)trim($_GET['status'])
            ];

            $headerid = $this->exammodel->GetId($data)[0];
            $remarks = $this->exammodel->GetId($data)[1];
            $results = [
                'id' => $headerid,
                'remarks' => $remarks,
            ];
            echo json_encode($results);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function createreceiptmarking()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => 'Exam points entry',
                'fromcenters' => $this->exammodel->GetCentersByStatus(1),
                'touched' => true,
                'exams' => '',
                'groups' => '',
                'id' => trim($_POST['id']),
                'receiptdate' => !empty(trim($_POST['receiptdate'])) ? date('Y-m-d',strtotime($_POST['receiptdate'])) : '',
                'fromcenter' => !empty($_POST['fromcenter']) ? $_POST['fromcenter'] : '',
                'group' => !empty($_POST['group']) ? $_POST['group'] : '',
                'exam' => !empty($_POST['exam']) ? $_POST['exam'] : '',
                'centerremarks' => trim($_POST['centerremarks']),  
                'markingremarks' => !empty(trim($_POST['markingremarks'])) ? trim($_POST['markingremarks']) : '',
                'table' => [],
                'studentsid' => $_POST['studentsid'],
                'names' => $_POST['names'],
                'marks' => $_POST['marks'],
                'receiptdate_err' => '',
                'fromcenter_err' => '',
                'exam_err' => '',
                'group_err' => '',
                'save_err' => '',
            ];
            
            for($i = 0; $i < count($data['studentsid']); $i++){
                array_push($data['table'],[
                    'sid' => $data['studentsid'][$i],
                    'name' => $data['names'][$i],
                    'marks' => !empty($data['marks'][$i]) ? $data['marks'][$i] : 0,
                ]);
            }

            if(empty($data['receiptdate'])){
                $data['receiptdate_err'] = 'Select receipt date';
            }

            if(!empty($data['receiptdate']) && $data['receiptdate'] > date('Y-m-d')){
                $data['receiptdate_err'] = 'Receipt date cannot be greater than today';
            }

            if(empty($data['fromcenter'])){
                $data['fromcenter_err'] = 'Select center';
            }

            if(empty($data['group'])){
                $data['group_err'] = 'Select group';
            }

            if(empty($data['exam'])){
                $data['exam_err'] = 'Select exam';
            }

            if(!empty($data['fromcenter'])){
                $data['groups'] = $this->exammodel->GetSelectOptions('group', $data['fromcenter'],1);
            }

            if(!empty($data['group'])){
                $data['exams'] = $this->exammodel->GetSelectOptions('exam', $data['group'],1);
            }

            if(!empty($data['fromcenter_err']) || !empty($data['receiptdate_err']) || !empty($data['group_err'])
               || !empty($data['exam_err'])){
                $this->view('exams/receiptmarking',$data);
                exit();
            }

            if(!$this->exammodel->CreateReceiptMarking($data)){
                $data['save_err'] = 'Unable to save this transaction';
                $this->view('exams/receiptmarking',$data);
                exit();
            }

            flash('home_msg',null,'Saved successfully!',flashclass('toast','success'));
            redirect('home');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function receiptpostmarking()
    {
        $centers = $this->exammodel->GetCentersByStatus(2);
        $data = [
            'title' => 'Receipt Post Marking',
            'centers' => $centers,
            'groups' => '',
            'exams' => '',
            'touched' => false,
            'id' => '',
            'receiptdate' => '',
            'fromcenter' => '',
            'group' => '',
            'exam' => '',
            'table' => [],
            'center_available' => '',
            'markerremarks' => '',
            'receiptremarks' => '',
            'receiptdate_err' => '',
            'fromcenter_err' => '',
            'centername' => '',
            'group_err' => '',
            'exam_err' => '',
            'save_err' => ''
        ];
        foreach($centers as $center){
            if((int)$_SESSION['centerid'] === (int)$center->ID){
                $data['center_available'] = true;
                $data['centername'] = $this->exammodel->GetCenterName($center->ID);
            }
        }
        $this->view('exams/receiptpostmarking', $data);
        exit();
    }

    public function getstudentmarks()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $id = intval(trim($_GET['id']));
            if(empty($id)){
                exit();
            }
            $output = '';
            $students = $this->exammodel->GetStudentsMarks($id);
            foreach($students as $student) {
                $output .= '
                    <tr>
                        <td class="d-none"><input type="text" name="studentsid[]" value="'.$student->ID.'"></td>
                        <td><input type="text" class="table-input w-100" name="names[]" value="'.$student->StudentName.'" readonly></td>
                        <td><input type="number" class="table-input" name="marks[]" value="'.$student->Marks.'" readonly></td>
                    </tr>
                ';
            }

            echo json_encode($output);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function createreceiptpostmarking()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $centers = $this->exammodel->GetCentersByStatus(2);
            $data = [
                'title' => 'Receipt Post Marking',
                'centers' => $centers,
                'groups' => '',
                'exams' => '',
                'touched' => true,
                'id' => trim($_POST['id']),
                'receiptdate' => !empty(trim($_POST['receiptdate'])) ? date('Y-m-d',strtotime(trim($_POST['receiptdate']))) : '',
                'fromcenter' => !empty($_POST['fromcenter']) ? $_POST['fromcenter'] : '',
                'group' => !empty($_POST['group']) ? $_POST['group'] : '',
                'exam' => !empty($_POST['exam']) ? $_POST['exam'] : '',
                'center_available' => converttobool(trim($_POST['centeravailable'])),
                'markerremarks' => !empty($_POST['markerremarks']) ? $_POST['markerremarks'] : '',
                'receiptremarks' => !empty($_POST['receiptremarks']) ? $_POST['receiptremarks'] : '',
                'studentsid' => $_POST['studentsid'],
                'names' => $_POST['names'],
                'marks' => $_POST['marks'],
                'table' => [],
                'receiptdate_err' => '',
                'fromcenter_err' => '',
                'centername' => '',
                'group_err' => '',
                'exam_err' => '',
                'save_err' => ''
            ];

            if($data['center_available']){
                $data['centername'] = $this->exammodel->GetCenterName((int)$data['fromcenter']);
            }

            for($i = 0; $i < count($data['studentsid']); $i++){
                array_push($data['table'],[
                    'sid' => $data['studentsid'][$i],
                    'name' => $data['names'][$i],
                    'marks' => !empty($data['marks'][$i]) ? $data['marks'][$i] : 0,
                ]);
            }

            if(empty($data['receiptdate'])){
                $data['receiptdate_err'] = 'Select receipt date';
            }

            if(!empty($data['receiptdate']) && $data['receiptdate'] > date('Y-m-d')){
                $data['receiptdate_err'] = 'Receipt date cannot be greater than today';
            }

            if(empty($data['fromcenter'])){
                $data['fromcenter_err'] = 'Select center';
            }

            if(empty($data['group'])){
                $data['group_err'] = 'Select group';
            }

            if(empty($data['exam'])){
                $data['exam_err'] = 'Select exam';
            }

            if(!empty($data['fromcenter'])){
                $data['groups'] = $this->exammodel->GetSelectOptions('group', $data['fromcenter'],2);
            }

            if(!empty($data['group'])){
                $data['exams'] = $this->exammodel->GetSelectOptions('exam', $data['group'],2);
            }

            if(!empty($data['fromcenter_err']) || !empty($data['receiptdate_err']) || !empty($data['group_err'])
               || !empty($data['exam_err'])){
                $this->view('exams/receiptpostmarking',$data);
                exit();
            }

            if(!$this->exammodel->CreateReceiptPostMarking($data)){
                $data['save_err'] = 'Unable to save this transaction';
                $this->view('exams/receiptpostmarking',$data);
                exit();
            }

            flash('home_msg',null,'Saved successfully!',flashclass('toast','success'));
            redirect('home');
            exit();
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
    public function points()
    {
        $data = [
            'title' => 'Points',
            'has_datatable' => true,
            'points' => $this->exammodel->GetPoints(),
        ];
        $this->view('exams/points',$data);
        exit();
    }

    public function addpoints()
    {
        $data = [
            'title' => 'Add Points',
            'groups' => $this->exammodel->GetGroups(),
            'courses' => $this->exammodel->GetCourses(),
            'categories' => $this->exammodel->GetCategories(),
            'touched' => false,
            'id' => '',
            'group' => '',
            'course' => '',
            'category' => '',
            'book' => '',
            'table' => [],
            'group_err' => '',
            'book_err' => '',
            'course_err' => '',
            'category_err' => '',
        ];
        $this->view('exams/addpoints',$data);
        exit();
    }

    public function getstudentspoints()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $gid = intval(trim($_GET['gid']));
            $type = trim($_GET['type']);
            if(empty($gid)){
                exit();
            }
            $output = '';
            $students = $this->exammodel->GetStudentsByGroup($gid,$type);
            foreach($students as $student) {
                $output .= '
                    <tr>
                        <td class="d-none"><input type="text" name="studentsid[]" value="'.$student->ID.'"></td>
                        <td><input type="text" class="table-input w-100" name="names[]" value="'.$student->StudentName.'" readonly></td>
                        <td><input type="text" class="table-input w-100" name="points[]" value="" ></td>
                        <td><input type="text" class="table-input w-100" name="remarks[]" value="" ></td>
                            
                    </tr>
                ';
            }

            echo json_encode($output);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}
