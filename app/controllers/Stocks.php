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

    public function getprice()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'book' => $_GET['book'],
                'date' => $_GET['rdate']
            ];
            $price = $this->stockmodel->GetPrice($data['book'],$data['date']);
            echo json_encode(floatval($price));
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}