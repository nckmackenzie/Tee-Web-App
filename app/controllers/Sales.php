<?php
class Sales extends Controller
{
    public function __construct()
    {
        if(!is_authenticated($_SESSION['userid'])){
            redirect('auth/login');
            exit();
        }
        $this->salemodel = $this->model('Sale');
    }

    public function index()
    {
        $sales = $this->salemodel->GetSales();
        $data = [
            'title' => 'Sales',
            'has_datatable' => true,
            'sales' => $sales
        ];
        $this->view('sales/index',$data);
        exit();
    }

    public function add()
    {
        $students = $this->salemodel->GetStudents();
        $salesid = $this->salemodel->GetSaleId();
        $books = $this->salemodel->GetBooks();
        $data = [
            'title' => 'Add sales',
            'students' =>  $students,
            'salesid' => $salesid,
            'books' =>  $books,
            'touched' => false,
            'isedit' => false,
            'id' => '',
            'sdate' => date('Y-m-d'),
            'student' => '',
            'subtotal' => '',
            'discount' => '',
            'net' => '',
            'paid' => '',
            'balance' => '',
            'table' => [],
            'sdate' => date('Y-m-d'),
            'student_err' => '',
            'subtotal_err' => '',
            'discount_err' => '',
            'net_err' => '',
            'paid_err' => '',
            'balance_err' => '',
        ];
        $this->view('sales/add',$data);
        exit();
    }

    public function getstockandrate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $bookid = intval(trim($_GET['bookid']));
            $date = date('Y-m-d',strtotime(trim($_GET['date'])));
            $rate_stock = $this->salemodel->GetStockAndRate($date,$bookid);
            $result = ['rate' => $rate_stock->Rate,'stock' => $rate_stock->Stock];
            echo json_encode($result);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}