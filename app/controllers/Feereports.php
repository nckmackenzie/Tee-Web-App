<?php
class Feereports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        $this->reportmodel = $this->model('Feereport');
    }

    public function index()
    {
        $data = ['title' => 'Page not found!'];
        $this->view('auth/notfound',$data);
        exit;
    }

    public function feepayments()
    {
        checkrights($this->authmodel,'fee payments');
        $data = [
            'title' => 'Fee payment report',
            'has_datatable' => true
        ];
        $this->view('feereports/feepayments',$data);
        exit;
    }

    public function feepaymentsrpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime($_GET['sdate'])) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime($_GET['edate'])) : null,
                'results' => []
            ];

            if(is_null($data['sdate']) || is_null($data['edate'])){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }
            $results = $this->reportmodel->Getfeepayments($data);
            if(empty($results )){
                http_response_code(404);
                echo json_encode(['message' => 'No data found']);
                exit;
            }
            foreach ($results as $result):
                array_push($data['results'],[
                    'paymentDate' => $result->PaymentDate,
                    'receiptNo' => $result->ReceiptNo,
                    'studentName' => $result->StudentName,
                    'amount' => $result->AmountPaid,
                    'paymentReference' => $result->PaymentReference
                ]);
            endforeach;
            echo json_encode($data['results']);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}