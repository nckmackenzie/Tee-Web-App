<?php
class Semisters extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'semisters');
        $this->semistermodel = $this->model('Semister');
    }

    public function index()
    {
        $data = [
            'title' => 'Semisters',
            'has_datatable' => true,
            'semisters' => $this->semistermodel->GetSemisters()
        ];
        $this->view('semisters/index',$data);
        exit;
    }

    public function add()
    {
        $data = [
            'title' => 'Add Semister',
            'classes' => $this->semistermodel->GetClasses(),
            'id' => 0,
            'isedit' => false,
            'semistername' => '',
            'class' => '',
            'startdate' => '',
            'enddate' => '',
            'has_error' => false,
            'error' => '',
        ];
        $this->view('semisters/add',$data);
        exit;
    }

    //create and update
    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //extract json data
            $fields = json_decode(file_get_contents('php://input'));
            $data = [
                'title' => converttobool($fields->isedit) ? 'Edit Semister' : 'Add Semister',
                'id' => !empty(trim($fields->id)) ? trim($fields->id) : '',
                'isedit' => converttobool($fields->isedit),
                'semistername' => !empty(trim($fields->semistername)) ? trim($fields->semistername) : NULL,
                'class' => !empty($fields->class) ? (int)trim($fields->class) : NULL,
                'startdate' => !empty(trim($fields->startdate)) ? date('Y-m-d',strtotime(trim($fields->startdate))) : NULL,
                'enddate' => !empty(trim($fields->enddate)) ? date('Y-m-d',strtotime(trim($fields->enddate))) : NULL,
                'has_error' => false,
                'error' => '',
            ];
            //validate
            if(is_null($data['semistername']) || is_null($data['startdate']) || is_null($data['enddate']) || is_null($data['class'])){
                http_response_code(400);
                echo json_encode(['message' => 'Fill all required fields']);
                exit;
            }
            if(!$this->semistermodel->CheckExists($data,'name')){
                http_response_code(400);
                echo json_encode(['message' => 'Semister name already exists']);
                exit;
            }
            if($data['startdate'] > $data['enddate']){
                http_response_code(400);
                echo json_encode(['message' => 'Start date cannot be greater than end date']);
                exit;
            }
            if(!$this->semistermodel->CheckExists($data,'date')){
                http_response_code(400);
                echo json_encode(['message' => 'Defined period conflicts with another period']);
                exit;
            }
            if(!$this->semistermodel->CreateUpdate($data)){
                http_response_code(500);
                echo json_encode(['message' => 'Unable to save. Retry or contact admin']);
                exit;
            }
            //saved successfully
            echo json_encode(['success' => true]);
            exit;

        }else{
            redirect('auth/forbidden');
            exit;
        }
    }
    
    public function edit($id)
    {
        $semister = $this->semistermodel->GetSemister($id);
        $data = [
            'title' => 'Edit Semister',
            'classes' => $this->semistermodel->GetClasses(),
            'id' => $semister->ID,
            'isedit' => true,
            'semistername' => strtoupper($semister->SemisterName),
            'startdate' => $semister->StartDate,
            'enddate' => $semister->EndDate,
            'class' => $semister->ClassId,
            'has_error' => false,
            'error' => '',
        ];
        $this->view('semisters/add',$data);
        exit;
    }

    public function delete()
    {
        delete('semister',$this->semistermodel);
    }

    public function close()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
    
            if(empty($id)){
                flash('semister_msg',null,'Unable to get selected semister',flashclass('alert','danger'));
                redirect('semisters');
                exit();
            }
    
    
            if(!$this->semistermodel->Close($id)){
                flash('semister_msg',null,'Unable to delete selected semister',flashclass('alert','danger'));
                redirect('semisters');
                exit();
            }
    
            flash('semister_flash_msg',null,'Deleted successfully',flashclass('toast','success'));
            redirect('semisters');
            exit();
    
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}