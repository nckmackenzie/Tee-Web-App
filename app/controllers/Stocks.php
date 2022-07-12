<?php
class Stocks extends Controller
{
    public function __construct()
    {
        if(!is_authenticated($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->stockmodel = $this->model('Stock');
    }

    public function receipts()
    {
        $receipts = $this->stockmodel->GetReceipts();
        $data = [
            'title' => 'Receipts',
            'has_datatable' => true,
            'receipts' => $receipts
        ];
        $this->view('stocks/receipts',$data);
    }

    public function addreceipt()
    {
        $books = $this->stockmodel->GetBooks();
        $data = [
            'title' => 'Add Receipt',
            'books' => $books,
            'date' => date('Y-m-d'),
            'type' => 'grn'
        ];
        $this->view('stocks/addreceipt',$data);
    }
}