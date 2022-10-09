<?php
class Userrights extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        if((int)$_SESSION['usertypeid'] > 2){
            redirect('auth/unauthorized');
            exit;
        }

        $this->rightsmodel = $this->model('Userright');
    }

    public function index()
    {
        $data = [
            'title' => 'User rights',
            'has_datatable' => false,
            'users' => $this->rightsmodel->GetUsers(),
            'user' => '',
            // 'forms' => $this->rightsmodel->GetForms()
        ];
        $this->view('userrights/index',$data);
        exit;
    }

    public function getrightsassigned()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $userid = isset($_GET['userid']) && !empty(trim($_GET['userid'])) ? htmlentities(trim($_GET['userid'])) : NULL;

            if(is_null($userid)) :
                http_response_code(400);
                echo json_encode(['message' => 'Select user']);
                exit;
            endif;

            echo json_encode($this->rightsmodel->GetForms($userid));

        }else{
            redirect('auth/forbidden');
            exit;
        }
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $fields = json_decode(file_get_contents('php://input')); //extract json from submitted form
            $data =[
                'user' => isset($fields->user) && !empty(trim($fields->user)) ? (int)trim($fields->user) : NULL,
                'rights' => is_countable($fields->tableData) ? $fields->tableData : NULL,
            ];

            //validate
            if(is_null($data['user']) || is_null($data['rights'])) : 
                http_response_code(400);
                echo json_encode(['message' => 'Fill all required fields']);
                exit;
            endif;

            //if was not saved
            if(!$this->rightsmodel->CreateUpdate($data)){
                http_response_code(500);
                echo json_encode(['message' => 'Rights unable to save. Retry or contact admin']);
                exit;
            }

            echo json_encode(['message' => 'Successfully saved','success' => true]);
            exit;

        }else {
            redirect('auth/forbidden');
            exit;
        }
    }

    //clone user rights
    public function clone()
    {
        $data = [
            'title' => 'Clone user rights',
            'has_datatable' => true,
            'users' => $this->rightsmodel->GetUsers(),
        ];
        $this->view('userrights/clone',$data);
        exit;
    }

    //clone user rights functionality
    public function createclone()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $fields = json_decode(file_get_contents('php://input'));
            
            $data = [
                'from' =>!empty(trim($fields->from))? $fields->from : '',
                'to' =>!empty(trim($fields->to)) ? $fields->to : '',
            ];

            //validate
            if(empty($data['from']) || empty($data['to'])) :
                http_response_code(400);
                echo json_encode(['message' => 'Please fill all required entries']);
                exit;
            endif;

            if(!$this->rightsmodel->CheckRights((int)$data['from'])) :
                http_response_code(404);
                echo json_encode(['message' => 'User has no rights assigned to them!']);
                exit;
            endif;

            //rights didn't clone
            if(!$this->rightsmodel->Clone($data)) :
                http_response_code(500);
                echo json_encode(['message' => 'Failed to clone user rights! Retry or contact admin']);
                exit;
            endif;

            http_response_code(200);
            echo json_encode(['message' => 'Successfully cloned!']);
            exit;

        }else{
            redirect('auth/forbidden');
            exit;
        }
    }

    //check if selected user has rights assigned
    public function checkuserrights()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $userid = htmlspecialchars(trim($_GET['userid']));

            echo json_encode($this->rightsmodel->CheckRights((int)$userid));

        }else {
            redirect('auth/forbidden');
            exit;
        }
    }
}
