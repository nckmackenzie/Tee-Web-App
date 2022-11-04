<?php
class Pettycashreports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        $this->reportmodel = $this->model('Pettycashreport');
    }

    public function index()
    {
        $data = ['title' => 'Page not found'];
        $this->view('auth/notfound', $data);
        exit;
    }

    public function utilization()
    {
        $data = ['title' => 'Petty cash utilization','has_datatable' => true];
        $this->view('pettycashreports/utilization', $data);
        exit;
    }

    public function utilizationrpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $_GET = filter_input_array(INPUT_GET, FILTER_UNSAFE_RAW);
            $data = [
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
            ];
            //validate
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

            $results = $this->reportmodel->GetPettyCashReport($data);
            $utilizations = [];
            foreach($results as $result) {
                array_push($utilizations,[
                    'date' => date('d-m-Y',strtotime($result->TransactionDate)),
                    'reference' => strtoupper($result->Reference),
                    'debit' => floatval($result->Debit),
                    'credit' => floatval($result->Credit),
                    'narration'=> strtoupper($result->Narration),
                ]);
            }
            echo json_encode(['success' => true,'results' => $utilizations]);
            exit;
        }
        else
        {
            redirect('auth/forbidden');
            exit;
        }
    }
}