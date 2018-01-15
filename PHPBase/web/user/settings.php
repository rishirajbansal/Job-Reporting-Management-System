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

include_once(dirname(__FILE__) . "/../../classes/inputs/UserOrgInputs.php");
include_once(dirname(__FILE__) . "/../../classes/dao/UserSettingsDao.php");
include_once(dirname(__FILE__) . "/../../classes/base/Constants.php");
include_once(dirname(__FILE__) . "/../../classes/vo/Organization.php");

include_once("sessionMgmt.php");

require_once(dirname(__FILE__) . "/../commonInc/init.php");
require_once(dirname(__FILE__) . "/inc/config.ui.php");


/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('SET_MSG_1', TXT_U);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

//$page_nav["dashboard"]["active"] = true;
include("inc/nav.php");


/*------------- Form Submissions ---------*/

$userOrgInputs = new UserOrgInputs();
$userSettingsDao = new UserSettingsDao();
$orgDetails = new Organization();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$orgid = $_SESSION['orgid'];
$userOrgInputs->setIdorgs($orgid);

//Load Org Details
$loadFlag = $userSettingsDao->loadProfile($userOrgInputs);
if ($loadFlag){
    $orgDetails = $userSettingsDao->getOrgDetails();
}
else{
    if ($userSettingsDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
        $errMsgObject['msg'] = 'error';
        $errMsgObject['text'] = $userSettingsDao->getErrors();
    }
    else if ($userSettingsDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
        $errMsgObject['msg'] = 'criticalError';
        $errMsgObject['text'] = $userSettingsDao->getCriticalError();
    }
}
    

if (isset($_POST['formsubmit']) && isset($_POST['mode'])){
    
    preInputsInspection($userOrgInputs);
    
    $mode = $_POST['mode'];
    
    if ($mode == SETTINGS_MODE_NEWPASSWORD){
        
        $daoFlag = '';
        $daoFlag = $userSettingsDao->saveNewPassword($userOrgInputs);
        
        if ($daoFlag){
            if ($userSettingsDao->getMsgType() == SUBMISSION_MSG_TYPE_SUCCESS){
                $errMsgObject['msg'] = 'success';
                $errMsgObject['text'] = $userSettingsDao->getSuccessMessage();
            }
            else if ($userSettingsDao->getMsgType() == SUBMISSION_MSG_TYPE_COMPLETESUCCESS){
                $errMsgObject['msg'] = 'completeSuccess';
                $errMsgObject['text'] = $userSettingsDao->getCompleteMsg();
            }
        }
        else{
            if ($userSettingsDao->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
                $errMsgObject['msg'] = 'message';
                $errMsgObject['text'] = $userSettingsDao->getMessages();
            }
            else if ($userSettingsDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
                $errMsgObject['msg'] = 'error';
                $errMsgObject['text'] = $userSettingsDao->getErrors();
            }
            else if ($userSettingsDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
                $errMsgObject['msg'] = 'criticalError';
                $errMsgObject['text'] = $userSettingsDao->getCriticalError();
            }
        }
    }
    
}

/*------------- End Form Submissions ---------*/

?>


<!-- MAIN PANEL -->
<div id="main" role="main">
    
    <?php
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-cogs"></i></i> <?php echo getLocaleText('SET_MSG_2', TXT_U); ?> </h1>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <?php
        
        if ($loadFlag) { ?>
        
            <section id="widget-grid" class="">

                <div class="row">

                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="jarviswidget" id="wid-id-0" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">

                            <header>
                                <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                                <h2><?php echo getLocaleText('SET_MSG_3', TXT_U); ?> </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body" style="min-height: 450px;">

                                    <div class="tabs-left">

                                        <ul class="nav nav-tabs tabs-left" id="" style="min-height: 400px;background-color: #f9f9f9">
                                            <li class="active">
                                                <a href="#tab-r1" data-toggle="tab"><?php echo getLocaleText('SET_MSG_4', TXT_U); ?></a>
                                            </li>
                                            <li>
                                                <a href="#tab-r2" data-toggle="tab"><?php echo getLocaleText('SET_MSG_5', TXT_U); ?></a>
                                            </li>
                                        </ul>

                                        <div class="tab-content" style="margin-left: 210px;">

                                            <div class="tab-pane active" id="tab-r1">
                                                
                                                <form class="form-horizontal" id="profileForm" name="profileForm" method="post" action="">
                                                    <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                                    <input type="hidden" name="mode" id="mode" value=""/>
                                                    
                                                    <h3><?php echo getLocaleText('SET_MSG_6', TXT_U); ?> </h3>
                                                    <p><?php echo getLocaleText('SET_MSG_7', TXT_U); ?> </p>
                                                    
                                                    <fieldset style="min-height: 260px">
                                                        
                                                        <legend></legend>
                                                        <br/>
                                                    
                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label"><strong><?php echo getLocaleText('SET_MSG_8', TXT_U); ?> : </strong></label>
                                                            <label class="col-md-5 control-label" style="text-align: left"><?php echo $orgDetails->getName(); ?></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label"><strong><?php echo getLocaleText('SET_MSG_9', TXT_U); ?> : </strong></label>
                                                            <label class="col-md-5 control-label" style="text-align: left"><?php echo $orgDetails->getPhone(); ?></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label"><strong><?php echo getLocaleText('SET_MSG_10', TXT_U); ?> : </strong></label>
                                                            <label class="col-md-5 control-label" style="text-align: left"><?php echo $orgDetails->getEmail(); ?></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label"><strong><?php echo getLocaleText('SET_MSG_11', TXT_U); ?> : </strong></label>
                                                            <label class="col-md-5 control-label" style="text-align: left"><?php echo $orgDetails->getUsername(); ?></label>
                                                        </div>
                                                    </fieldset>
                                                    
                                                    <div class="alert alert-info fade in">
                                                        <i class="fa-fw fa fa-info"></i>
                                                        <strong><?php echo getLocaleText('SET_MSG_12', TXT_U); ?></strong> <?php echo getLocaleText('SET_MSG_13', TXT_U); ?>
                                                    </div>
                                                    
                                                </form>
                                                
                                            </div>

                                            <div class="tab-pane" id="tab-r2">

                                                <form class="form-horizontal" id="chgPwdForm" name="chgPwdForm" method="post" action="">
                                                    <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                                    <input type="hidden" name="mode" id="mode" value=""/>

                                                    <h3><?php echo getLocaleText('SET_MSG_14', TXT_U); ?></h3>
                                                    <p><?php echo getLocaleText('SET_MSG_15', TXT_U); ?></p>

                                                    <fieldset style="min-height: 260px">

                                                        <legend></legend>
                                                        <br/>

                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label"><?php echo getLocaleText('SET_MSG_16', TXT_U); ?> </label>
                                                            <div class="col-md-4">
                                                                <div class="input-group input-group-md">
                                                                    <span class="input-group-addon "><i class="fa fa-lock fa-fw"></i></span>
                                                                    <input class="form-control" placeholder="<?php echo getLocaleText('SET_MSG_16', TXT_U); ?>" type="password" name="newPwd" id="newPwd" value="">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <br/><br/>

                                                        <div class="form-group">
                                                            <label class="col-md-2 control-label"><?php echo getLocaleText('SET_MSG_17', TXT_U); ?> </label>
                                                            <div class="col-md-4">
                                                                <div class="input-group input-group-md">
                                                                    <span class="input-group-addon "><i class="fa fa-lock fa-fw"></i></span>
                                                                    <input class="form-control" placeholder="<?php echo getLocaleText('SET_MSG_17', TXT_U); ?>" type="password" name="confPwd" id="confPwd" value="">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </fieldset>

                                                    <div class="form-actions" style="border: none;background: none;border-top: 1px solid rgba(0,0,0,.1);">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <button type="button" class="btn btn-default bg-color-blueLight txt-color-white" id="btnCancel-pwd">
                                                                    <i class="glyphicon glyphicon-remove"></i> <?php echo getLocaleText('SET_MSG_BTN_1', TXT_U); ?>
                                                                </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <button type="button" class="btn btn-default" id="btnReset-pwd">
                                                                    <i class="fa fa-refresh"></i> <?php echo getLocaleText('SET_MSG_BTN_2', TXT_U); ?>
                                                                </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <button type="button" class="btn btn-warning" id="btnUpdate-pwd">
                                                                    <i class="fa fa-save"></i> <?php echo getLocaleText('SET_MSG_BTN_3', TXT_U); ?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </article>

                </div>

            </section>
        
        <?php
        }
        ?>
        
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

        var $validator = $("#chgPwdForm").validate({

            rules: {
                newPwd: {
                    required: true,
                    minlength : 5,
                    maxlength : 20
                },
                confPwd: {
                    required: true,
                    minlength : 5,
                    maxlength : 20,
                    equalTo : '#newPwd'
                }
            },

            messages: {
                newPwd: {
                    required: "<?php echo getLocaleText('SET_MSG_VALID_1', TXT_U); ?>",
                    minlength: "<?php echo getLocaleText('SET_MSG_VALID_2', TXT_U); ?>",
                    maxlength: "<?php echo getLocaleText('SET_MSG_VALID_3', TXT_U); ?>"
                },
                confPwd: {
                    required: "<?php echo getLocaleText('SET_MSG_VALID_4', TXT_U); ?>",
                    equalTo : "<?php echo getLocaleText('SET_MSG_VALID_5', TXT_U); ?>"
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

        $('#btnUpdate-pwd').on('click', function () {
            var $valid = $("#chgPwdForm").valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            else{
                document.getElementById('mode').value = '<?php echo SETTINGS_MODE_NEWPASSWORD; ?>';
                document.forms['chgPwdForm']['formsubmit'].value = 'chgPwd';
                $("#chgPwdForm").submit();
            }
        });
        
        $('#btnCancel-pwd').on('click', function () {
            location.href = 'home.php';
        });
        
        $('#btnReset-pwd').on('click', function () {
            $validator.resetForm();
            document.getElementById('chgPwdForm').reset();
        });
        

    })

</script>

<?php

    function preInputsInspection(UserOrgInputs $userOrgInputs){
        
        $userOrgInputs->setIn_password(mysql_real_escape_string($_POST['newPwd']));

    }

?>
