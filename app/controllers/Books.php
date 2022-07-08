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
            'title' => 'Books',
            'has_datatable' => true,
            'books' => $books
        ];
        $this->view('books/index',$data);
    }

    public function add()
    {
        $data = [
            'title' => 'Books',
            'has_datatable' => true,
            'isedit' => false,
            'touched' => false,
            'id' => '',
            'name' => '',
            'code' => '',
            'author' => '',
            'publisher' => '',
            'name_err' => '',
            'code_err' => '',
            'author_err' => '',
            'publisher_err' => '',
        ];
        $this->view('books/add',$data);
    }
}