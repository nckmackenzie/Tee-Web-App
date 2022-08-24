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
            'accounts' => $this->journalmodel->GetGlAccounts(),
            'isedit' => false,
            'id' => '',
            'journalno' => $this->journalmodel->GetJournalNo(),
            'description' => '',
        ];
        $this->view('journals/index',$data);
    }
}