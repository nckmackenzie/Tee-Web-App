<?php

class Stockreports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])):
            redirect('auth');
            exit();
        endif;

        $this->reportmodel = $this->model('Stockreport');
    }

    public function receipts()
    {
        $data = [
            'title' => 'Receipts Report',
            'has_datatable' => true
        ];
        $this->view('stockreports/receipts', $data);
        exit;
    }

    public function receiptsrpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'sdate' => date('Y-m-d',strtotime($_GET['sdate'])),
                'edate' => date('Y-m-d',strtotime($_GET['edate'])),
                'type' => trim($_GET['type']),
                'results' => []
            ];

            $results = $this->reportmodel->GetReceipts($data);
            foreach($results as $result):
                array_push($data['results'],[
                    'receiptDate' => $result->ReceiptDate,
                    'grnNo' => $result->GrnNo,
                    'receiptType' => $result->ReceiptType,
                    'fromCenter' => strtoupper($result->Center),
                    'book' => strtoupper($result->Title),
                    'qty' => $result->Qty
                ]);
            endforeach;

            echo json_encode($data['results']);
        }else{
            redirect('auth/forbidden');
            exit;;
        }
    }
}