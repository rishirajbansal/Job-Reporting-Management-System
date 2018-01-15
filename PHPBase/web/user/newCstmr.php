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
include_once(dirname(__FILE__) . "/../../classes/dao/UserOrgDao.php");
include_once(dirname(__FILE__) . "/../../classes/base/Constants.php");
include_once(dirname(__FILE__) . "/../../classes/vo/UserEntity.php");

include_once("sessionMgmt.php");

require_once(dirname(__FILE__) . "/../commonInc/init.php");
require_once(dirname(__FILE__) . "/inc/config.ui.php");



/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('NEW_CSTMR_MSG_1', TXT_U);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["clients"]["active"] = true;
include("inc/nav.php");

/*------------- Form Submissions ---------*/

$userOrgInputs = new UserOrgInputs();
$userOrgDao = new UserOrgDao();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$orgid = $_SESSION['orgid'];

$validationRules = ' ';
$validationMessages = ' ';

$isDetailsFetched = FALSE;
$cstmrid = '';
$mode = '';

//Load prerequisties for Customer creation
$userOrgInputs->setIdorgs($orgid);
$loadFlag = $userOrgDao->fetchCustomerDynaDetails($userOrgInputs);
if ($loadFlag){
    $userEntities = $userOrgDao->getUserEntities();
}
else{
    if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
        $errMsgObject['msg'] = 'error';
        $errMsgObject['text'] = $userOrgDao->getErrors();
    }
    else if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
        $errMsgObject['msg'] = 'criticalError';
        $errMsgObject['text'] = $userOrgDao->getCriticalError();
    }
}

if (isset($_POST['formsubmit']) && $_POST['formsubmit'] != 'cstmrListForm' && $_POST['formsubmit'] != 'viewCstmrDetailsForm'){
    
    $mode = $_POST['mode'];
    
    preInputsInspection($userOrgInputs, $userOrgDao);
    
    $cstmrid = $userOrgInputs->getIdcstmrs();
    
    $saveFlag = $userOrgDao->saveNewCustomer($userOrgInputs, $mode);
    
    if ($saveFlag){
        
        if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_SUCCESS){
            $errMsgObject['msg'] = 'success';
            $errMsgObject['text'] = $userOrgDao->getSuccessMessage();
        }
        else if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_COMPLETESUCCESS){
            $errMsgObject['msg'] = 'completeSuccess';
            $errMsgObject['text'] = $userOrgDao->getCompleteMsg();
        }
        
    }
    else{
        if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
            $errMsgObject['msg'] = 'message';
            $errMsgObject['text'] = $userOrgDao->getMessages();
        }
        else if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
            $errMsgObject['msg'] = 'error';
            $errMsgObject['text'] = $userOrgDao->getErrors();
        }
        else if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
            $errMsgObject['msg'] = 'criticalError';
            $errMsgObject['text'] = $userOrgDao->getCriticalError();
        }
        
    }
    
    $fetchFlag = $userOrgDao->fetchCustomerDetails($userOrgInputs, $mode);
            
    if ($fetchFlag){
        $userEntities = $userOrgDao->getUserEntities();
        $isDetailsFetched = TRUE;
    }
    
}
else if (!empty($loadFlag) && isset($_POST['formsubmit']) && ($_POST['formsubmit'] == 'cstmrListForm' || $_POST['formsubmit'] == 'viewCstmrDetailsForm') && isset($_POST['mode'])){
    
    if (isset($_POST['cstmrid'])){
        $cstmrid = $_POST['cstmrid'];
    }
    $mode = $_POST['mode'];
    
    $userOrgInputs->setIdcstmrs($cstmrid);
    
    $fetchFlag = $userOrgDao->fetchCustomerDetails($userOrgInputs, $mode);
            
    if ($fetchFlag){
        $userEntities = $userOrgDao->getUserEntities();
        $isDetailsFetched = TRUE;
    }
    else{
        if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
            $errMsgObject['msg'] = 'message';
            $errMsgObject['text'] = $userOrgDao->getMessages();
        }
        else if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
            $errMsgObject['msg'] = 'error';
            $errMsgObject['text'] = $userOrgDao->getErrors();
        }
        else if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
            $errMsgObject['msg'] = 'criticalError';
            $errMsgObject['text'] = $userOrgDao->getCriticalError();
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
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-building"></i> 
                    <?php
                    if ($mode == USERDYNAFIELDS_LIST_MODE_NEW){ ?>
                         <?php echo getLocaleText('NEW_CSTMR_MSG_2', TXT_U); ?>
                    <?php
                    } 
                    else if ($mode == USERDYNAFIELDS_LIST_MODE_EDIT){ ?>
                        <?php echo getLocaleText('NEW_CSTMR_MSG_3', TXT_U); ?>
                    <?php
                    }
                    ?>
                </h1>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <a href="customers.php" class="btn bg-color-blueLight txt-color-white btn-lg pull-right header-btn hidden-mobile"><i class="fa fa-arrow-circle-left "></i> &nbsp;<?php echo getLocaleText('NEW_CSTMR_MSG_4', TXT_U); ?></a>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <?php
        
        if ($loadFlag && $isDetailsFetched){ ?>
        
            <section id="widget-grid" class="">

                <div class="row">

                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">

                            <header>
                                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                                <h2><?php echo getLocaleText('NEW_CSTMR_MSG_5', TXT_U); ?> </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body" style="padding-left: 20px;padding-right: 20px;">

                                    <form class="form-horizontal" id="newCstmrForm" name="newCstmrForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                        <input type="hidden" name="cstmrid" id="wrkrid" value="<?php echo $cstmrid; ?>"/>
                                        <input type="hidden" name="mode" id="mode" value="<?php echo $_POST['mode']; ?>"/>

                                        <h3><strong><?php echo getLocaleText('NEW_CSTMR_MSG_6', TXT_U); ?></strong></h3>
                                        <p><?php echo getLocaleText('NEW_CSTMR_MSG_7', TXT_U); ?>  </p>

                                        <?php include("dynaComps.php"); ?>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </article>

                </div>

            </section>
        
        <?php
        }
        else if (empty ($userOrgDao->getMsgType())) { ?>
            <div class="alert alert-danger fade in">
                <i class="fa-fw fa fa-times"></i>
                <?php echo getLocaleText('NEW_CSTMR_MSG_8', TXT_U); ?>
            </div>
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
        
        var $validator = $("#newCstmrForm").validate({

                rules: {
                    <?php echo $validationRules; ?>
                },

                messages: {
                    <?php echo $validationMessages; ?>
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
            
        jQuery.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[A-Za-z0-9\s`~!@#$%^&*()+={}'".\/?\\-]+$/i.test(value);
        }, "");
        
            
        $('#dp-div').on('click', function () {
            $('#ui-datepicker-div').css({'z-index': 1000});
        });
        
        $('#btnCancel').on('click', function () {
            location.href = 'customers.php';
        });
        
        $('#btnSave').on('click', function () {
            var $valid = $("#newCstmrForm").valid();
            //alert($valid);
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            else{
                document.forms['newCstmrForm']['formsubmit'].value = 'formsubmit';
                $("#newCstmrForm").submit();
            }
        });
        
        $('#btnReset').on('click', function () {
            $validator.resetForm();
            document.getElementById('newCstmrForm').reset();
        });
        
    })
    
    function jtime(timeId){
        
        $('#'+timeId).timepicker({
            minuteStep: 1
            });
        
    }

</script>


<?php

    function preInputsInspection(UserOrgInputs $userOrgInputs, UserOrgDao $userOrgDao){
        
        $postedIdValues = '';
        
        $userEntities = $userOrgDao->getUserEntities();
        foreach ($userEntities as $userEntity){
            $htmlname = $userEntity->getHtmlName();
            if (isset($_POST[$htmlname])){
                $postedIdValues = $postedIdValues . $userEntity->getIdDynaFields() . ':' . $_POST[$htmlname] . '|';
            }
        }
        
        $postedIdValues = substr($postedIdValues, 0, strlen($postedIdValues)-1);
        
        $userOrgInputs->setPostedIdValues($postedIdValues);
        
        if (isset($_POST['cstmrid'])){
            $userOrgInputs->setIdcstmrs($_POST['cstmrid']);
        }
        
    }
    
?>