<?php
class Pettycashreceipts extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'petty cash receipt');
        $this->receiptmodel = $this->model('Pettycashreceipt');
    }

    public function index()
    {
        $data = [
            'title' => 'Petty cash receipt',
            'has_datatable' => true,
            'receipts' => $this->receiptmodel->GetReceipts()
        ];
        $this->view('pettycashreceipts/index', $data);
        exit;
    }

    public function add()
    {
        $data = [
            'title' => 'Add Petty Cash Receipt',
            'receiptno' => $this->receiptmodel->GetReceiptNo(),
            'id' => 0,
            'isedit' => false
        ];
        $this->view('pettycashreceipts/add', $data);
        exit;
    }

    public function CreateUpdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $fields = json_decode(file_get_contents('php://input'));
            if(empty($fields)){
                http_response_code(400);
                echo json_encode(['message' => 'Unable to extract submitted data']);
                exit;
            }

            $data = [
                'id' => isset($fields->id) && !empty(trim($fields->id)) ? (int)trim($fields->id) : 0,
                'isedit' => isset($fields->id) ? converttobool($fields->isedit) : false,
                'receiptdate' => isset($fields->receiptdate) && !empty(trim($fields->receiptdate)) ? date('Y-m-d',strtotime(trim($fields->receiptdate))) : null,
                'amount' => isset($fields->amount) && !empty(trim($fields->amount)) ? floatval(trim($fields->amount)) : null,
                'reference' => isset($fields->reference) && !empty(trim($fields->reference)) ? strtolower(trim($fields->reference)) : null,
                'narration' => isset($fields->narration) && !empty(trim($fields->narration)) ? strtolower(trim($fields->narration)) : null,
            ];

            //validate
            if(is_null($data['receiptdate']) || is_null($data['amount']) || is_null($data['reference'])){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }
            if(!$this->receiptmodel->CheckReferenceExists($data['reference'],$data['id'])){
                http_response_code(400);
                echo json_encode(['message' => 'Reference / Cheque no already exists']);
                exit;
            }

            if(!$this->receiptmodel->CreateUpdate($data)){
                http_response_code(500);
                echo json_encode(['message' => 'Something went wrong while saving. Contact system admin']);
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

    public function getnewid()
    {
        $newid = $this->receiptmodel->GetReceiptNo();
        echo json_encode(['success' => true, 'newid' => $newid]);
    }
}