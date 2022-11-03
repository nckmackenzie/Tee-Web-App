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
}