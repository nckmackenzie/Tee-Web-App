<?php

class Classes extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit;
        }
        $this->authmodel = $this->model('Auths');
        checkrights($this->authmodel,'classes');
        $this->classmodel = $this->model('Classs');
    }

    public function index()
    {
        $data = [
            'title' => 'Classes',
            'has_datatable' => true,
            'classes' => $this->classmodel->GetClasses()
        ];
        $this->view('classes/index',$data);
        exit;
    }
    
    public function add()
    {
        $data = [
            'title' => 'Add Class',
            'id' => 0,
            'isedit' => false,
            'classname' => '',
        ];
        $this->view('classes/add',$data);
        exit;
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $fields = json_decode(file_get_contents('php://input'));
            $data = [
                'id' => isset($fields->id) && !empty(trim($fields->id)) ? (int)trim($fields->id) : '',
                'isedit' => converttobool($fields->isedit),
                'classname' => isset($fields->classname) && !empty(trim($fields->classname)) ? strtolower(trim($fields->classname)) : null,
            ];

            //validate
            if(is_null($data['classname'])){
                http_response_code(400);
                echo json_encode(['message' => 'Enter class name']);
                exit;
            }
            if(!$this->classmodel->CheckName($data['classname'],$data['id'])){
                http_response_code(400);
                echo json_encode(['message' => 'Class Name already exists']);
                exit;
            }
            if(!$this->classmodel->CreateUpdate($data)){
                http_response_code(500);
                echo json_encode(['message' => 'Unable to save class. Retry or contact admin']);
                exit;
            }

            echo json_encode(['success' => true]);
            exit;
        }
        else{
            redirect('auth/forbidden');
            exit;
        }
    }

    public function edit($id)
    {
        $class = $this->classmodel->GetClass($id);
        if(empty($class)){
            flash('class_msg',null,'Transaction seems to be deleted',flashclass('alert','danger'));
            redirect('classes');
            exit;
        }
        $data = [
            'title' => 'Edit class',
            'id' => $class->ID,
            'isedit' => true,
            'classname' => strtoupper($class->ClassName),
        ];
        $this->view('classes/add',$data);
        exit;
    }

    public function delete()
    {
        delete('class',$this->classmodel,false);
        exit;
    }
}