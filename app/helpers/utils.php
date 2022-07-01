<?php
//url redirect
function redirect($page){
    header('location: ' . URLROOT . '/' . $page);
}

//add css classes for data validation
function inputvalidation($data,$err){
    if (!empty($err)){
        return 'is-invalid';
    }elseif (empty($err) && !empty($data)){ 
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