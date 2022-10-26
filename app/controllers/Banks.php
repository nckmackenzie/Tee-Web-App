<?php

class Banks extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'banks');
        $this->bankmodel = $this->model('Bank');
    }

    public function index()
    {
        $data = [
            'title' => 'Banks',
            'has_datatable' => true,
            'banks' => $this->bankmodel->GetBanks()
        ];
        $this->view('banks/index',$data);
        exit;
    }
    //add bank
    public function add()
    {
        $data = [
            'title' => 'Add bank',
            'id' => 0,
            'isedit' => false,
            'bankname' => '',
            'accountno' => '',
        ];
        $this->view('banks/add',$data);
        exit;
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $fields = json_decode(file_get_contents('php://input'));
            $data = [
                'id' => isset($fields->id) && !empty(trim($fields->id)) ? (int)trim($fields->id) : null,
                'isedit' => converttobool($fields->isedit),
                'bankname' => isset($fields->bankname) && !empty(trim($fields->bankname)) ? strtolower(trim($fields->bankname)) : null,
                'accountno' => isset($fields->accountno) && !empty(trim($fields->accountno)) ? trim($fields->accountno) : null,
                'openingbal' => converttobool($fields->isedit) ? null : (isset($fields->openingbal) && !empty(trim($fields->openingbal)) ? floatval(trim($fields->openingbal)) : null),
                'asof' => converttobool($fields->isedit) ? null : (isset($fields->asof) && !empty(trim($fields->asof)) ? date('Y-m-d',strtotime(trim($fields->asof))) : null),
            ];

            //validate
            if(is_null($data['bankname'])){
                http_response_code(400);
                echo json_encode(['message' => 'Enter bank name']);
                exit;
            }
            if(!$data['isedit'] && $data['openingbal'] > 0 && is_null($data['asof'])){
                http_response_code(400);
                echo json_encode(['message' => 'Select date of balance']);
                exit;
            }
            if(!$data['isedit'] && $data['asof'] > date('Y-m-d')){
                http_response_code(400);
                echo json_encode(['message' => 'Invalid date of balance']);
                exit;
            }

            if(!$this->bankmodel->CreateUpdate($data)){
                http_response_code(500);
                echo json_encode(['message' => 'Unable to save bank. Retry or contact admin']);
                exit;
            }

            echo json_encode(['success' => true]);
            exit;
        }
        else{
            redirect('auth/forbidden');
            exit;
        }
    }
}