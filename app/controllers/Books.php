<?php
class Books extends Controller
{
    public function __construct()
    {
        adminonly($_SESSION['userid'],$_SESSION['usertypeid']);
        $this->bookmodel = $this->model('Book');
    }

    public function index(){
        $items = $this->bookmodel->GetItems();
        $data = [
            'title' => 'Books',
            'has_datatable' => true,
            // 'items' => $items
        ];
        $this->view('books/index',$data);
    }
}