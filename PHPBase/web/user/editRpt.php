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

include_once("sessionMgmt.php");

require_once(dirname(__FILE__) . "/../commonInc/init.php");
require_once(dirname(__FILE__) . "/inc/config.ui.php");


/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('EDIT_RPT_MSG_1', TXT_U);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["reporting"]["active"] = true;
$page_nav["reporting"]["sub"]["dispreports"]["active"] = true;
include("inc/nav.php");

/*------------- Form Submissions ---------*/

$userOrgInputs = new UserOrgInputs();
$userOrgDao = new UserOrgDao();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$validationRules = ' ';
$validationMessages = ' ';

$orgid = $_SESSION['orgid'];

$isDetailsFetched = FALSE;
$rptid = '';
$mode = '';

$userOrgInputs->setIdorgs($orgid);

//Load prerequisties for Report Editing
$loadFlag = $userOrgDao->fetchReportDynaDetails($userOrgInputs);
if ($loadFlag){
    $userEntities = $userOrgDao->getAllUserEntities();
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



if (isset($_POST['formsubmit']) && $_POST['formsubmit'] != 'rptListForm' && $_POST['formsubmit'] != 'viewRptDetailsForm'){
    
    $mode = $_POST['mode'];
    
    if (isset($_POST['rptid'])){
        $rptid = $_POST['rptid'];
    }
    $userOrgInputs->setIdrpts($rptid);
    $userOrgDao->fetchReportDetails($userOrgInputs, $mode);
    
    preInputsInspection($userOrgInputs, $userOrgDao);
    
    $saveFlag = $userOrgDao->saveReport($userOrgInputs, $mode);
    
    if ($saveFlag){
        
        if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_SUCCESS){
            $errMsgObject['msg'] = 'success';
            $errMsgObject['text'] = $userOrgDao->getSuccessMessage();
        }
        else if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_COMPLETESUCCESS){
            $errMsgObject['msg'] = 'completeSuccess';
            $errMsgObject['text'] = $userOrgDao->getCompleteMsg();
        }
        
        $userOrgDao->setUserEntities(null);
        $fetchFlag = $userOrgDao->fetchReportDetails($userOrgInputs, $mode);

        if ($fetchFlag){
            $userEntities = $userOrgDao->getUserEntities();
            $isDetailsFetched = TRUE;
        }

        if ($mode == ORG_USERRPT_LIST_MODE_EDIT_PUBLISH){
            $publishFlag = $userOrgDao->publishReport($userOrgInputs);
            
            if ($publishFlag){
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
                if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
                    $errMsgObject['msg'] = 'error';
                    $errMsgObject['text'] = $userOrgDao->getErrors();
                }
                else if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
                    $errMsgObject['msg'] = 'criticalError';
                    $errMsgObject['text'] = $userOrgDao->getCriticalError();
                }
            }
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
    
}
else if (isset($_POST['formsubmit']) && ($_POST['formsubmit'] == 'rptListForm' || $_POST['formsubmit'] == 'viewRptDetailsForm') && isset($_POST['mode'])){
    
    if (isset($_POST['rptid'])){
        $rptid = $_POST['rptid'];
    }
    $mode = $_POST['mode'];
    
    $userOrgInputs->setIdrpts($rptid);
    
    $fetchFlag = $userOrgDao->fetchReportDetails($userOrgInputs, $mode);
            
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
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-tag"></i><?php echo getLocaleText('EDIT_RPT_MSG_2', TXT_U); ?> </h1>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <a href="dispReports.php" class="btn bg-color-blueLight txt-color-white btn-lg pull-right header-btn hidden-mobile"><i class="fa fa-arrow-circle-left "></i> &nbsp;<?php echo getLocaleText('EDIT_RPT_MSG_3', TXT_U); ?></a>
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
                                <h2><?php echo getLocaleText('EDIT_RPT_MSG_4', TXT_U); ?> - <strong style="color: #95c0d6"><i><?php echo $userOrgDao->generateReportNo($userOrgInputs->getIdrpts()); ?></i></strong> </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body" style="padding-left: 20px;padding-right: 20px;">

                                    <form class="form-horizontal" id="rptForm" name="rptForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                        <input type="hidden" name="rptid" id="rptid" value="<?php echo $rptid; ?>"/>
                                        <input type="hidden" name="mode" id="mode" value="<?php echo $_POST['mode']; ?>"/>

                                        <h3><strong><?php echo getLocaleText('EDIT_RPT_MSG_5', TXT_U); ?></strong></h3>
                                        <p><?php echo getLocaleText('EDIT_RPT_MSG_6', TXT_U); ?>  </p>

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
                <?php echo getLocaleText('EDIT_RPT_MSG_7', TXT_U); ?>
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

<script src="<?php echo ASSETS_URL; ?>/js/plugin/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>

<script>

    $(document).ready(function() {
        
        pageSetUp();
        
        var $validator = $("#rptForm").validate({

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
        
        /*jQuery.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[A-Za-z0-9\s`~!@#$%^&*()+={}'".\/?\\-]+$/i.test(value);
        }, "");*/

        //Message string is again provided here as to handle the messes for dyna 2TB boxes
        jQuery.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[A-Za-z0-9\s`~!@#$%^&*()+={}'".\/?\\-]+$/i.test(value);
        }, "<?php echo getLocaleText('EDIT_RPT_MSG_VALID_1', TXT_U); ?>");
        
        
        $.validator.addClassRules("cstValid", { alphanumeric: true });
        
        
        $('#btnSave').on('click', function () {
            document.forms['rptForm']['formsubmit'].value = 'formsubmit';
            document.getElementById('mode').value = '';
            $("#rptForm").submit();
        });
        
        $('#btnPublish').on('click', function () {
            var $valid = $("#rptForm").valid();
            if (!$valid){
                return false;
            }
            
            document.forms['rptForm']['formsubmit'].value = 'formsubmit';
            document.getElementById('mode').value = '<?php echo ORG_USERRPT_LIST_MODE_EDIT_PUBLISH; ?>';
            
            $.smallBox({
                title: "<?php echo getLocaleText('EDIT_RPT_MSG_8', TXT_U); ?>",
                content: "<br/><?php echo getLocaleText('EDIT_RPT_MSG_9', TXT_U); ?>",
                color: "#b25156",
                iconSmall: "fa fa-check bounce animated",
                timeout: 50000
            });
            
            $("#rptForm").submit();
        });
        
        $('#btnCancel').on('click', function () {
            location.href = 'dispReports.php';
        });
        
        $('#btnReset').on('click', function () {
            document.getElementById('rptForm').reset();
        });
        
        $('#dp-div').on('click', function () {
            $('#ui-datepicker-div').css({'z-index': 1000});
        });
        
    })
    
    function jtime(timeId){
        
        $('#'+timeId).timepicker({
            minuteStep: 1,
            showSeconds: true,
            secondStep: 1
            });
        
    }
    
    var totalRow2TB = 0;
    
    function addRow2TB(htmlname, ctr, dynaid){
        
        if (totalRow2TB == 0){
            totalRow2TB = ctr;
        }
        ++totalRow2TB;
       
        $("#2TBadd-"+dynaid).remove();
            
        var icons = '<div class="col-md-2" style="padding-left: 15px; padding-top: 7px;"><i class="fa fa-lg fa-minus-circle" style="color: #dc2d2d;cursor: pointer;font-size: 1.6em;" id="2TBminus-'+dynaid+'" onclick="javascript:minusRow2TB(\'' + htmlname + '\', ' + totalRow2TB + ', \'' + dynaid + '\');" title="Remove Task"></i>&nbsp;&nbsp;<i class="fa fa-lg fa-plus-circle" style="color: #739e73;cursor: pointer;font-size: 1.6em;" id="2TBadd-'+dynaid+'" onclick="javascript:addRow2TB(\'' + htmlname + '\', ' + totalRow2TB + ', \'' + dynaid + '\');" title="Add new Task" ></i></div>';

        var div = $('<div id="inner2TB-'+dynaid+'-'+totalRow2TB+'"></div>');

        div.html('<br/><br/>' + getDynamic2TB_1(htmlname) + getDynamic2TB_2(htmlname) + icons);            

        $("#2TB-"+dynaid).append(div);
        
    }
    
    function minusRow2TB(htmlname, ctr, dynaid){
        
        $("#inner2TB-"+dynaid+'-'+ctr).remove();
        
    }
    
    /*$("#2TB").on("click", '.fa-minus-circle', function () {
            
        $(this).parent().prev().parent().remove();

    });*/
    
    function getDynamic2TB_1(htmlname) {
        var divhtml = '<div class="col-md-5" style="padding-left: 0px;"><div class="input-group input-group-md" style="width:100%"><input class="form-control cstValid" type="text" placeholder="" name="' + htmlname + '_1-' + totalRow2TB + '" id="' + htmlname + '_1-' + totalRow2TB + '" value=""></div></div>';
        return divhtml;
    }
    
    function getDynamic2TB_2(htmlname) {
        var divhtml = '<div class="col-md-5" style="padding-right: 0px;"><div class="input-group input-group-md" style="width:100%"><input class="form-control cstValid" type="text" placeholder="" name="' + htmlname + '_2-' + totalRow2TB + '" id="' + htmlname + '_2-' + totalRow2TB + '" value=""></div></div>';
        return divhtml;
    }

</script>


<?php

    function preInputsInspection(UserOrgInputs $userOrgInputs, UserOrgDao $userOrgDao){
        
        $postedIdValues = '';
        
        $userEntities = $userOrgDao->getUserEntities();
        foreach ($userEntities as $userEntity){
            $htmlname = $userEntity->getHtmlName();
            if ($userEntity->getHtmlType() == DYNA_CONTROL_TYPE_DYNAMIC_TEXTBOXES_2){
                $consPostedValues = parseDynamic2TBValues($htmlname);
                if (isset($consPostedValues) && !empty($consPostedValues)){
                    $postedIdValues = $postedIdValues . $userEntity->getIdDynaFields() . FIELDID_VALUE_SEPERATOR . $consPostedValues . FIELDID_VALUE_DATASET_SEPERATOR;
                }
            }
            else{
                if (isset($_POST[$htmlname]) && !empty($_POST[$htmlname])){
                    $value = $_POST[$htmlname];
                    if ($userEntity->getHtmlType() == DYNA_CONTROL_TYPE_TIME){
                        $value = str_replace(':', TIME_CONTROL_SEPERATOR, $value);
                    }
                    $postedIdValues = $postedIdValues . $userEntity->getIdDynaFields() . FIELDID_VALUE_SEPERATOR . $value . FIELDID_VALUE_DATASET_SEPERATOR;
                    
                    //Save client and worker name seperately
                    if ($htmlname == DYNAFIELDS_FIELDID_HTMLNAME_CUSTOMER_NAME){
                        if (!empty($value)){
                            $userOrgInputs->setClient($value);
                        }
                    }
                    else if ($htmlname == DYNAFIELDS_FIELDID_HTMLNAME_WORKER_NAME){
                        if (!empty($value)){
                            $userOrgInputs->setWorker($value);
                        }
                    }
                }
            }
        }
        
        if (!empty($postedIdValues)){
            $postedIdValues = substr($postedIdValues, 0, strlen($postedIdValues)-1);
        }
                
        $userOrgInputs->setPostedIdValues($postedIdValues);
        
        if (isset($_POST['rptid'])){
            $userOrgInputs->setIdrpts($_POST['rptid']);
        }
        
    }
    
    function parseDynamic2TBValues($htmlname){
        
        $parsedHtmlNames = array();
        $consPostedValues = '';
        
        foreach ($_POST as $key => $value){
            if (strpos($key, $htmlname) !== FALSE){
                
                if (!in_array($key, $parsedHtmlNames)){
                    
                    $htmlvalue = '';
                    $tbSeq = substr($key, strlen($htmlname)+1);
                    $tbSeq = explode('-', $tbSeq);
                    $otherTbKey = '';
                    
                    if ($tbSeq[0] == 1){
                        $otherTbKey = $htmlname.'_2-'.$tbSeq[1];
                        $otherTbValue = '';
                        if (isset($_POST[$otherTbKey])){
                            $otherTbValue = $_POST[$otherTbKey];
                        }
                        $htmlvalue = $value . FIELD_2TB_VALUES_SEPERATOR . $otherTbValue;
                    }
                    else if ($tbSeq[0] == 2){
                        $otherTbKey = $htmlname.'_1-'.$tbSeq[1];
                        $otherTbValue = '';
                        if (isset($_POST[$otherTbKey])){
                            $otherTbValue = $_POST[$otherTbKey];
                        }
                        $htmlvalue = $otherTbValue . FIELD_2TB_VALUES_SEPERATOR . $value;
                    }
                    
                    array_push($parsedHtmlNames, $key);
                    array_push($parsedHtmlNames, $otherTbKey);
                    
                    if (!empty($htmlvalue) && $htmlvalue !== FIELD_2TB_VALUES_SEPERATOR){
                        $consPostedValues = $consPostedValues . $htmlvalue . FIELD_2TB_VALUES_DATASET_SEPERATOR;
                    }
                }
                
            }
        }
        
        if (!empty($consPostedValues)){
            $consPostedValues = substr($consPostedValues, 0, strlen($consPostedValues)-1);
        }
        
        return $consPostedValues;
        
    }
    
?>

