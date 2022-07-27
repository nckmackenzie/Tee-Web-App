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
}