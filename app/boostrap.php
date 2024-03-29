<?php
//load config 
require_once 'config/config.php';
//load functions
require_once 'helpers/utils.php';
require_once 'helpers/session_handler.php';
// require_once 'libraries/Core.php';
// require_once 'libraries/Controller.php';
// require_once 'libraries/Database.php';

//AUTOLOAD CORE LIBRARIES
spl_autoload_register(function($className){
    require_once 'libraries/' . $className . '.php';
});