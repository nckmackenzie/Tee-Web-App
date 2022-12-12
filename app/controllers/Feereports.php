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
                'type' => isset($_GET['type']) && !empty(trim($_GET['type'])) ? trim(htmlentities($_GET['type'])) : null,
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime($_GET['sdate'])) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime($_GET['edate'])) : null,
                'results' => []
            ];

            if(is_null($data['sdate']) || is_null($data['edate']) || is_null($data['type'])){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }
            if($data['sdate'] > $data['edate']){
                http_response_code(400);
                echo json_encode(['message' => 'Start date cannot be greater than end date']);
                exit;
            }
            $results = $this->reportmodel->Getfeepayments($data);
            if(empty($results )){
                http_response_code(404);
                echo json_encode(['message' => 'No data found']);
                exit;
            }
            if($data['type'] === 'all'){
                foreach ($results as $result):
                    array_push($data['results'],[
                        'paymentDate' => $result->PaymentDate,
                        'receiptNo' => $result->ReceiptNo,
                        'studentName' => $result->StudentName,
                        'amount' => $result->AmountPaid,
                        'paymentReference' => $result->PaymentReference
                    ]);
                endforeach;
            }else{
                foreach ($results as $result):
                    array_push($data['results'],[
                        'course' => ucwords($result->CourseName),
                        'value' => $result->SumOfValue
                    ]);
                endforeach;
            }
            echo json_encode(['success' => true,'data' => $data['results']]);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function balances()
    {
        checkrights($this->authmodel,'fee balances');
        $data = [
            'title' => 'Fee Balances',
            'has_datatable' => true,
            'semisters' => $this->reportmodel->GetSemisters(),
        ];
        $this->view('feereports/balances', $data);
        exit;
    }

    public function getbalancerpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'semister' => isset($_GET['semister']) && !empty(trim($_GET['semister'])) ? (int)trim($_GET['semister']) : null,
                'results' => []
            ];

            if(is_null($data['semister'])){
                http_response_code(400);
                echo json_encode(['message' => 'Select semister']);
                exit;
            }
            $results = $this->reportmodel->GetSemisterBalances($data['semister']);
            if(empty($results )){
                http_response_code(404);
                echo json_encode(['message' => 'No data found']);
                exit;
            }
            foreach ($results as $result):
                array_push($data['results'],[
                    'studentName' => $result->Student,
                    'openingBal' => $result->BalanceBf,
                    'semisterFees' => $result->SemFees,
                    'amountPaid' => $result->AmountPaid,
                    'balance' => $result->Balance
                ]);
            endforeach;

            echo json_encode(['success' => true, 'results' => $data['results']]);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function graduationfees()
    {
        checkrights($this->authmodel,'graduation fee payments');
        $data = [
            'title' => 'Graduation Fee Payments',
            'has_datatable' => true,
        ];
        $this->view('feereports/graduationfees', $data);
        exit;
    }

    public function graduationfeesrpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);

            $data = [
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
                'results' => []
            ];

            if(is_null($data['sdate']) || is_null($data['edate'])){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }

            $results = $this->reportmodel->GetGraduationFeePayments($data);
            if(empty($results )){
                http_response_code(404);
                echo json_encode(['message' => 'No data found']);
                exit;
            }

            foreach ($results as $result):
                array_push($data['results'],[
                    'paymentDate' => date('d-m-Y',strtotime($result->PaymentDate)),
                    'receiptNo' => $result->ReceiptNo,
                    'studentName' => $result->Student,
                    'amount' => $result->AmountPaid,
                    'paymentReference' => ucwords($result->PayReference)
                ]);
            endforeach;

            echo json_encode(['success' => true,'results' => $data['results']]);

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}