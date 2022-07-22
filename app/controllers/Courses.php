<?php
class Courses extends Controller
{
    public function __construct()
    {
        if(!is_authenticated($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->coursemodel = $this->model('Course');
    }
}