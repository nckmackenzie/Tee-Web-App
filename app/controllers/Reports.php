<?php
class Reports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])):
            redirect('auth');
            exit();
        endif;

        $this->reportmodel = $this->model('Report');
    }

    public function feepayments()
    {
        $data = [
            'title' => 'Fee Payment Report',
            'has_datatable' => true,
        ];
        $this->view('reports/feepayments',$data);
        exit;
    }
    public function feepaymentsrpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'sdate' => date('Y-m-d',strtotime($_GET['sdate'])),
                'edate' => date('Y-m-d',strtotime($_GET['edate'])),
                'results' => []
            ];
            $results = $this->reportmodel->Getfeepayments($data);
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

    public function salesreport()
    {
        $data = [
            'title' => 'Sales Report',
            'has_datatable' => true
        ];
        $this->view('reports/salesreport', $data);
        exit;
    }

    public function salesrpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'sdate' => date('Y-m-d',strtotime($_GET['sdate'])),
                'edate' => date('Y-m-d',strtotime($_GET['edate'])),
                'results' => []
            ];
            $results = $this->reportmodel->GetSalesReport($data);
            foreach ($results as $result):
                array_push($data['results'],[
                    'saleId' => $result->SalesID,
                    'salesDate' => $result->SalesDate,
                    'soldTo' => $result->SoldTo,
                    'subTotal' => $result->SubTotal,
                    'discount' => $result->Discount,
                    'netAmount' => $result->NetAmount,
                    'reference' => $result->Reference,
                ]);
            endforeach;
            echo json_encode($data['results']);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}
