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
        // $students = $this->salemodel->GetStudents();
        $salesid = $this->salemodel->GetSaleId();
        $books = $this->salemodel->GetBooks();
        $data = [
            'title' => 'Add sales',
            // 'students' =>  $students,
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

    function fetchstudentorgroup($type){
        $studentsorgroups = $this->salemodel->GetStudentsOrGroups($type);
        return $studentsorgroups;
    }

    public function getstudentorgroup()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $saletype = trim($_GET['type']);
            //validate
            if(empty($saletype) || !isset($saletype)){
                flash('sale_msg',null,'Invalid request',flashclass('alert', 'danger'));
                redirect('sales');
                exit();
            }

            $studentsorgroups = $this->fetchstudentorgroup($saletype);
            $output = '<option value="">Select '.ucwords($saletype).'</option>';
            foreach($studentsorgroups as $studentorgroup){
                $output .= '<option value="'.$studentorgroup->ID.'">'.$studentorgroup->CriteriaName.'</option>';
            }

            echo json_encode($output);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}