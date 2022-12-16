<?php
class Managementreports extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        $this->reportmodel = $this->model('Managementreport');
    }

    public function index()
    {
        $data = ['title' => 'Page not found'];
        $this->view('auth/notfound',$data);
        exit;
    }

    public function incomestatement()
    {
        $data = ['title' => 'Income statement','has_datatable' => true];
        $this->view('managementreports/incomestatement',$data);
        exit;
    }

    public function incomestatementvalues()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
            ];

            if(is_null($data['sdate']) || is_null($data['edate'])){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }
            if($data['sdate'] > $data['edate']){
                http_response_code(400);
                echo json_encode(['message' => 'Start date cannot be greater than end date']);
                exit;
            }

            $values = $this->reportmodel->GetIncomeStatementValues($data);
            $results = [
                'fee' => floatval($values[0]),
                'gradfee' => floatval($values[1]),
                'generalExpenses' => floatval($values[2]),
                'purchases' => floatval($values[3]),
                'sales' => floatval($values[4]),
            ];

            echo json_encode(['success' => true, 'results' => $results]);
            exit;

        }
        else{
            redirect('auth/forbidden');
            exit;
        }
    }

    public function trialbalance()
    {
        $data = [
            'title' => 'Trial Balance',
            'has_datatable' => true
        ];
        $this->view('managementreports/trialbalance',$data);
        exit;
    }

    public function trialbalancerpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
            ];
            if(is_null($data['sdate']) || is_null($data['edate'])){
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }
            if($data['sdate'] > $data['edate']){
                http_response_code(400);
                echo json_encode(['message' => 'Start date cannot be greater than end date']);
                exit;
            }
            //get tb result
            $accounts = $this->reportmodel->GetTrialBalanceReport($data);
            $results = [];
            $debitstotal = 0;
            $creditstotal = 0;

            if(empty($accounts)){
                array_push($results,[
                    'account' => '',
                    'debit' => '',
                    'credit' => '',
                ]);
            }else{
                foreach($accounts as $account)
                {
                    $debitstotal += floatval($account->Debit);
                    $creditstotal += floatval($account->credit);
                    if(floatval($account->Debit) !==0 && floatval($account->credit) !==0):
                        array_push($results,[
                            'account' => ucwords($account->Account),
                            'debit' => floatval($account->Debit) == 0 ? '' : floatval($account->Debit), 
                            'credit' => floatval($account->credit) == 0 ? '' : floatval($account->credit)
                        ]);
                    endif;
                }
            }

            echo json_encode(['success' => true,
                              'results' => $results,
                              'debitstotal' => $debitstotal,
                              'creditstotal' => $creditstotal]);
            exit;
        }
        else
        {
            redirect('auth/forbidden');
            exit;
        }
    }

    public function tbdetailed()
    {
        $data = ['title' => 'Account detailed','has_datatable' => true];
        $this->view('managementreports/tbdetailed',$data);
        exit;
    }

    public function getledgerdetailedrpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'sdate' => isset($_GET['sdate']) && !empty(trim($_GET['sdate'])) ? date('Y-m-d',strtotime(trim($_GET['sdate']))) : null,
                'edate' => isset($_GET['edate']) && !empty(trim($_GET['edate'])) ? date('Y-m-d',strtotime(trim($_GET['edate']))) : null,
                'account' => isset($_GET['account']) && !empty(trim($_GET['account'])) ? strtolower(trim($_GET['account'])) : null,
            ];
            if(is_null($data['sdate']) || is_null($data['edate']) || is_null($data['account'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Provide all required fields']);
                exit;
            }
            if($data['sdate'] > $data['edate']){
                http_response_code(400);
                echo json_encode(['message' => 'Start date cannot be greater than end date']);
                exit;
            }
            //get tb result
            $accounts = $this->reportmodel->GetLedgerDetails($data);
            $results = [];
            $debitstotal = 0;
            $creditstotal = 0;

            if(empty($accounts)){
                array_push($results,[
                    'transactionDate' => '',
                    'account' => '',
                    'debit' => '',
                    'credit' => '',
                    'narration' => '',
                    'transactionType' => '',
                ]);
            }else{
                foreach($accounts as $account)
                {
                    $debitstotal += floatval($account->Debit);
                    $creditstotal += floatval($account->Credit);
                    array_push($results,[
                        'transactionDate' => date('d-m-Y',strtotime($account->TransactionDate)),
                        'account' => ucwords($account->Account), 
                        'debit' => floatval($account->Debit) == 0 ? '' : floatval($account->Debit),
                        'credit' => floatval($account->Credit) == 0 ? '' : floatval($account->Credit),
                        'narration' => ucwords($account->Narration), 
                        'transactionType' => ucwords($account->TransactionType), 
                    ]);
                }
            }

            echo json_encode(['success' => true,
                              'results' => $results,
                              'debitstotal' => $debitstotal,
                              'creditstotal' => $creditstotal]);
            exit;
        }
        else
        {
            redirect('auth/forbidden');
            exit;
        }
    }

    public function balancesheet()
    {
        $data = ['title' => 'Balance Sheet'];
        $this->view('managementreports/balancesheet',$data);
        exit;
    }

    public function balancesheetrpt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $date = isset($_GET['date']) && !empty(trim($_GET['date'])) ? date('Y-m-d',strtotime(trim($_GET['date']))) : null;
            if(is_null($date)){
                http_response_code(400);
                echo json_encode(['success' => false,'Provide date']);
                exit;
            }
            if($date > date('Y-m-d')){
                http_response_code(400);
                echo json_encode(['success' => false,'Invalid date selected']);
                exit;
            }

            $totals = $this->reportmodel->GetTotals($date);
            //get results
            $assets = $this->reportmodel->BalancesheetAssets($date);
            $liabilitiesequities = $this->reportmodel->BalancesheetLiablityAndEquity($date);
            $assetstotals = $totals[0];
            $liabilityequitytotal = $totals[1];
            $netincome = $this->reportmodel->GetNetIncome($date);
            $totalLiablityEquity = floatval($liabilityequitytotal) + floatval($netincome);
            $output = '';
            $output .= '
                <table class="table table-bordered table-sm" id="table">
                    <thead class="bg-lightblue">
                        <tr>
                            <th>Balance Sheet As Of '.date("d/m/Y", strtotime($date)).'</th>
                        </tr>
                    </thead>   
                    <tbody>
                        <tr class="bg-success text-white">
                            <td colspan="2">Assets</th>
                        </tr>';
                    foreach($assets as $asset){
                        $output .='
                        <tr>
                            <td>'.ucwords($asset->Account).'</td>
                            <td>'.number_format($asset->bal,2).'</td>
                        </tr>';
                    }
                    $output .='
                        <tr style="background-color: #abebbc;">
                            <td style="font-weight: 700;">Assets Total</td>
                            <td style="font-weight: 700;">'.number_format($assetstotals,2).'</td>
                        </tr>
                        <tr style="background-color: #e85858; color: #fff;">
                            <td colspan="2">Liability & Equity</th>
                        </tr>';
                    foreach ($liabilitiesequities as $liabilityequity) {
                        $output .='
                        <tr>
                             <td>'.strtoupper($liabilityequity->Account).'</td>
                             <td>'.number_format((floatval($liabilityequity->bal) * -1),2).'</td>
                        </tr>';
                    } 
                    $output .='
                        <tr>
                            <td>NET INCOME</td>
                            <td>'.number_format(floatval($netincome),2).'</td>
                        </tr>
                        <tr style="background-color: #f59595;">
                            <td style="font-weight: 700;">Liablity & Equity Total</td>
                            <td style="font-weight: 700;">'.number_format($totalLiablityEquity,2).'</td>
                        </tr>
                    </tbody>
                </table>';

            echo json_encode(['success' => true, 'markup' => $output]);
            exit;
        }
        else
        {
            redirect('auth/forbidden');
            exit;
        }
    }
}