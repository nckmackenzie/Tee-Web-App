<?php

class Budgetreports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        $this->reusemodel = $this->model('Reusable');
        $this->reportmodel = $this->model('Budgetreport');
    }

    public function index()
    {
        $data= ['title' => 'Page not found'];
        $this->view('auth/notfound',$data);
        exit;
    }
    
    public function summary()
    {
        checkrights($this->authmodel,'summary');
        $data = [
            'title' => 'Budget summary',
            'has_datatable' => true,
            'years' => $this->reusemodel->GetYears(true)
        ];
        $this->view('budgetreports/summary',$data);
        exit;
    }

    public function summaryrpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $yearid = isset($_GET['year']) && !empty(trim($_GET['year'])) ? (int)trim(htmlentities($_GET['year'])) : null;
            $accounts = [];
            //validate
            if(is_null($yearid)){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }

            $results = $this->reportmodel->GetBudgetVsExpense($yearid,'summary');
            if(empty($results)){
                http_response_code(404);
                echo json_encode(['message' => 'No data found']);
                exit;
            }

            //loop to reformat 
            foreach($results as $result)
            {
                array_push($accounts,[
                    'expenseAccount' => $result->AccountName,
                    'budgetedAmount' => floatval($result->BudgetedAmount),
                    'expensedAmount' => floatval($result->ExpensedAmount),
                    'variance' => floatval($result->BudgetedAmount) - floatval($result->ExpensedAmount)
                ]);
            }

            echo json_encode(['success' => true,'results' => $accounts]);
            exit;
        }
        else
        {
            redirect('auth/forbidden');
            exit;
        }
    }

    public function detailed()
    {
        checkrights($this->authmodel,'detailed');
        $data = [
            'title' => 'Budget detailed',
            'has_datatable' => true,
            'years' => $this->reusemodel->GetYears(true)
        ];
        $this->view('budgetreports/detailed',$data);
        exit;
    }

    public function detailedrpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $yearid = isset($_GET['year']) && !empty(trim($_GET['year'])) ? (int)trim(htmlentities($_GET['year'])) : null;
            $accounts = [];
            //validate
            if(is_null($yearid)){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }

            $results = $this->reportmodel->GetBudgetVsExpense($yearid,'detailed');
            if(empty($results)){
                http_response_code(404);
                echo json_encode(['message' => 'No data found']);
                exit;
            }

            //loop to reformat 
            foreach($results as $result)
            {
                array_push($accounts,[
                    'expenseAccount' => $result->AccountName,
                    'budgetedAmount' => floatval($result->BudgetedAmount),
                    'oct' => floatval($result->Oct),
                    'nov' => floatval($result->Nov),
                    'dec' => floatval($result->Dec),
                    'jan' => floatval($result->Jan),
                    'feb' => floatval($result->Feb),
                    'mar' => floatval($result->Mar),
                    'apr' => floatval($result->Apr),
                    'may' => floatval($result->May),
                    'jun' => floatval($result->Jun),
                    'jul' => floatval($result->Jul),
                    'aug' => floatval($result->Aug),
                    'sep' => floatval($result->Sep)
                ]);
            }

            echo json_encode(['success' => true,'results' => $accounts]);
            exit;
        }
        else
        {
            redirect('auth/forbidden');
            exit;
        }
    }
}