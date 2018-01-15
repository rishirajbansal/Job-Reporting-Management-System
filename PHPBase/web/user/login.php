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

include_once(dirname(__FILE__) . "/../../classes/inputs/UserOrgInputs.php");
include_once(dirname(__FILE__) . "/../../classes/dao/LoginDao.php");
include_once(dirname(__FILE__) . "/../../classes/base/Constants.php");

ob_start();
/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('LOGIN_MSG_1', TXT_U);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";
$no_main_header = true;
$page_html_prop = array("id"=>"extr-page", "class"=>"animated fadeInDown");

include("inc/header.php");


/*------------- Form Submissions ---------*/

$userOrgInputs = new UserOrgInputs();
$loginDao = new LoginDao();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

if (session_id() == "") {
    session_start();
    
    if (isset($_GET['redirect']) && $_GET['redirect'] == 'lng' && isset($_GET['loc'])){
        
    }
    else{
        session_destroy();
    }
}

if (isset($_POST['formsubmit'])){

    preInputsInspection($userOrgInputs);
    
    $daoFlag = $loginDao->login($userOrgInputs);
    
    if ($daoFlag){
        session_id('U-'.$loginDao->getLoggedInOrgid().'-'. time());
        session_start();
        $_SESSION['loggedIn'] = 1;
        $_SESSION['begin'] = time();
        $_SESSION['orgid'] = $loginDao->getLoggedInOrgid();
        $_SESSION['orgname'] = $loginDao->getLoggedInOrgname();
        $_SESSION['logo'] = '';
        $_SESSION['locale'] = DEFAULT_LOCALE;
        
        $_SESSION['queryFilter'] = $loginDao->getLoggedInQueryFilters();
        $_SESSION['allQueryFilter'] = $loginDao->getQueryFilters();

        $errMsgObject['msg'] = 'completeSuccess';
        
        $dir = '../admin/orgdata/' . 'org_' . $loginDao->getLoggedInOrgid();
        $logoDir = $dir . '/logo';
        $file = '';
        if ($handle = opendir($logoDir)){
            while (false !== ($entry = readdir($handle))) {
                if (strpos($entry, 'logo') !== FALSE){
                    $file = $entry;
                }
            }
            closedir($handle);
        }
        if (!empty($file)){
            $_SESSION['logo'] = substr($logoDir, 2) . '/' . $file;
        }

        header("location: home.php");
        exit();
    }
    else{
        if ($loginDao->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
            $errMsgObject['msg'] = 'message';
            $errMsgObject['text'] = $loginDao->getMessages();
        }
        else if ($loginDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
            $errMsgObject['msg'] = 'error';
            $errMsgObject['text'] = $loginDao->getErrors();
        }
        else if ($loginDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
            $errMsgObject['msg'] = 'criticalError';
            $errMsgObject['text'] = $loginDao->getCriticalError();
        }
    }
    
}

//I18n
if (isset($_GET['redirect']) && $_GET['redirect'] == 'lng' && isset($_GET['loc'])){
    
    $_SESSION['locale'] = $_GET['loc'];
    $referer = $_SERVER['HTTP_REFERER'];
    $referer = substr($referer, strrpos($referer, '/')+1);
    header('location: ' . $referer);
    exit();
    
}


/*------------- End Form Submissions ---------*/

?>


<header id="header">
    <div id="apptitle" class="txt-color-red login-header-big" style="margin-left: 10px;margin-top: 15px;width: auto;">
        <span><h1 style="color: #1f629d;"><strong><?php echo getLocaleText('LOGIN_MSG_2', TXT_U); ?></strong></h1></span>
    </div>
</header>

<!-- MAIN PANEL -->
<div id="main" role="main">
    <br/><br/>
    <div style="margin-left: 13px;margin-right: 13px;">
        <?php include ('renderMsgsErrs.php'); ?>
    </div>
    
    <br/><br/>
    
    <!-- MAIN CONTENT -->
    <div id="content" class="container" style="max-width: 640px;">
        
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            
            <div class="well no-padding">
                
                <form id="loginForm" name="loginForm" method="post" action="" class="hor-form login-form">
                    <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                    
                    <header>
                        <h3 class="text-primary" style="margin: 0px;">Sign In</h3>
                    </header>
                    
                    <fieldset>
                        
                        <div class="form-group">
                            <label><?php echo getLocaleText('LOGIN_MSG_3', TXT_U); ?> </label>
                            <div class="input-group input-group-md">
                                <input class="form-control" placeholder="<?php echo getLocaleText('LOGIN_MSG_3', TXT_U); ?>" type="text" name="l_org" id="l_org" >
                                <span class="input-group-addon "><i class="fa fa-user fa-building"></i></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label> <?php echo getLocaleText('LOGIN_MSG_4', TXT_U); ?></label>
                            <div class="input-group input-group-md">
                                <input class="form-control" placeholder="<?php echo getLocaleText('LOGIN_MSG_4', TXT_U); ?>" type="text" name="l_uname" id="l_uname" >
                                <span class="input-group-addon "><i class="fa fa-user fa-fw"></i></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo getLocaleText('LOGIN_MSG_5', TXT_U); ?> </label>
                            <div class="input-group input-group-md">
                                <input class="form-control" placeholder="<?php echo getLocaleText('LOGIN_MSG_5', TXT_U); ?>" type="password" name="l_pwd" id="l_pwd" >
                                <span class="input-group-addon "><i class="fa fa-lock fa-fw"></i></span>
                            </div>
                        </div>
                        
                        <br/><br/>
                        
                    </fieldset>
                    
                    <footer>
                        <div style="float: right;">
                            <button type="button" class="btn btn-default" id="btnReset" style="color: #000000;font-weight: 400;">
                                <i class="fa fa-refresh fa-fw"></i> <?php echo getLocaleText('LOGIN_MSG_BTN_1', TXT_U); ?>
                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-primary" id="btnSubmit" style="font-weight: 400;">
                                <i class="fa fa-arrow-circle-right fa-fw"></i> <?php echo getLocaleText('LOGIN_MSG_BTN_2', TXT_U); ?>
                            </button>
                        </div>
                    </footer>
                    
                </form>
                
            </div>
            
        </div>
        
    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->


<?php
    include("inc/footer.php");
    include("../commonInc/scripts.php"); 
?>

<script>

    $(document).ready(function() {

        pageSetUp();
        
        var $validator = $("#loginForm").validate({

            rules: {
                l_org: {
                    required: true
                },
                l_uname: {
                    required: true,
                    minlength : 5,
                    maxlength : 20
                },
                l_pwd: {
                    required: true,
                    minlength : 5,
                    maxlength : 20
                }
            },

            messages: {
                l_org: {
                    required: "<?php echo getLocaleText('LOGIN_MSG_VALID_7', TXT_U); ?>"
                },                
                l_uname: {
                    required: "<?php echo getLocaleText('LOGIN_MSG_VALID_1', TXT_U); ?>",
                    minlength: "<?php echo getLocaleText('LOGIN_MSG_VALID_2', TXT_U); ?>",
                    maxlength: "<?php echo getLocaleText('LOGIN_MSG_VALID_3', TXT_U); ?>"
                },
                l_pwd: {
                    required: "<?php echo getLocaleText('LOGIN_MSG_VALID_4', TXT_U); ?>",
                    minlength: "<?php echo getLocaleText('LOGIN_MSG_VALID_5', TXT_U); ?>",
                    maxlength: "<?php echo getLocaleText('LOGIN_MSG_VALID_6', TXT_U); ?>"
                }
            },

            highlight: function (element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },

            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } 
                else {
                    error.insertAfter(element);
                }
            }

        });
        
        $('#btnSubmit').on('click', function () {
            var $valid = $("#loginForm").valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            else{
                document.forms['loginForm']['formsubmit'].value = 'submit';
                $("#loginForm").submit();
            }
        });
        
        $('#btnReset').on('click', function () {
            $validator.resetForm();
            document.getElementById('loginForm').reset();
        });


    })

</script>

<?php

    function preInputsInspection(UserOrgInputs $userOrgInputs){
        
        $userOrgInputs->setIn_orgname(mysql_real_escape_string($_POST['l_org']));
        $userOrgInputs->setIn_username(mysql_real_escape_string($_POST['l_uname']));
        $userOrgInputs->setIn_password(mysql_real_escape_string($_POST['l_pwd']));

    }
ob_end_flush();
?>