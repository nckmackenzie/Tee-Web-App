<?php
class Expenses extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->expensemodel = $this->model('Expense');
    }
}