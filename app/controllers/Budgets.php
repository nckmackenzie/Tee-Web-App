<?php
class Budgets extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        if((int)$_SESSION['usertypeid'] > 3){
            redirect('auth/unauthorized');
            exit();
        }
        $this->budgetmodel = $this->model('Budget');
    }
}