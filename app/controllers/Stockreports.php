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
}