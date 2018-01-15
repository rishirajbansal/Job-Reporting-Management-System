<?php

/* Licensed To: ThoughtExecution & 9sistemes
* Authored By: Rishi Raj Bansal
* Developed in: Jul-Aug-Sep 2016
 * ===========================================================================
 * This is FULLY owned and COPYRIGHTED by ThoughtExecution
 * This code may NOT be RESOLD or REDISTRUBUTED under any circumstances, and is only to be used with this application
 * Using the code from this application in another application is strictly PROHIBITED and not PERMISSIBLE
 * ===========================================================================
*/

require_once(dirname(__FILE__) . "/../commonInc/init.php");
require_once(dirname(__FILE__) . "/inc/config.ui.php");

include_once(dirname(__FILE__) . "/../../classes/inputs/OrgInputs.php");
include_once(dirname(__FILE__) . "/../../classes/dao/LoginDao.php");
include_once(dirname(__FILE__) . "/../../classes/base/Constants.php");

ob_start();
/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('LOGOUT_MSG_1', TXT_A);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";
$no_main_header = true;
$page_html_prop = array("id"=>"extr-page", "class"=>"animated fadeInDown");

include("inc/header.php");


/*------------- Form Submissions ---------*/


if (session_id() == "") {
    session_start();
}

if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1)) {
    
    /*unset($_SESSION['loggedIn']);
    unset($_SESSION['isAdmin']);
    unset($_SESSION['begin']);
    unset($_SESSION['locale']); */
    
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();

}
else{
    header("location: login.php");
    exit();
}


/*------------- End Form Submissions ---------*/

?>

<header id="header">
    <div id="apptitle" class="txt-color-red login-header-big" style="margin-left: 10px;margin-top: 15px;width: auto;">
        <span><h1 style="color: #1f629d;"><?php echo getLocaleText('LOGOUT_MSG_2', TXT_A); ?></h1></span>
    </div>
    <div id="logo-group" style="float: right">
        <span id="logo" style="margin-top: 12px;"> <img src="<?php echo ASSETS_URL; ?>/img/misc/logo.png" alt="<?php echo getLocaleText('LOGOUT_MSG_3', TXT_A); ?>"> </span>
    </div>
    
</header>

<!-- MAIN PANEL -->
<div id="main" role="main">
    <br/><br/>
    <div style="margin-left: 13px;margin-right: 13px;">
        <?php include ('renderMsgsErrs.php'); ?>
    </div>
    
    <br/><br/>
    
    <div id="content">
    
        <br/>
        <div class="col-md-12">
                
            <div class="row">
                
                <div class="col-md-6 col-md-offset-3">
                    <div class="well" style="background-color: #f7f7f7;">
                        <div class="row">

                            <div class="col-sm-12 ">
                                <br/>
                                <?php
                                if (isset($_GET['st'])){ ?>
                                    <div class="alert alert-warning alert-block">
                                        <br/>
                                        <h4 class="alert-heading"></h4>
                                        <br/>
                                        <h5 style="font-weight: 400;"><?php echo getLocaleText('LOGOUT_MSG_5-1', TXT_A); ?> <span class="txt-color-red"><?php echo $sessionTimeout; ?><?php echo getLocaleText('LOGOUT_MSG_5-2', TXT_A); ?> </span></h5>
                                        <br/>
                                    </div>
                                <?php
                                }
                                else { ?>
                                    <div class="alert alert-success alert-block">
                                        <br/>
                                        <h4 class="alert-heading"><?php echo getLocaleText('LOGOUT_MSG_6', TXT_A); ?></h4> 
                                        <br/>
                                    </div>
                                    <br/>
                                    <h1 class="txt-color-red login-header-big text-center"><?php echo getLocaleText('LOGOUT_MSG_7', TXT_A); ?></h1>
                                <?php
                                }
                                ?>
                                <br/>
                            </div>

                        </div>
                    </div>
                    <br/><br/><br/>
                    
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-lg" id="btnSubmit" style="font-weight: 400;" onclick="javascript:location.href='login.php'">
                            <i class="fa fa-arrow-circle-right fa-fw"></i> <?php echo getLocaleText('LOGOUT_MSG_BTN_1', TXT_A); ?>
                        </button>
                    </div>
                    
                </div>

            </div>
            
        </div>
        
    </div>

</div>
<!-- END MAIN PANEL -->


<?php
    include("inc/footer.php");
    include("../commonInc/scripts.php");
    ob_end_flush();
?>

<script>

    $(document).ready(function() {

        pageSetUp();
        
    })

</script>
