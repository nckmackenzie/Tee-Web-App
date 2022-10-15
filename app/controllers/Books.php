<?php
class Books extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'books');
        $this->bookmodel = $this->model('Book');
    }

    public function index(){
        $books = $this->bookmodel->GetBooks();
        $data = [
            'title' => 'Books List',
            'has_datatable' => true,
            'books' => $books
        ];
        $this->view('books/index',$data);
    }

    public function add()
    {
        $courses = $this->bookmodel->GetCourses();
        $data = [
            'title' => 'Add Book',
            'courses' => $courses,
            'glaccounts' => $this->bookmodel->GetGLAccounts(),
            'isedit' => false,
            'touched' => false,
            'id' => '',
            'name' => '',
            'code' => '',
            'author' => '',
            'course' => '',
            'publisher' => '',
            'openingbal' => '',
            'glaccount' => '',
            'asat' => '',
            'allowedit' => false,
            'active' => true,
            'name_err' => '',
            'code_err' => '',
            'author_err' => '',
            'publisher_err' => '',
            'openingbal_err' => '',
            'asat_err' => '',
            'course_err' => '',
            'glaccount_err' => '',
        ];
        $this->view('books/add',$data);
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $courses = $this->bookmodel->GetCourses();
            $data = [
                'title' => converttobool($_POST['isedit']) ? 'Edit Book' : 'Add Book',
                'courses' => $courses,
                'glaccounts' => $this->bookmodel->GetGLAccounts(),
                'isedit' => converttobool($_POST['isedit']),
                'touched' => true,
                'id' => trim($_POST['id']),
                'course' => !empty($_POST['course']) ? trim($_POST['course']) : '',
                'name' => strtolower(trim($_POST['name'])),
                'code' => strtolower(trim($_POST['code'])),
                'author' => strtolower(trim($_POST['author'])),
                'publisher' => strtolower(trim($_POST['publisher'])),
                'openingbal' => (converttobool($_POST['isedit']) && converttobool($_POST['allowedit'])) ? trim($_POST['openingbal']) : trim($_POST['openingbal']),
                'asat' => !empty($_POST['asat']) ? date("Y-m-d", strtotime($_POST['asat'])) : date('Y-m-d'),
                'active' => converttobool($_POST['isedit']) ? (isset($_POST['active']) ? true : false) : true,
                'allowedit' => converttobool($_POST['allowedit']),
                'glaccount' => !empty($_POST['glaccount']) ? trim($_POST['glaccount']) : '',
                'glaccount_err' => '',
                'name_err' => '',
                'code_err' => '',
                'author_err' => '',
                'publisher_err' => '',
                'openingbal_err' => '',
                'asat_err' => '',
                'course_err' => '',
            ];

            if(empty($data['name'])){
                $data['name_err'] = 'Book name is required';
            }

            if(empty($data['code'])){
                $data['code_err'] = 'Book code is required';
            }else{
                if(!$this->bookmodel->CheckAvailability($data['id'],$data['code'])){
                    $data['code_err'] = 'Book code exists';
                }
            }

            if(empty($data['course'])){
                $data['course_err'] = 'Course not selected';
            }

            if(empty($data['openingbal']) && $data['allowedit']){
                $data['openingbal_err'] = 'Opening balance is required';
            }

            if(empty($data['asat'])){
                $data['asat_err'] = 'Date of opening balance is required';
            }else{
                if($data['asat'] > date('Y-m-d')){
                    $data['asat_err'] = 'Invalid date of opening balance';
                }
            }

            if(empty($data['glaccount'])){
                $data['glaccount_err'] = 'Select G/L account';
            }

            if(!empty($data['name_err']) || !empty($data['code_err']) || !empty($data['asat_err']) ||
               !empty($data['openingbal_err']) || !empty($data['glaccount_err'])){
                $this->view('books/add',$data);
                exit();
            }

            if(!$this->bookmodel->CreateUpdate($data)){
                flash('book_msg',null,'Book not created.Retry and contact admin if it fails',flashclass('alert','danger'));
                redirect('books');
                exit();
            }

            flash('book_flash_msg',null,'Saved successfully.',flashclass('toast','success'));
            redirect('books');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        $book = $this->bookmodel->GetBook($id);
        $courses = $this->bookmodel->GetCourses();
        $data = [
            'title' => 'Edit Book',
            'courses' => $courses,
            'glaccounts' => $this->bookmodel->GetGLAccounts(),
            'isedit' => true,
            'touched' => false,
            'id' => (int)$book->ID,
            'name' => $book->Title,
            'code' => $book->BookCode,
            'author' => $book->Author,
            'publisher' => $book->Publisher,
            'openingbal' => $book->OpeningBal,
            'course' => $book->CourseId,
            'allowedit' => converttobool($book->AllowBalEdit),
            'active' => converttobool($book->Active),
            'asat' => $book->AsAtDate,
            'glaccount' => $book->GlAccountId,
            'name_err' => '',
            'code_err' => '',
            'author_err' => '',
            'publisher_err' => '',
            'openingbal_err' => '',
            'asat_err' => '',
            'course_err' => '',
            'glaccount_err' => ''
        ];
        $this->view('books/add',$data);
    }

    public function delete()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            
            if(empty($id)){
                flash('book_msg',null,'Unable to get selected book!',flashclass('alert','danger'));
                redirect('books');
                exit();
            }

            if((int)$this->bookmodel->GetStock($id) > 0){
                flash('book_msg',null,'Cannot delete as book is still in stock!',flashclass('alert','danger'));
                redirect('books');
                exit();
            }   
               
            if(!$this->bookmodel->Delete($id)){
                flash('book_msg',null,'Book not deleted.Retry and contact admin if it fails',flashclass('alert','danger'));
                redirect('books');
                exit();
            }

            flash('book_flash_msg',null,'Deleted successfully.',flashclass('toast','success'));
            redirect('books');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}