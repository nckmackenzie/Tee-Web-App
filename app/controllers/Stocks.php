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
        $receipts = $this->stockmodel->GetReceiptsOrTransfers('receipts');
        $data = [
            'title' => 'Receipts',
            'has_datatable' => true,
            'receipts' => $receipts
        ];
        $this->view('stocks/receipts',$data);
        exit();
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

    public function transfers()
    {
        $transfers = $this->stockmodel->GetReceiptsOrTransfers('transfers');
        $data = [
            'title' => 'Transfers',
            'has_datatable' => true,
            'transfers' => $transfers,
        ];
        $this->view('stocks/transfers',$data);
        exit();
    }

    public function addtransfer()
    {
        $books = $this->stockmodel->GetBooks();
        $centers = $this->stockmodel->GetCenters();
        $data = [
            'title' => 'Add Transfer',
            'centers' => $centers,
            'books' => $books,
            'touched' => false,
            'id' => '',
            'allowedit' => false,
            'isedit' => false,
            'date' => date('Y-m-d'),
            'error' => '',
            'center' => '',
            'mtn' => '',
            'table' => [],
            'date_err' => '',
            'center_err' => '',
            'mtn_err' => '',
        ];
        $this->view('stocks/addtransfer',$data);
        exit();
    }

    public function createupdatetransfer()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $books = $this->stockmodel->GetBooks();
            $centers = $this->stockmodel->GetCenters();
            $data = [
                'title' => converttobool($_POST['isedit']) ? 'Edit Transfer' : 'Add Transfer',
                'centers' => $centers,
                'books' => $books,
                'touched' => true,
                'id' => trim($_POST['id']),
                'isedit' => converttobool($_POST['isedit']),
                'allowedit' => converttobool($_POST['allowedit']),
                'error' => '',
                'date' => !empty($_POST['date']) ? date('Y-m-d',strtotime($_POST['date'])) : '',
                'center' => !empty($_POST['center']) ? trim($_POST['center']) : '',
                'mtn' => trim($_POST['mtn']),
                'table' => [],
                'booksid' => $_POST['booksid'],
                'booksname' => $_POST['booksname'],
                'qtys' => $_POST['qtys'],
                'date_err' => '',
                'center_err' => '',
                'mtn_err' => '',
            ];

            if(count($data['booksid']) == 0){
                $data['error'] = 'No items added for transfer';
            }else{
                for ($i=0; $i < count($data['booksid']); $i++) { 
                    array_push($data['table'],[
                        'pid' => $data['booksid'][$i],
                        'book' => $data['booksname'][$i],
                        'qty' => $data['qtys'][$i],
                    ]);
                }
            }

            if(empty($data['date'])){
                $data['date_err'] = 'Select transfer date';
            }else{
                if($data['date'] > date('Y-m-d')){
                    $data['date_err'] = 'Invalid date selected';
                }
            }

            if(empty($data['center'])){
                $data['center_err'] = 'Select center transfering to';
            }

            if(empty($data['mtn'])){
                $data['mtn_err'] = 'Please enter MTN No';
            }

            if(!empty($data['date_err']) || !empty($data['center_err']) || !empty($data['mtn_err']) 
               || !empty($data['error'])){
                $this->view('stocks/addtransfer',$data);
                exit();
            }

            if(!$this->stockmodel->CreateUpdateTransfer($data)){
                flash('transfer_msg',null,'Transfer not created. Retry or contact admin',flashclass('alert','danger'));
                redirect('stocks/transfers');
                exit();
            }

            flash('transfer_toast_msg',null,'Transfer created.',flashclass('toast','success'));
            redirect('stocks/transfers');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
    public function transferedit($id)
    {
        $books = $this->stockmodel->GetBooks();
        $centers = $this->stockmodel->GetCenters();
        $transfereheader = $this->stockmodel->GetTransfereHeader($id);
        $transferdetails = $this->stockmodel->GetTransferDetails($id);
        $data = [
            'title' => 'Edit Transfer',
            'centers' => $centers,
            'books' => $books,
            'touched' => false,
            'id' => $transfereheader->ID,
            'isedit' => true,
            'allowedit' => !converttobool($transfereheader->Received),
            'date' => $transfereheader->TransferDate,
            'center' => $transfereheader->ToCenter,
            'mtn' => $transfereheader->MtnNo,
            'table' => [],
            'date_err' => '',
            'center_err' => '',
            'mtn_err' => '',
        ];
        
        foreach ($transferdetails as $detail){
            array_push($data['table'],[
                'pid' => $detail->BookId,
                'book' => $detail->Title,
                'qty' => $detail->Qty
            ]);
        }
        $this->view('stocks/addtransfer',$data);
        exit();
    }
}