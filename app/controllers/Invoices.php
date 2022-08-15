<?php
class Invoices extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->invoicemodel = $this->model('Invoice');
    }
}