<?php

require_once(dirname(__FILE__) . "/../commonInc/init.php");


if (session_id() == "") {
    session_start();
}

if (empty($_SESSION['loggedIn']) || ($_SESSION['loggedIn'] != 1) || empty($_SESSION['isAdmin']) || ($_SESSION['isAdmin'] != 1)) {    
    header("location: login.php");
    exit();
}

$config_sessionTimeout = $sessionTimeout;
if (!validateSession($_SESSION['begin'], $config_sessionTimeout)){
    header("location: logout.php?st=1");
    exit();
}
else{
    $_SESSION['begin'] = time();
}


function validateSession($sessionTime, $sessionTimeout){
    
    $secondsInactive = time() - $sessionTime;
    
    $sessionExpiry = $sessionTimeout * 60;
    
    if ($secondsInactive >= $sessionExpiry){
        return false;
    }
    else{
        return true;
    }
    
}



?>
