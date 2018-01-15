<?php

require_once(dirname(__FILE__) . "/../commonInc/init.php");


if (session_id() == "") {
    session_start();
}

if (empty($_SESSION['loggedIn']) || ($_SESSION['loggedIn'] != 1) || empty($_SESSION['orgid'])) {    
    header("location: login.php");
    exit();
}

$config_sessionTimeout = $userSessionTimeout;
if (!validateSession($_SESSION['begin'], $config_sessionTimeout)){
    header("location: logout.php?st=1");
    exit();
}
else{
    $_SESSION['begin'] = time();
}


function validateSession($sessionTime, $userSessionTimeout){
    
    $secondsInactive = time() - $sessionTime;
    
    $sessionExpiry = $userSessionTimeout * 60;
    
    if ($secondsInactive >= $sessionExpiry){
        return false;
    }
    else{
        return true;
    }
    
}



?>
