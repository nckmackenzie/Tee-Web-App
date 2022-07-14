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

    public function createupdatereceipt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'type' => trim($_POST['receipttype']),
                'date' => !empty($_POST['date']) ? date('Y-m-d',strtotime($_POST['date'])) : date('Y-m-d'),
                'mtn' => !empty($_POST['mtn']) ? trim($_POST['mtn']) : '',
                'reference' => !empty($_POST['reference']) ? trim($_POST['reference']) : '',
                'booksid' => $_POST['booksid'],
                'booksname' => $_POST['booksname'],
                'qtys' => $_POST['qtys'],
            ];

            if(!$this->stockmodel->CreateUpdateReceipt($data)){
                flash('receipt_msg',null,'Receipt not created. Retry or contact admin',flashclass('alert','danger'));
                redirect('stocks/receipts');
                exit();
            }

            flash('receipt_toast_msg',null,'Receipt created.',flashclass('toast','success'));
            redirect('stocks/receipts');
            exit();
        }
    }
}