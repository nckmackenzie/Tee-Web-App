<?php
class Years extends Controller
{
    public function __construct(){
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'financial years');
        $this->yearmodel = $this->model('Year');
    }

    public function index()
    {
        $years = $this->yearmodel->GetYears();
        $data = [
            'title' => 'Financial Years',
            'has_datatable' => true,
            'years' => $years
        ];
        $this->view('years/index',$data);
    }

    public function add()
    {
        $data = [
            'title' => 'Add Year',
            'isedit' => false,
            'touched' => false,
            'id' => '',
            'name' => '',
            'start' => '',
            'end' => '',
            'name_err' => '',
            'start_err' => '',
            'end_err' => '',
        ];
        $this->view('years/add',$data);
        exit();
    }

    public function createupdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => 'Add Year',
                'isedit' => converttobool($_POST['isedit']),
                'touched' => true,
                'id' => $_POST['id'],
                'name' => trim($_POST['name']),
                'start' => date("Y-m-d", strtotime($_POST['start'])),
                'end' => date("Y-m-d", strtotime($_POST['end'])),
                'name_err' => '',
                'start_err' => '',
                'end_err' => '',
            ];
            //validate
            if(empty($data['name'])){
                $data['name_err'] = '';
            }else{
                if(!$this->yearmodel->CheckAvailability('name',$data['id'],$data['name'])){
                    $data['name_err'] = 'Year name already exists';
                }
            }

            if(empty($data['start'])){
                $data['start_err'] = '';
            }else{
                if(!$this->yearmodel->CheckAvailability('date',$data['id'],$data['start'])){
                    $data['start_err'] = 'Seleced date range conflicts with another existing record';
                    $data['end_err'] = 'Seleced date range conflicts with another existing record';
                }
            }

            if(empty($data['end'])){
                $data['end_err'] = '';
            }

            if(!empty($data['start']) && !empty($data['end']) && ($data['start'] > $data['end'])){
                $data['start_err'] = 'Start date cannot be greater than end date';
            }

            if(!empty($data['start_err']) || !empty($data['end_err']) || !empty($data['name_err'])){
                $this->view('years/add',$data);
                exit();
            }

            if(!$this->yearmodel->CreateUpdate($data)){
                flash('year_msg',null,'Somthing went wrong while saving! Try again or contact admin',flashclass('alert','danger'));
                redirect('years');
                exit();
            }

            flash('year_toast_msg',null,'Saved successfully!',flashclass('toast','success'));
            redirect('years');
            exit();    

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    function openclosedelete($action)
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = trim($_POST['id']);

            if(empty($id)){
                flash('year_msg',null,'Unable to get selected year!',flashclass('alert','danger'));
                redirect('years');
                exit();
            }

            if(!$this->yearmodel->DeleteClose($id,$action)){
                flash('year_msg',null,'Unable to '.$action.' selected year. Retry or contact admin!',flashclass('alert','danger'));
                redirect('years');
                exit();
            }

            flash('year_toast_msg',null,''.ucwords($action).' success!',flashclass('toast','success'));
            redirect('years');
            exit();    

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function delete()
    {
        $this->openclosedelete('delete');
    }

    public function close()
    {
        $this->openclosedelete('close');
    }

    public function open()
    {
        $this->openclosedelete('open');
    }

    public function edit($id)
    {
        $year = $this->yearmodel->GetYear($id);
        $data = [
            'title' => 'Edit Year',
            'isedit' => true,
            'touched' => false,
            'id' => (int)$year->ID,
            'name' => strtoupper($year->YearName),
            'start' => $year->StartDate,
            'end' => $year->EndDate,
            'name_err' => '',
            'start_err' => '',
            'end_err' => '',
        ];
        $this->view('years/add',$data);
        exit();
    }
}