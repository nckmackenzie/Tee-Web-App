<?php
class Journals extends Controller
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
        $this->journalmodel = $this->model('Journal');
    }

    public function index()
    {
        $data = [
            'title' => 'Journal Entries',
            'glaccounts' => $this->journalmodel->GetGlAccounts(),
            'isedit' => false,
            'touched' => false,
            'id' => '',
            'jdate' => '',
            'journalno' => $this->journalmodel->GetJournalNo(),
            'description' => '',
            'debitstotal' => '',
            'creditstotal' => '',
            'accounts' => [],
            'jdate_err' => '',
            'save_err' => '',
        ];
        $this->view('journals/index',$data);
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => 'Journal Entries',
                'glaccounts' => $this->journalmodel->GetGlAccounts(),
                'isedit' => converttobool(trim($_POST['isedit'])),
                'touched' => true,
                'id' => trim($_POST['id']),
                'jdate' => !empty($_POST['jdate']) ? date('Y-m-d',strtotime(trim($_POST['jdate']))) : '',
                'journalno' => trim($_POST['journalno']),
                'description' => !empty(trim($_POST['description'])) ? trim($_POST['description']) : '',
                'debitstotal' => !empty(trim($_POST['debitstotal'])) ? trim($_POST['debitstotal']) : '',
                'creditstotal' => !empty(trim($_POST['creditstotal'])) ? trim($_POST['creditstotal']) : '',
                'accounts' => [],
                'accountsid' => isset($_POST['accountsid']) ? $_POST['accountsid'] : '',
                'accountsname' => isset($_POST['accountsname']) ? $_POST['accountsname'] : '',
                'types' => isset($_POST['types']) ? $_POST['types'] : '',
                'amounts' => isset($_POST['amounts']) ? $_POST['amounts'] : '',
                'debits' => isset($_POST['debits']) ? $_POST['debits'] : '',
                'credits' => isset($_POST['credits']) ? $_POST['credits'] : '',
                'has_rows' => true,
                'jdate_err' => '',
                'save_err' => '',
            ];

            if(!isset($data['accountsid']) || !is_countable($data['accountsid'])){
                $data['has_rows'] = false;
            }

            if($data['has_rows']){
                for($i = 0; $i < count($data['accountsid']); $i++){
                    array_push($data['accounts'],[
                        'aid' => $data['accountsid'][$i],
                        'name' => $data['accountsname'][$i],
                        'type' => trim($data['types'][$i]),
                        'debit' => !empty($data['debits'][$i]) ? $data['debits'][$i] : 0,
                        'credit' => !empty($data['credits'][$i]) ? $data['credits'][$i] : 0,
                    ]);
                }
            }

            if(empty($data['jdate'])){
                $data['jdate_err'] = 'Select a date';
            }
            if(!empty($data['jdate']) && !validatedate($data['jdate'])){
                $data['jdate_err'] = 'Select a valid date';
            }

            if(!empty($data['jdate_err'])){
                $this->view('journals/index',$data);
                exit();
            }

            if(!$this->journalmodel->CreateUpdate($data)){
                $data['save_err'] ='Unable to save journal. Try again or contact admin';
                $this->view('journals/index',$data);
                exit();
            }

            flash('journal_flash_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('journals');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}