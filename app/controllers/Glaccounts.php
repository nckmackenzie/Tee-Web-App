<?php
class Glaccounts extends Controller 
{
    public function __construct()
    {
        if(isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        if(!$_SESSION['ishead'] || intval($_SESSION['usertype']) > 2){
            redirect('auth/unauthorized');
            exit();
        }
        $this->glaccountmodel = $this->model('Glaccount');
    }
}