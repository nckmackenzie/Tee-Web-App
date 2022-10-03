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
            'forms' => $this->rightsmodel->GetForms()
        ];
        $this->view('userrights/index',$data);
        exit;
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'user' => !empty(trim($_POST['user'])) ? trim($_POST['user']) : '',
                'forms' => $_POST['formsid'],
                'names' => $_POST['formsname'],
                'access' => $_POST['access'],
            ];

            if($this->rightsmodel->CreateUpdate($data)){
                redirect('userrights');
                exit;
            }

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
