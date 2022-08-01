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