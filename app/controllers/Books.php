<?php
class Books extends Controller
{
    public function __construct()
    {
        adminonly($_SESSION['userid'],$_SESSION['usertypeid']);
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
        $data = [
            'title' => 'Add Book',
            'isedit' => false,
            'touched' => false,
            'id' => '',
            'name' => '',
            'code' => '',
            'author' => '',
            'publisher' => '',
            'openingbal' => '',
            'asat' => '',
            'name_err' => '',
            'code_err' => '',
            'author_err' => '',
            'publisher_err' => '',
            'openingbal_err' => '',
            'asat_err' => '',
        ];
        $this->view('books/add',$data);
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => converttobool($_POST['isedit']) ? 'Edit Book' : 'Add Book',
                'isedit' => converttobool($_POST['isedit']),
                'touched' => true,
                'id' => trim($_POST['id']),
                'name' => strtolower(trim($_POST['name'])),
                'code' => strtolower(trim($_POST['code'])),
                'author' => strtolower(trim($_POST['author'])),
                'publisher' => strtolower(trim($_POST['publisher'])),
                'openingbal' => trim($_POST['openingbal']),
                'asat' => !empty($_POST['asat']) ? date("Y-m-d", strtotime($_POST['asat'])) : date('Y-m-d'),
                'name_err' => '',
                'code_err' => '',
                'author_err' => '',
                'publisher_err' => '',
                'openingbal_err' => '',
                'asat_err' => '',
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

            if(empty($data['openingbal'])){
                $data['openingbal_err'] = 'Opening balance is required';
            }

            if(empty($data['asat'])){
                $data['asat_err'] = 'Date of opening balance is required';
            }else{
                if($data['asat'] > date('Y-m-d')){
                    $data['asat_err'] = 'Invalid date of opening balance';
                }
            }

            if(!empty($data['name_err']) || !empty($data['code_err']) || !empty($data['asat_err']) ||
               !empty($data['openingbal_err'])){
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
}