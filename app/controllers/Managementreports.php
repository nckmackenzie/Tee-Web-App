<?php
class Managementreports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        $this->reportmodel = $this->model('Managementreport');
    }

    public function index()
    {
        $data = ['title' => 'Page not found'];
        $this->view('auth/notfound',$data);
        exit;
    }

    public function incomestatement()
    {
        $data = ['title' => 'Income statement','has_datatable' => true];
        $this->view('managementreports/incomestatement',$data);
        exit;
    }

    public function incomestatementvalues()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
            ];

            if(is_null($data['sdate']) || is_null($data['edate'])){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }
            if($data['sdate'] > $data['edate']){
                http_response_code(400);
                echo json_encode(['message' => 'Start date cannot be greater than end date']);
                exit;
            }

            $values = $this->reportmodel->GetIncomeStatementValues($data);
            $results = [
                'fee' => floatval($values[0]),
                'gradfee' => floatval($values[1]),
                'generalExpenses' => floatval($values[2]),
                'purchases' => floatval($values[3]),
            ];

            echo json_encode(['success' => true, 'results' => $results]);
            exit;

        }
        else{
            redirect('auth/forbidden');
            exit;
        }
    }
}