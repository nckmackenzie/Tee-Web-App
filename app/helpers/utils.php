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
function DeleteModal($route){
    echo '
    <div class="modal fade" id="centermodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="'.$route.'" method="post" autocomplete="off">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myCenterModalLabel">Delete</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete?</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" value="" id="id" />
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    ';
}