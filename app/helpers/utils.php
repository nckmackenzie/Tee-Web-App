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

function hash_equals_custom($knownString, $userString) {
    if (function_exists('mb_strlen')) {
        $kLen = mb_strlen($knownString, '8bit');
        $uLen = mb_strlen($userString, '8bit');
    } else {
        $kLen = strlen($knownString);
        $uLen = strlen($userString);
    }
    if ($kLen !== $uLen) {
        return false;
    }
    $result = 0;
    for ($i = 0; $i < $kLen; $i++) {
        $result |= (ord($knownString[$i]) ^ ord($userString[$i]));
    }
    return 0 === $result;
}

function encrypt ($pure_string, $encryption_key) {
    $cipher     = 'AES-256-CBC';
    $options    = OPENSSL_RAW_DATA;
    $hash_algo  = 'sha256';
    $sha2len    = 32;
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($pure_string, $cipher, $encryption_key, $options, $iv);
    $hmac = hash_hmac($hash_algo, $ciphertext_raw, $encryption_key, true);
    return $iv.$hmac.$ciphertext_raw;
}

function decrypt ($encrypted_string, $encryption_key) {
    $cipher     = 'AES-256-CBC';
    $options    = OPENSSL_RAW_DATA;
    $hash_algo  = 'sha256';
    $sha2len    = 32;
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($encrypted_string, 0, $ivlen);
    $hmac = substr($encrypted_string, $ivlen, $sha2len);
    $ciphertext_raw = substr($encrypted_string, $ivlen+$sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $encryption_key, $options, $iv);
    $calcmac = hash_hmac($hash_algo, $ciphertext_raw, $encryption_key, true);
    if(function_exists('hash_equals')) {
        if (hash_equals($hmac, $calcmac)) return $original_plaintext;
    } else {
        if (hash_equals_custom($hmac, $calcmac)) return $original_plaintext;
    }
}

