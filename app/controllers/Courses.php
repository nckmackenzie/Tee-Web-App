<?php
class Courses extends Controller
{
    public function __construct()
    {
        if(!is_authenticated($_SESSION['userid'])){
            redirect('auth');
            exit();
        }

        if(!adminonly($_SESSION['userid'],$_SESSION['usertypeid']) && !converttobool($_SESSION['ishead'])){
            redirect('auth/unauthorized');
            exit();
        }
        $this->coursemodel = $this->model('Course');
    }

    public function index()
    {
        $courses = $this->coursemodel->GetCourses();
        $data = [
            'title' => 'Courses',
            'has_datatable' => true,
            'courses' => $courses
        ];
        $this->view('courses/index',$data);
    }
}