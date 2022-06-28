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