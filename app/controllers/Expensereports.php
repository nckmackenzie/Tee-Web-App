<?php
class Expensereports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'expenses report');
        $this->reusemodel = $this->model('Reusable');
        $this->reportmodel = $this->model('Expensereport');
    }

    public function index()
    {
        $data = [
            'title' => 'Expenses Report',
            'has_datatable' => true,
            'accounts' => $this->reusemodel->GetGlAccountsByType(2)
        ];
        $this->view('expensereports/index',$data);
        exit;
    }

    public function expenserpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'type' => isset($_GET['type']) && !empty(trim($_GET['type'])) ? strtolower(trim($_GET['type'])) : null,
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
                'account' => isset($_GET['account']) && !empty(trim($_GET['account'])) ? (int)trim($_GET['type']) : '',
            ];
            //validate
            if(is_null($data['type']) || is_null($data['sdate']) || is_null($data['edate'])){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }
            if($data['sdate'] > $data['edate']){
                http_response_code(400);
                echo json_encode(['message' => 'Start date cannot be greater than end date']);
                exit;
            }
            if($data['type'] === 'byaccount' && empty($data['account'])){
                http_response_code(400);
                echo json_encode(['message' => 'Select account']);
                exit;
            }
            $expenses = $this->reportmodel->GetExpenseReport($data);
            if(empty($expenses)){
                $results = [
                    'expenseDate' => '',
                    'voucherNo' => '',
                    'account' => '',
                    'amount' => '',
                    'reference' => '',
                    'narration' => ''
                ];
                echo json_encode(['success' => true, 'results' => $results]);
                exit;
            }
            $results = [];
            foreach($expenses as $expense)
            {
                array_push($results,[
                    'expenseDate' => date('d-m-Y',strtotime($expense->ExpenseDate)),
                    'voucherNo' => strtoupper($expense->VoucherNo),
                    'account' => strtoupper($expense->AccountName),
                    'amount' => $expense->Amount,
                    'reference' => strtoupper($expense->PaymentReference),
                    'narration' => strtoupper($expense->Narration)
                ]);
            }

            echo json_encode(['success' => true, 'results' => $results]);
            exit;
        }
        else{
            redirect('auth/forbidden');
            exit;
        }
    }
}