<?php
class Prices extends Controller
{
    public function __construct(){
        adminonly($_SESSION['userid'],$_SESSION['usertypeid']);
        $this->pricemodel = $this->model('Price');
    }

    public function index()
    {
        $prices = $this->pricemodel->GetPrices();
        $data = [
            'title' => 'Prices',
            'has_datatable' => true,
            'prices' => $prices
        ];
        $this->view('prices/index',$data);
    }

    public function add()
    {
        $books = $this->pricemodel->GetBooks();
        $data = [
            'title' => 'Add Price',
            'books' => $books,
            'id' => '',
            'touched' => false,
            'isedit' => false,
            'bookid' => '',
            'startdate' => '',
            'enddate' => '',
            'price' => '',
            'bookid_err' => '',
            'startdate_err' => '',
            'enddate_err' => '',
            'price_err' => '',
        ];
        $this->view('prices/add',$data);
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $books = $this->pricemodel->GetBooks();
            $data = [
                'title' => converttobool($_POST['isedit']) ? 'Edit Price' : 'Add Price',
                'books' => $books,
                'id' => trim($_POST['id']),
                'touched' => true,
                'isedit' => converttobool($_POST['isedit']),
                'bookid' => !empty($_POST['bookid']) ? trim($_POST['bookid']) : '',
                'startdate' => !empty($_POST['startdate']) ? date("Y-m-d", strtotime($_POST['startdate'])) : '',
                'enddate' => !empty($_POST['enddate']) ? date("Y-m-d", strtotime($_POST['enddate'])) : '',
                'price' => trim($_POST['price']),
                'bookid_err' => '',
                'startdate_err' => '',
                'enddate_err' => '',
                'price_err' => '',
            ];

            //validation
            if(empty($data['bookid'])){
                $data['bookid_err'] = 'Select book';
            }

            if(empty($data['price'])){
                $data['price_err'] = 'Book price is required';
            }

            if(empty($data['startdate'])){
                $data['startdate_err'] = 'Select starting date';
            }

            if(empty($data['enddate'])){
                $data['enddate_err'] = 'Select ending date';
            }

            if(!empty($data['startdate']) && !empty($data['enddate']) && $data['startdate'] > $data['enddate']){
                $data['startdate_err'] = 'Starting date cannot be greater than end date';
            }else{

            }

            //errors found in validation
            if(!empty($data['bookid_err']) || !empty($data['price_err']) || !empty($data['startdate_err']) 
               || !empty($data['enddate_err'])){
                $this->view('prices/add',$data);
                exit();
            }
  
            if(!$this->pricemodel->CreateUpdate($data)){
                flash('price_msg',null,'Unable to save.Retry or contact admin',flashclass('alert','danger'));
                redirect('prices');
                exit();
            }

            flash('price_toast_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('prices');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        $price = $this->pricemodel->GetPrice($id);
        $books = $this->pricemodel->GetBooks();
        $data = [
            'title' => 'Edit Price',
            'books' => $books,
            'id' => (int)$price->ID,
            'touched' => false,
            'isedit' => true,
            'bookid' => (int)$price->BookId,
            'startdate' => $price->StartDate,
            'enddate' => $price->EndDate,
            'price' => $price->Price,
            'bookid_err' => '',
            'startdate_err' => '',
            'enddate_err' => '',
            'price_err' => '',
        ];
        $this->view('prices/add',$data);
    }
}