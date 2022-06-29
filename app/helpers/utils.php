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
        echo 'toast-header bg-'. $type . '';
    }elseif($flash === 'alert'){
        echo 'alert alert-'. $type .' alert-dismissible fade show';
    }
}