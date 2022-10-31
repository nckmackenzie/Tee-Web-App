<?php
class Bankings extends Controller 
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        $this->bankingmodel = $this->model('Banking');
    }

    public function index()
    {
        $data = ['title' => 'Page not found'];
        $this->view('bankings/index',$data);
        exit;
    }

    public function clear()
    {
        checkrights($this->authmodel,'clear bankings');
        $data= [
            'title' => 'Clear transactions'
        ];
        $this->view('bankings/clear',$data);
        exit;
    }

    //fetch transactions
    public function fetchtransactions()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'type' => isset($_GET['type']) && !empty(trim($_GET['type'])) ? trim($_GET['type']) : null,
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
                'results' => []
            ];
            //validation
            if(is_null($data['type']) || is_null($data['sdate']) || is_null($data['edate'])){
                http_response_code(400);
                echo json_encode(['message' => 'Fill all required fields']);
                exit;
            }
            if($data['type'] !== 'bankings' && $data['type'] !== 'mpesa'){
                http_response_code(400);
                echo json_encode(['message' => 'Invalid transaction type']);
                exit;
            }
            if($data['sdate'] > $data['edate']){
                http_response_code(400);
                echo json_encode(['message' => 'Start date cannot be greater than end date']);
                exit;
            }
            $transactions = $this->bankingmodel->GetTransactions($data);
            if(empty($transactions)){
                http_response_code(404);
                echo json_encode(['message' => 'No transactions found']);
                exit;
            }
            foreach($transactions as $transaction){
                array_push($data['results'],[
                    'id' => $transaction->ID,
                    'transactionDate' => date('d-m-Y',strtotime($transaction->TransactionDate)),
                    'amount' => $transaction->Amount,
                    'type' => ucwords($transaction->TransactionType),
                    'reference' => ucwords($transaction->Reference)
                ]);
            }
            echo json_encode(['success' => true, 'results' => $data['results']]);
        }
        else
        {
            redirect('auth/forbidden');
            exit;
        }
    }

    public function cleartransactions()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $fields = json_decode(file_get_contents('php://input'));
            $bankings = $fields->details;
            if(empty($bankings)){
                http_response_code(400);
                echo json_encode(['message' => 'No transactions to clear']);
                exit;
            }
            if(count($bankings) === 0){
                http_response_code(400);
                echo json_encode(['message' => 'No transactions to clear']);
                exit;
            }
            $dateerror = 0;
            foreach($bankings as $banking){
                if(empty($banking->clearDate)){
                    $dateerror ++;
                }
            }
            if((int)$dateerror > 0){
                http_response_code(400);
                echo json_encode(['message' => $dateerror .' transactions missing clear date']);
                exit;
            }
            foreach($bankings as $banking){
                $date = date('Y-m-d',strtotime(trim($banking->clearDate)));
                if($date > date('Y-m-d')){
                    $dateerror ++;
                }
            }
            if((int)$dateerror > 0){
                http_response_code(400);
                echo json_encode(['message' => $dateerror .' clear date greater than current date']);
                exit;
            }
            foreach($bankings as $banking){
                $cdate = date('Y-m-d',strtotime(trim($banking->clearDate)));
                $tdate = date('Y-m-d',strtotime(trim($banking->tdate)));
                if($cdate < $tdate){
                    $dateerror ++;
                }
            }
            if((int)$dateerror > 0){
                http_response_code(400);
                echo json_encode(['message' => $dateerror .' clear date earlier than transaction date']);
                exit;
            }

            if(!$this->bankingmodel->ClearTransactions($bankings)){
                http_response_code(500);
                echo json_encode(['message' => 'Unable to clear transactions. Retry or contact admin']);
                exit;
            }
           
            echo json_encode(['success' => true]);
            exit;
        }
        else
        {
            redirect('auth/forbidden');
            exit;
        }
    }

    //banking recon
    public function recon()
    {
        checkrights($this->authmodel,'bank recon');
        $data = [
            'title' =>  'Bank reconcilliation',
            'has_datatable' => true,
        ];
        $this->view('bankings/recon', $data);
        exit;
    }

    public function getbankingvalues()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
                'values' => []
            ];
            if(is_null($data['sdate']) || is_null($data['edate'])){
                http_response_code(400);
                echo json_encode(['message' => 'Fill all required fields']);
                exit;
            }
            if($data['sdate'] > $data['edate']){
                http_response_code(400);
                echo json_encode(['message' => 'Start date cannot be greater than end date']);
                exit;
            }
            $values = $this->bankingmodel->GetValues($data);
            $data['values']['cleareddeposits'] = floatval($values[0]);
            $data['values']['clearedwithdrawals'] = floatval($values[1]);
            $data['values']['uncleareddeposits'] = floatval($values[2]);
            $data['values']['unclearedwithdrawals'] = floatval($values[3]);

            echo json_encode(['success' => true, 'values' => $data['values']]);
        }
        else{
            redirect('auth/forbidden');
            exit;
        }
    }

    public function uncleared()
    {
        $data = [
            'title' => 'Uncleared transactions',
            'has_datatable' => true
        ];
        $this->view('bankings/uncleared',$data);
        exit;
    }

    public function getuncleared()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'type' => isset($_GET['type']) && !empty(trim($_GET['type'])) ? strtolower(trim($_GET['type'])) : null,
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
                'results' => []
            ];
            if(is_null($data['sdate']) || is_null($data['edate']) || is_null($data['type'])){
                http_response_code(400);
                echo json_encode(['message' => 'Fill all required fields']);
                exit;
            }
            if($data['type'] !== 'deposits' && $data['type'] !== 'withdrawals'){
                http_response_code(400);
                echo json_encode(['message' => 'Invalid report type']);
                exit;
            }
            if($data['sdate'] > $data['edate']){
                http_response_code(400);
                echo json_encode(['message' => 'Start date cannot be greater than end date']);
                exit;
            }

            $results = $this->bankingmodel->GetUnclearedReports($data);
            if(empty($results) || count($results) == 0) {
                http_response_code(404);
                echo json_encode(['message' => 'No data found']);
                exit;
            }

            foreach ($results as $result){
                array_push($data['results'],[
                    'transactionDate' => date('d-m-Y',strtotime($result->TransactionDate)),
                    'amount' => floatval($result->Amount),
                    'reference' => ucwords($result->Reference),
                    'narration' => ucwords($result->Narration)
                ]);
            }
            
            echo json_encode(['success' => true, 'results' => $data['results']]);
        }
        else{
            redirect('auth/forbidden');
            exit;
        }
    }
}