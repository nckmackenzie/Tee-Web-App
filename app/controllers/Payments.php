<?php

class Payments extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'supplier payments');
        $this->paymentmodel = $this->model('Payment');
    }

    public function index()
    {
        $data= [
            'title' => 'Supplier payments',
            'has_datatable' => true,
            'payments' => $this->paymentmodel->GetPayments(),
        ];
        $this->view('payments/index',$data);
        exit;
    }

    public function add()
    {
        $data = [
            'title' => 'New payments',
            'payid' => $this->paymentmodel->GetPayId(),
            'invoices' => $this->paymentmodel->GetInvoicesWithBalances(),
            'totaldue' => 0
        ];

        if(!empty($data['invoices'])){
            $total = 0;
            foreach($data['invoices'] as $invoice){
                $total += floatval($invoice->OpeningBal);
            }
            $data['totaldue'] = $total;
        }

        $this->view('payments/add',$data);
        exit;
    }

    public function create()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $fields = json_decode(file_get_contents('php://input')); //extract json data
            if(empty($fields->header)){
                http_response_code(400);
                echo json_encode(['message' => 'Missing header data']);
                exit;
            }
            if(empty($fields->details)){
                http_response_code(400);
                echo json_encode(['message' => 'No payments made']);
                exit;
            }
            $header = $fields->header;
            $details = $fields->details;
            $data = [
                'paydate' => isset($header->paydate) && !empty(trim($header->paydate)) ? date('Y-m-d',strtotime(trim($header->paydate))) : null,
                'paymethod' => isset($header->paymethod) && !empty(trim($header->paymethod)) ? (int)trim($header->paymethod) : null,
                'details' => $details,
                'cheque_err' => '',
                'overpay_err' => ''
            ];
            $invalidreferenceerrors = 0;
            $overpayerrors = 0;
            $emptypayments = 0;
            foreach($details as $detail){
                if(empty(trim($detail->payment))){
                    $emptypayments ++;
                }
            }
            if((int)$emptypayments > 0){
                http_response_code(400);
                echo json_encode(['message' => $emptypayments . ' missing payment amounts']);
                exit;
            }
            foreach($details as $detail){
                if(empty(trim($detail->cheque))){
                    $invalidreferenceerrors ++;
                }
            }
            if((int)$invalidreferenceerrors > 0){
                http_response_code(400);
                echo json_encode(['message' => $invalidreferenceerrors . ' missing payment reference']);
                exit;
            }
            foreach($details as $detail){
                if(floatval(trim($detail->balance)) < floatval(trim($detail->payment))){
                    $overpayerrors ++;
                }
            }
            if((int)$overpayerrors > 0){
                http_response_code(400);
                echo json_encode(['message' => $overpayerrors . ' overpayments on invoice']);
                exit;
            }

            if(!$this->paymentmodel->Create($data)){
                http_response_code(500);
                echo json_encode(['message' => 'Unable to save payments. Retry or contact admin']);
                exit;
            }

            echo json_encode(['success' => true]);
            exit;

        }else
        {
            redirect('auth/forbidden');
            exit;
        }
    }

    public function print($id)
    {
        //check if passed url query is valid
        if (!strpos($id,'-') !== false) {
            flash('payment_msg',null,'Invalid invoice payment details provided',flashclass('alert','danger'));
            redirect('payments');
            exit;
        }
        //split value
        $paymentdetails = explode('-',$id);
        $paymentid = (int)$paymentdetails[0];
        $supplierid = (int)$paymentdetails[1];
        $header = $this->paymentmodel->GetPaymentDetails($paymentid,$supplierid);
        $paydate = $header->TransactionDate;
        //if payid and supplier id wrong
        if(!$header){
            flash('payment_msg',null,'Invalid invoice payment details provided',flashclass('alert','danger'));
            redirect('payments');
            exit;
        }
        $data = [
            'title' => 'Print payment',
            'pdate' => date('d-m-Y',strtotime($paydate)),
            'payid' => $paymentid,
            'supplier' => strtoupper($this->paymentmodel->GetSupplierName($supplierid)),
            'payments' => $this->paymentmodel->GetPaymentsDetail($paymentid,$supplierid,date('Y-m-d',strtotime($paydate))),
            'invoicevaluetotal' => 0,
            'paymentstotal' => 0,
            'balancetotal' => 0
        ];
        foreach($data['payments'] as $payments){
            $data['invoicevaluetotal'] += floatval($payments->InvoiceValue);
            $data['paymentstotal'] += floatval($payments->Payment);
            $data['balancetotal'] += floatval($payments->Balance);
        }
        $this->view('payments/print',$data);
        exit;
    }
}