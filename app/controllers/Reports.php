<?php
class Reports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])):
            redirect('auth');
            exit();
        endif;
        $this->authmodel = $this->model('Auths');
        $this->reportmodel = $this->model('Report');
    }

    public function feepayments()
    {
        checkrights($this->authmodel,'fee payments');
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
        checkrights($this->authmodel,'sales reports');
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
                'type' => isset($_GET['type']) && !empty(trim($_GET['type'])) ? trim($_GET['type']) : 'all',
                'criteria' => isset($_GET['criteria']) && !empty(trim($_GET['criteria'])) ? (int)trim($_GET['criteria']) : null,
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
                'results' => []
            ];
            //validation
            if(is_null($data['sdate']) || is_null($data['edate'])){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }

            if($data['type'] === 'bycenter' && is_null($data['criteria'])){
                http_response_code(400);
                echo json_encode(['message' => 'Select criteria']);
                exit;
            }
            $results = $this->reportmodel->GetSalesReport($data);
            if($data['type'] !== 'bycourse') :
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
            else : 
                foreach($results as $result) :
                    array_push($data['results'],[
                        'course' => ucwords($result->CourseName),
                        'value' => $result->SumOfValue
                    ]);
                endforeach;
            endif;
            echo json_encode($data['results']);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function getsalesreportcriteria()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $type = isset($_GET['type']) && !empty(trim($_GET['type'])) ? trim(htmlentities($_GET['type'])) : null;
            $output = '<option value="" selected disabled>Select '.substr($type,2).'</option>';
            foreach($this->reportmodel->GetSalesCriterias($type) as $criteria)
            {
                $output .= '<option value="'.$criteria->ID.'">'.$criteria->CriteriaName.'</option>';
            }
            echo json_encode($output);
            exit;
        }
        else
        {
            redirect('auth/forbidden');
            exit();
        }
    }
}
