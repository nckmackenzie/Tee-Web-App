<?php
//url redirect
function redirect($page){
    header('location: ' . URLROOT . '/' . $page);
}

//add css classes for data validation
function inputvalidation($data,$err,$touch){
    if (!empty($err)){
        return 'is-invalid';
    }elseif (empty($err) && !empty($data) && $touch === true){ 
        return 'is-valid';
    }
}

//set selected option
function selectdCheck($value1,$value2)
{
    if ($value1 == $value2){
      echo 'selected="selected"';
    } else {
       echo '';
    }
    return;
}

//add class based on alert type
function flashclass($flash,$type) {
    if($flash === "toast"){
        return 'toast-header bg-'. $type . '';
    }elseif($flash === 'alert'){
        return 'alert alert-'. $type .' alert-dismissible fade show';
    }
}

//Get value from Database
function getdbvalue($con,$sql,$arr){
    $stmt = $con->prepare($sql);
    $stmt->execute($arr);
    return $stmt->fetchColumn();
}

//load result set
function loadresultset($con,$sql,$arr){
    $stmt = $con->prepare($sql);
    $stmt->execute($arr);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

//get first letter of word
function getfirstword($word){
    return explode(' ',$word)[0];
}

//set badge classes for status
function badgeclasses($var){
    if($var === 'Active'){
        return 'bg-success';;
    }else{
        return 'bg-danger';
    }
}

//convert value from string to boolean
function converttobool($val){
    $converted = filter_var($val, FILTER_VALIDATE_BOOLEAN);
    return $converted;
}

//modal
function DeleteModal($route,$modalid,$message,$inputid){
    echo '
    <div class="modal fade" id="'.$modalid.'" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="'.$route.'" method="post" autocomplete="off">
                    <div class="modal-header">
                        <h4 class="modal-title">Action</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>'.$message.'</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" value="" id="'.$inputid.'" />
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    ';
}

function adminonly($session,$usertype){
    if(!isset($session)){
        redirect('auth');
        exit();
    }elseif(isset($session) && $usertype > 2){
        redirect('auth/unauthorized');
        exit();
    }
}

//checkbox checked state
function checkstate($val)
{
    if (converttobool($val)){
      echo 'checked';
    } else {
       echo 'unchecked';
    }
    return;
}

//check if user is authenticated
function is_authenticated($user){
    if(!isset($user)){
        return false;
    }
    return true;
}

function encrypt($string){
    $key = ENCRYPTION_KEY;
    $result = '';
    $test = '';
    for ($i=0; $i <strlen($string) ; $i++) { 
        $char = substr($string, $i,1);
        $keychar = substr($key, ($i % strlen($key))-1,1);
        $char = chr(ord($char)+ord($keychar));
        //$test[$char]=ord($char)+ord($keychar);
        $result.=$char;
    }
    return urlencode(base64_encode($result));
}

function decrypt($string){
    $key = ENCRYPTION_KEY;
    $result = '';
    $string = base64_decode(urldecode($string));
    for ($i=0; $i < strlen($string) ; $i++) { 
        $char = substr($string, $i,1);
        $keychar = substr($key, ($i % strlen($key))-1,1);
        $char = chr(ord($char)-ord($keychar));
        $result.=$char;
    }
    return $result;
}

//validate email
function validateemail($email){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return false;
    }else{
        return true;
    }
}

//save to ledger
function savetoledger($con,$date,$account,$debit,$credit,$narration,$accountId,$type,$tid,$center){
    $sql = "INSERT INTO ledger (TransactionDate,Account,Debit,Credit,Narration,AccountId,
                                TransactionType,TransactionId,CenterId) 
            VALUES(?,?,?,?,?,?,?,?,?)";
    $stmt = $con->prepare($sql);
    $stmt->execute([$date,$account,$debit,$credit,$narration,$accountId,$type,$tid,$center]);
}

//disable other center edit
function checkcenter($center){
    if(intval($_SESSION['centerid']) !== intval($center)){
        redirect('auth/unauthorized');
        exit();
    }
}

function delete($name,$model,$validatedelete = false){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = trim($_POST['id']);

        if(empty($id)){
            flash($name.'_msg',null,'Unable to get selected '.$name,flashclass('alert','danger'));
            redirect($name.'s');
            exit();
        }

        if($validatedelete && !$model->ValidateDelete($id)){
            flash($name.'_msg',null,'Cannot delete as record referenced elsewhere',flashclass('alert','danger'));
            redirect($name.'s');
            exit();
        }

        if(!$model->Delete($id)){
            flash($name.'_msg',null,'Unable to delete selected '.$name,flashclass('alert','danger'));
            redirect($name.'s');
            exit();
        }

        flash($name.'_flash_msg',null,'Deleted successfully',flashclass('toast','success'));
        redirect($name.'s');
        exit();

    }else{
        redirect('auth/forbidden');
        exit();
    }
}

//validate date not greater than today
function validatedate($date){
    if($date > date('Y-m-d')){
        return false;
    }else{
        return true;
    }
}

//get unique no from database;
function getuniqueid($con,$field,$table,$cid,$bycenter = true){
    $sql = "SELECT COUNT(*) FROM $table WHERE Deleted = 0";
    if($bycenter){
        $sql .= " AND (CenterId = :cid)";
    }
    $stmt = $con->prepare($sql);
    if($bycenter){
        $stmt->bindValue(':cid',$cid);
    }
    $stmt->execute();
    if((int)$stmt->fetchColumn() === 0){
        return 1;
    }else{
        $sql = "SELECT 
                    $field 
                FROM 
                    $table 
                WHERE 
                    Deleted = 0";
        if($bycenter){
            $sql .= " AND (CenterId = :cid)";
        }
        $sql .=" ORDER BY $field DESC";
        $stmt = $con->prepare($sql);
        if($bycenter){
            $stmt->bindValue(':cid',$cid);
        }
        if($stmt->execute()){
            return (int)$stmt->fetchColumn() + 1;
        }else{
            return 0;
        }
    }
}

//calculate vat based on vat type and amount
function calculatevat($type,$amount,$rate){
    $results = [];
    if((int)$type === 1){
        $results = [$amount,0,$amount];
    }elseif ((int)$type === 2) {
        $incamount = $amount;
        $excamount = ($amount)/(100 + $rate)*100;
        $vat = $incamount - $excamount;
        $results = [$excamount,$vat,$incamount];
    }elseif ((int)$type === 3) {
        $excamount = $amount;
        $incamount = ($amount)*(100 + $rate)/100;
        $vat = $incamount - $excamount;
        $results = [$excamount,$vat,$incamount];
    }
    return $results;
}

function getusermenuitems($con,$userid)
{
    $sql = 'SELECT 
                DISTINCT f.Module 
            FROM 
                userrights r INNER JOIN forms f on r.FormId = f.ID 
            WHERE (r.UserId = ?)';
    $stmt = $con->prepare($sql);
    $stmt->execute([$userid]);
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    $modules = array();
    foreach($results as $result) {
        array_push($modules,$result->Module);
    }
    return $modules;
}

function getmodulemenuitems($con,$userid,$module)
{
    $sql = 'SELECT f.FormName,
                   f.Path
            FROM   userrights r inner join forms f on r.FormId = f.ID
            WHERE  r.UserId = :usid AND (f.Module = :menu)
            ORDER BY f.MenuOrder';
    $stmt = $con->prepare($sql);
    $stmt->bindValue(':usid',$userid);
    $stmt->bindValue(':menu',$module);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function hassubmenus($con,$module)
{
    $count = getdbvalue($con,'SELECT count(*) FROM `forms` WHERE SubModule IS NOT NULL AND Module = ?',[$module]);
    if($count == 0){
        return false;
    }
    return true;
}

function getsubmenuitems($con,$module,$userid)
{
    $sql = 'SELECT 
                DISTINCT f.SubModule 
            FROM 
                userrights r INNER JOIN forms f on r.FormId = f.ID 
            WHERE (r.UserId = ?) AND (f.Module = ?)';
    $stmt = $con->prepare($sql);
    $stmt->execute([$userid,$module]);
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    $submodules = array();
    foreach($results as $result) {
        array_push($submodules,$result->SubModule);
    }
    return $submodules;
}

//load sub menu items
function getsubmenunavitems($con,$userid,$module,$sub)
{
    $sql = 'SELECT f.FormName,
                   f.Path
            FROM   userrights r inner join forms f on r.FormId = f.ID
            WHERE  r.UserId = :usid AND (f.Module = :menu) AND (f.SubModule = :sub)
            ORDER BY f.MenuOrder';
    $stmt = $con->prepare($sql);
    $stmt->bindValue(':usid',$userid);
    $stmt->bindValue(':menu',$module);
    $stmt->bindValue(':sub',$sub);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

//CHECK USER RIGHTS
function checkuserrights($con,$user,$form){
    $stmt = $con->prepare('SELECT COUNT(*) 
                           FROM vw_user_rights
                           WHERE (UserId = ?) AND (FormName = ?)');
    $stmt->execute([$user,$form]);
    $count = (int)$stmt->fetchColumn();
    if($count === 0){
        return false;
    }else{
        return true;
    }
}

function checkrights($model,$form){
    if((int)$_SESSION['usertypeid'] > 1 && !$model->CheckRights($form)){
        redirect('auth/unauthorized');
        exit;
    }
}

function alert($errormsg)
{
    return '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> '.$errormsg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    ';
}
//number formatter
function numberFormat($number){
    if(strpos($number,',') !== false){
       return str_replace(',','',$number);
    }
    return $number;
}