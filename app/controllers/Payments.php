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
}