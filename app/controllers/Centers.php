<?php
class Centers extends Controller 
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }else{
            $this->centermodel = $this->model('Center');
        }
    }

    public function checkrights()
    {
        if(isset($_SESSION['userid']) && $_SESSION['usertypeid'] > 2){
            return false;
        }elseif (isset($_SESSION['userid']) && (int)$_SESSION['usertypeid'] < 3) {
            return true;
        }
    }

    public function index()
    {
        if((int)$_SESSION['ishead'] != 1 || !$this->checkrights()){
            redirect('auth/forbidden');
            exit();
        }
        $data = [
            'title' => 'Centers',
            'has_datatable' => true,
            'centers' => $this->centermodel->GetCenters()
        ];
        $this->view('centers/index', $data);
    }
    
    public function add()
    {
        if((int)$_SESSION['ishead'] != 1 || !$this->checkrights()){
            redirect('auth/forbidden');
            exit();
        }
        $data = [
            'title' => 'Create Center',
            'touched' => false,
            'isedit' => false,
            'id' => '',
            'name' => '',
            'email' => '',
            'contact' => '',
            'name_err' => '',
            'email_err' => '',
            'contact_err' => '',
        ];
        $this->view('centers/add',$data);
        exit();
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST,FILTER_UNSAFE_RAW);
            $data = [
                'title' => 'Create Center',
                'touched' => true,
                'isedit' => converttobool($_POST['isedit']),
                'id' => trim($_POST['id']),
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'contact' => trim($_POST['contact']),
                'name_err' => '',
                'email_err' => '',
                'contact_err' => '',
            ];

            //validation
            if(empty($data['name'])){
                $data['name_err'] = 'Provide center name';
            }else{
                if(!$this->centermodel->CheckAvailability('CenterName',$data['id'],$data['name'])){
                    $data['name_err'] = 'Center name already available';
                }
            }

            if(empty($data['contact'])){
                $data['contact_err'] = 'Provide center contact';
            }else{
                if(!$this->centermodel->CheckAvailability('Contact',$data['id'],$data['contact'])){
                    $data['contact_err'] = 'Center already available';
                }
            }

            if(!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $data['email_err'] = 'Invalid email provided';
            }
            

            if(!empty($data['name_err']) || !empty($data['contact_err']) || !empty($data['email_err'])){
                $this->view('centers/add',$data);
            }else{
                if(!$this->centermodel->CreateUpdate($data)){
                    flash('center_msg',null,'Something went wrong creating the center.',flashclass('alert','danger'));
                    redirect('users');
                    exit();
                }else{
                    flash('center_toast_msg',null,'Saved successfully!',flashclass('toast','success'));
                    redirect('centers');
                    exit();
                }
            }

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}