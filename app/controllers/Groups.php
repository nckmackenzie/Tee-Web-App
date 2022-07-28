<?php
class Groups extends Controller
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->groupmodel = $this->model('Group');
    }

    public function index()
    {
        $groups = $this->groupmodel->GetGroups();
        $data = [
            'title' => 'Groups',
            'has_datatable' => true,
            'groups' => $groups
        ];
        $this->view('groups/index',$data);
    }

    public function add()
    {
        $data = [
            'title' => 'Add Group',
            'id' => '',
            'touched' => false,
            'isedit' => false,
            'groupname' => '',
            'parishname' => '',
            'active' => true,
            'groupname_err' => '',
            'parishname_err' => '',
        ];
        $this->view('groups/add',$data);
        exit();
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => converttobool(trim($_POST['isedit'])) ? 'Edit Group' : 'Add Group',
                'id' => trim($_POST['id']),
                'touched' => true,
                'isedit' => converttobool(trim($_POST['isedit'])),
                'groupname' => !empty(trim($_POST['groupname'])) ? trim($_POST['groupname']) : '',
                'parishname' => !empty(trim($_POST['parishname'])) ? trim($_POST['parishname']) : '',
                'active' => converttobool(trim($_POST['isedit'])) ? (isset($_POST['active']) ? true : false) : true,
                'groupname_err' => '',
                'parishname_err' => '',
            ];

            //validate
            if(empty($data['groupname'])){
                $data['groupname_err'] = 'Enter group name';
            }

            if(empty($data['parishname'])){
                $data['parishname_err'] = 'Enter parish name';
            }

            if(!empty($data['groupname']) && !empty($data['parishname']) 
               && !$this->groupmodel->CheckGroupName($data['groupname'],$data['parishname'],$data['id'])){
                $data['groupname_err'] = 'Group name already exists';
            }

            if(!empty($data['groupname_err']) || !empty($data['parishname_err'])){
                $this->view('groups/add',$data);
                exit();
            }

            if(!$this->groupmodel->CreateUpdate($data)){
                flash('group_msg',null,'Unable to save group. Contact your administrator',flashclass('alert','danger'));
                redirect('groups');
                exit();
            }

            flash('group_flash_msg',null,'Saved successfully!',flashclass('toast','success'));
            redirect('groups');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function edit($id)
    {
        $group = $this->groupmodel->GetGroup($id);
        $data = [
            'title' => 'Edit Group',
            'id' => $group->ID,
            'touched' => false,
            'isedit' => true,
            'groupname' => strtoupper($group->GroupName),
            'parishname' => strtoupper($group->ParishName),
            'active' => converttobool($group->Active),
            'groupname_err' => '',
            'parishname_err' => '',
        ];
        $this->view('groups/add',$data);
        exit();
    }

    public function delete()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = trim($_POST['id']);

            if(empty($id)){
                flash('group_msg',null,'Unable to get selected group.',flashclass('alert','danger'));
                redirect('groups');
                exit();
            }

            if(!$this->groupmodel->Delete($id)){
                flash('group_msg',null,'Unable to delete group. Contact your administrator',flashclass('alert','danger'));
                redirect('groups');
                exit();
            }

            flash('group_flash_msg',null,'Deleted successfully!',flashclass('toast','success'));
            redirect('groups');
            exit();
        }
    }

    public function members()
    {
        $groups = $this->groupmodel->GetGroupMembers();
        $data = [
            'title' => 'Groups Members',
            'has_datatable' => true,
            'groups' => $groups
        ];
        $this->view('groups/members',$data);
        exit();
    }

    public function manage($id)
    {
        $groupmembers = $this->groupmodel->GetMembersByGroup($id);
        $students = $this->groupmodel->GetStudents();
        $groups = $this->groupmodel->GetGroups();
        $data = [
            'title' => 'Manage Group Members',
            'id' => '',
            'isedit' => false,
            'groupmembers' => [],
            'students' => $students,
            'groups' => $groups,
            'group' => $id
        ];
        foreach ($groupmembers as $member){
            array_push($data['groupmembers'],[
                'sid' => $member->ID,
                'studentname' => $member->StudentName
            ]);
        }
        $this->view('groups/manage',$data);
        exit();
    }

    public function managecreateupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $students = $this->groupmodel->GetStudents();
            $groups = $this->groupmodel->GetGroups();
            $data = [
                'title' => 'Manage Group Members',
                'id' => trim($_POST['id']),
                'isedit' => converttobool(trim($_POST['isedit'])),
                'groupmembers' => [],
                'students' => $students,
                'groups' => $groups,
                'group' => trim($_POST['group']),
                'studentsid' => $_POST['studentsid'],
                'studentsname' => $_POST['studentsname'],
            ];

            if(!$this->groupmodel->ManageCreateUpdate($data)){
                flash('groupmember_msg',null,'Members not saved. Retry or contact admin',flashclass('alert','danger'));
                redirect('groups/members');
                exit();
            }

            flash('groupmember_flash_msg',null,'Saved successfully!',flashclass('toast','success'));
            redirect('groups/members');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}