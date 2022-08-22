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

    public function index()
    {
        $data = [
            'title' => 'Expenses',
            'has_datatable' => true,
            'expenses' => $this->expensemodel->GetExpenses()
        ];
        $this->view('expenses/index',$data);
    }
}