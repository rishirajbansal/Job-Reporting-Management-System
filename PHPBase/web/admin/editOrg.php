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

include_once(dirname(__FILE__) . "/../../classes/inputs/OrgInputs.php");
include_once(dirname(__FILE__) . "/../../classes/dao/OrgDao.php");
include_once(dirname(__FILE__) . "/../../classes/base/Constants.php");
include_once(dirname(__FILE__) . "/../../classes/vo/Organization.php");

include_once("sessionMgmt.php");

require_once(dirname(__FILE__) . "/../commonInc/init.php");
require_once(dirname(__FILE__) . "/inc/config.ui.php");


/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('EDIT_ORG_MSG_1', TXT_A);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["organizations"]["active"] = true;
include("inc/nav.php");

/*------------- Form Submissions ---------*/

$orgInputs = new OrgInputs();
$orgDao = new OrgDao();
$productDynaFields = array();
$tasksDynaFields = array();
$workerDynaFields = array();
$customerDynaFields = array();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$prd_consCBIds = '';
$prd_consCBIds_ckd = '';
$tsks_consCBIds = '';
$tsks_consCBIds_ckd = '';
$wrks_consCBIds = '';
$wrks_consCBIds_ckd = '';
$cstmrs_consCBIds = '';
$cstmrs_consCBIds_ckd = '';

$prd_consBtIds = '';
$prd_consBtIds_ckd = '';
$tsks_consBtIds = '';
$tsks_consBtIds_ckd = '';
$wrks_consBtIds = '';
$wrks_consBtIds_ckd = '';
$cstmrs_consBtIds = '';
$cstmrs_consBtIds_ckd = '';

$xEditControls = array();

$isDetailsFetched = FALSE;
$orgDetails = new Organization();


//Load prerequisties for Orgnaization updation
$loadFlag = $orgDao->loadDynaFields();
if ($loadFlag){
    $productDynaFields = $orgDao->getProductDynaFields();
    $tasksDynaFields = $orgDao->getTaskDynaFields();
    $workerDynaFields = $orgDao->getWorkerDynaFields();
    $customerDynaFields = $orgDao->getCustomerDynaFields();
}
else{
    if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
        $errMsgObject['msg'] = 'error';
        $errMsgObject['text'] = $orgDao->getErrors();
    }
    else if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
        $errMsgObject['msg'] = 'criticalError';
        $errMsgObject['text'] = $orgDao->getCriticalError();
    }
}


if (isset($_POST['formsubmit']) && $_POST['formsubmit'] != 'listorgsubmit' && $_POST['formsubmit'] != 'vieworgsubmit'){
    
    $isFormCancel = $_POST['formcancel'];
    
    if (isset($isFormCancel) && ($isFormCancel === 'true')){
        $orgDao->cancelOrg();
        //header("location: org.php");
        echo("<script>location.href = 'org.php';</script>");
        exit();
    }
    
    $updateFlag = '';
    if ($_POST['formsubmit'] == ORG_UPDATE_PART_ORG_DETAILS){
        preInputsInspection($orgInputs, $orgDao, ORG_UPDATE_PART_ORG_DETAILS);
        $updateFlag = $orgDao->updateOrg($orgInputs, ORG_UPDATE_PART_ORG_DETAILS);
    }
    else if ($_POST['formsubmit'] == ORG_UPDATE_PART_ORG_COBRADING){
        preInputsInspection($orgInputs, $orgDao, ORG_UPDATE_PART_ORG_COBRADING);
        $updateFlag = $orgDao->updateOrg($orgInputs, ORG_UPDATE_PART_ORG_COBRADING);
    }
    else if ($_POST['formsubmit'] == ORG_UPDATE_PART_ORG_PRODUCT_DETAILS){
        preInputsInspection($orgInputs, $orgDao, ORG_UPDATE_PART_ORG_PRODUCT_DETAILS);
        $updateFlag = $orgDao->updateOrg($orgInputs, ORG_UPDATE_PART_ORG_PRODUCT_DETAILS);
    }
    else if ($_POST['formsubmit'] == ORG_UPDATE_PART_ORG_TASK_DETAILS){
        preInputsInspection($orgInputs, $orgDao, ORG_UPDATE_PART_ORG_TASK_DETAILS);
        $updateFlag = $orgDao->updateOrg($orgInputs, ORG_UPDATE_PART_ORG_TASK_DETAILS);
    }
    else if ($_POST['formsubmit'] == ORG_UPDATE_PART_ORG_WORKER_DETAILS){
        preInputsInspection($orgInputs, $orgDao, ORG_UPDATE_PART_ORG_WORKER_DETAILS);
        $updateFlag = $orgDao->updateOrg($orgInputs, ORG_UPDATE_PART_ORG_WORKER_DETAILS);
    }
    else if ($_POST['formsubmit'] == ORG_UPDATE_PART_ORG_CUSTOMER_DETAILS){
        preInputsInspection($orgInputs, $orgDao, ORG_UPDATE_PART_ORG_CUSTOMER_DETAILS);
        $updateFlag = $orgDao->updateOrg($orgInputs, ORG_UPDATE_PART_ORG_CUSTOMER_DETAILS);
    }
    
    if ($updateFlag){
        if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_SUCCESS){
            $errMsgObject['msg'] = 'success';
            $errMsgObject['text'] = $orgDao->getSuccessMessage();
        }
        else if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_COMPLETESUCCESS){
            $errMsgObject['msg'] = 'completeSuccess';
            $errMsgObject['text'] = $orgDao->getCompleteMsg();
        }
    }
    else{
        if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
            $errMsgObject['msg'] = 'message';
            $errMsgObject['text'] = $orgDao->getMessages();
        }
        else if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
            $errMsgObject['msg'] = 'error';
            $errMsgObject['text'] = $orgDao->getErrors();
        }
        else if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
            $errMsgObject['msg'] = 'criticalError';
            $errMsgObject['text'] = $orgDao->getCriticalError();
        }
    }
    
    $fetchFlag = $orgDao->fetchOrg($orgInputs);
            
    if ($fetchFlag){
        $orgDetails = $orgDao->getOrgDetails();
        $orgDetails->setIdorgs($orgInputs->getIdorgs());
        $isDetailsFetched = TRUE;
    }
        
}
else if (!empty($loadFlag) && isset($_POST['formsubmit']) && ($_POST['formsubmit'] == 'listorgsubmit' || $_POST['formsubmit'] == 'vieworgsubmit') && isset($_POST['mode'])){
    
    if ($_POST['mode'] == ORG_LIST_MODE_EDIT){
        $orgId = $_POST['orgid'];
        $orgInputs->setIdorgs($orgId);
        
        $fetchFlag = $orgDao->fetchOrg($orgInputs);
            
        if ($fetchFlag){
            $orgDetails = $orgDao->getOrgDetails();
            $orgDetails->setIdorgs($orgId);
            $isDetailsFetched = TRUE;
        }
        else{
            if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
                $errMsgObject['msg'] = 'message';
                $errMsgObject['text'] = $orgDao->getMessages();
            }
            else if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
                $errMsgObject['msg'] = 'error';
                $errMsgObject['text'] = $orgDao->getErrors();
            }
            else if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
                $errMsgObject['msg'] = 'criticalError';
                $errMsgObject['text'] = $orgDao->getCriticalError();
            }
        }
    }
    
}

/*------------- End Form Submissions ---------*/


?>

<!-- MAIN PANEL -->
<div id="main" role="main">
    
    <?php
        $breadcrumbs[getLocaleText('EDIT_ORG_MSG_2', TXT_A)] = "org.php";
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-building"></i> <?php echo getLocaleText('EDIT_ORG_MSG_3', TXT_A); ?></h1>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <a href="org.php" class="btn bg-color-blueLight txt-color-white btn-lg pull-right header-btn hidden-mobile"><i class="fa fa-circle-arrow-up fa-lg"></i> <?php echo getLocaleText('EDIT_ORG_MSG_4', TXT_A); ?></a>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <?php
        
        if ($isDetailsFetched){ ?>
        
        <section id="widget-grid" class="">

            <!-- row -->
            <div class="row">

                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <!-- Widget ID (each widget will need unique ID)-->
                    <div class="jarviswidget" id="wid-id-0" 
                         data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">

                        <header>
                            <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                            <h2><?php echo getLocaleText('EDIT_ORG_MSG_5', TXT_A); ?> - <strong class="text-primary"><i><?php echo $orgDetails->getName(); ?></i></strong> </h2>
                        </header>

                        <!-- widget div-->
                        <div>

                            <!-- widget edit box -->
                            <div class="jarviswidget-editbox">

                            </div>
                            <!-- end widget edit box -->

                            <!-- widget content -->
                            <div class="widget-body" style="min-height: 450px;">

                                <div class="tabs-left">

                                    <ul class="nav nav-tabs tabs-left" id="" style="min-height: 400px;background-color: #f5f5dc">
                                        <li class="active">
                                            <a href="#tab-r1" data-toggle="tab"><?php echo getLocaleText('EDIT_ORG_MSG_6', TXT_A); ?></a>
                                        </li>
                                        <li>
                                            <a href="#tab-r2" data-toggle="tab"><?php echo getLocaleText('EDIT_ORG_MSG_7', TXT_A); ?></a>
                                        </li>
                                        <li>
                                            <a href="#tab-r3" data-toggle="tab"><?php echo getLocaleText('EDIT_ORG_MSG_8', TXT_A); ?></a>
                                        </li>
                                        <li>
                                            <a href="#tab-r4" data-toggle="tab"><?php echo getLocaleText('EDIT_ORG_MSG_9', TXT_A); ?></a>
                                        </li>
                                        <li>
                                            <a href="#tab-r5" data-toggle="tab"><?php echo getLocaleText('EDIT_ORG_MSG_10', TXT_A); ?></a>
                                        </li>
                                        <li>
                                            <a href="#tab-r6" data-toggle="tab"><?php echo getLocaleText('EDIT_ORG_MSG_11', TXT_A); ?></a>
                                        </li>

                                    </ul>

                                    <div class="tab-content" style="margin-left: 210px;">

                                        <div class="tab-pane active" id="tab-r1">

                                            <form class="form-horizontal" id="orgDetailsForm" name="orgDetailsForm" method="post" action="">
                                                <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                                <input type="hidden" name="formcancel" id="formcancel" value=""/>
                                                <input type="hidden" name="orgid" id="orgid" value="<?php echo $orgDetails->getIdorgs(); ?>"/>

                                                <h3><?php echo getLocaleText('EDIT_ORG_MSG_12', TXT_A); ?></h3>
                                                <p><?php echo getLocaleText('EDIT_ORG_MSG_13', TXT_A); ?></p>

                                                <fieldset style="min-height: 260px">

                                                    <legend></legend>
                                                    <br/>

                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label"><?php echo getLocaleText('EDIT_ORG_MSG_14', TXT_A); ?> </label>
                                                        <div class="col-md-5">
                                                            <div class="input-group input-group-md">
                                                                <span class="input-group-addon "><i class="fa fa-building fa-fw"></i></span>
                                                                <input class="form-control" placeholder="<?php echo getLocaleText('EDIT_ORG_MSG_14', TXT_A); ?>" type="text" name="orgname" id="orgname" value="<?php echo $orgDetails->getName(); ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label"><?php echo getLocaleText('EDIT_ORG_MSG_15', TXT_A); ?> </label>
                                                        <div class="col-md-3">
                                                            <div class="input-group input-group-md">
                                                                <span class="input-group-addon "><i class="fa fa-phone fa-fw"></i></span>
                                                                <input class="form-control" data-mask="+99 (999) 999-9999" data-mask-placeholder= "X" placeholder="+34" type="text" name="phone" id="phone" value="<?php echo $orgDetails->getPhone(); ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label"><?php echo getLocaleText('EDIT_ORG_MSG_16', TXT_A); ?> </label>
                                                        <div class="col-md-5">
                                                            <div class="input-group input-group-md">
                                                                <span class="input-group-addon "><i class="fa fa-envelope fa-fw"></i></span>
                                                                <input class="form-control" placeholder="email@address.com" type="text" name="email" id="email" value="<?php echo $orgDetails->getEmail(); ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label"><?php echo getLocaleText('EDIT_ORG_MSG_17', TXT_A); ?> </label>
                                                        <div class="col-md-3">
                                                            <div class="input-group input-group-md">
                                                                <span class="input-group-addon "><i class="fa fa-user fa-fw"></i></span>
                                                                <input class="form-control" placeholder="<?php echo getLocaleText('EDIT_ORG_MSG_17', TXT_A); ?> " type="text" name="uname" id="uname" value="<?php echo $orgDetails->getUsername(); ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label"><?php echo getLocaleText('EDIT_ORG_MSG_18', TXT_A); ?> </label>
                                                        <div class="col-md-3">
                                                            <div class="input-group input-group-md">
                                                                <span class="input-group-addon "><i class="fa fa-lock fa-fw"></i></span>
                                                                <input class="form-control" placeholder="<?php echo getLocaleText('EDIT_ORG_MSG_18', TXT_A); ?>" type="password" name="pwd" id="pwd" value="<?php echo $orgDetails->getPassword(); ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </fieldset>

                                                <div class="form-actions" style="border: none;background: none;border-top: 1px solid rgba(0,0,0,.1);">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <button type="button" class="btn btn-default bg-color-blueLight txt-color-white" id="btnCancel-org">
                                                                <i class="glyphicon glyphicon-remove"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_1', TXT_A); ?>
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-default" id="btnReset-org">
                                                                <i class="fa fa-refresh"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_2', TXT_A); ?>
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-primary" id="btnUpdate-org">
                                                                <i class="fa fa-save"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_3', TXT_A); ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>

                                        <div class="tab-pane" id="tab-r2">

                                            <form class="form-horizontal" id="coBrandForm" name="coBrandForm" method="post" action="">
                                                <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                                <input type="hidden" name="formcancel" id="formcancel" value=""/>
                                                <input type="hidden" name="orgid" id="orgid" value="<?php echo $orgDetails->getIdorgs(); ?>"/>

                                                <h3><?php echo getLocaleText('EDIT_ORG_MSG_19', TXT_A); ?></h3>
                                                <p><?php echo getLocaleText('EDIT_ORG_MSG_20', TXT_A); ?></p>

                                                <fieldset style="min-height: 260px">

                                                    <legend></legend>
                                                    <br/>

                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label"><strong><?php echo getLocaleText('EDIT_ORG_MSG_21', TXT_A); ?> </strong></label>
                                                        <div class="col-md-5">
                                                            <?php
                                                            if (file_exists($orgDetails->getLogoPath())){ ?>
                                                                <div class="input-group input-group-md" id="imagepreview">
                                                                    <img src="<?php echo $orgDetails->getLogoPath(); ?>" style="max-width: 300px;">
                                                                </div>
                                                            <?php }
                                                            else{ ?>
                                                                <div class="input-group input-group-md" id="imagepreview" style="margin-top: 6px;">
                                                                    <label class="col-md-12"><span class="label label-warning" style="font-size: 12px"><?php echo $orgDetails->getLogoPath(); ?></span></label>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <br/><br/>

                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label"><strong><?php echo getLocaleText('EDIT_ORG_MSG_22', TXT_A); ?> </strong></label>
                                                        <div class="col-md-5">
                                                            <div class="input-group input-group-md">
                                                                <div class="dropzone" id="logoupload" action="cobrand.php">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </fieldset>

                                                <div class="form-actions" style="border: none;background: none;border-top: 1px solid rgba(0,0,0,.1);">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <button type="button" class="btn btn-default bg-color-blueLight txt-color-white" id="btnCancel-cobrnd">
                                                                <i class="glyphicon glyphicon-remove"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_1', TXT_A); ?>
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-primary" id="btnUpdate-cobrnd">
                                                                <i class="fa fa-save"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_3', TXT_A); ?>
                                                            </button>
                                                            <br/><br/>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>

                                        <div class="tab-pane" id="tab-r3">

                                            <form class="form-horizontal" id="dynaPrdsForm" name="dynaPrdsForm" method="post" action="">
                                                <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                                <input type="hidden" name="formcancel" id="formcancel" value=""/>
                                                <input type="hidden" name="orgid" id="orgid" value="<?php echo $orgDetails->getIdorgs(); ?>"/>

                                                <h3><?php echo getLocaleText('EDIT_ORG_MSG_23', TXT_A); ?></h3>
                                                <p><?php echo getLocaleText('EDIT_ORG_MSG_24', TXT_A); ?></p>

                                                <fieldset style="min-height: 260px">

                                                    <legend></legend>
                                                    <br/>

                                                    <div class="alert alert-block alert-form-wizard-error" id="divDynaPrd" style="display: none">
                                                        <?php echo getLocaleText('EDIT_ORG_MSG_25', TXT_A); ?>
                                                    </div>

                                                    <?php
                                                        $orgDynaProduct = $orgDao->getOrgProductDynaFields();
                                                        $dynaFieldsProcessedDetails = $orgDynaProduct->getDynaFieldsProcessedDetails();
                                                        $tmpFlag = TRUE;
                                                        foreach($productDynaFields as $prdDynaField){
                                                            $cbName = $prdDynaField->getIdDynaFields();
                                                            $btName = 'bt_'.$prdDynaField->getIdDynaFields();
                                                            
                                                            $childList = $prdDynaField->getHtmlListValues();
                                                            
                                                            $checked = '';
                                                            $btColor = '';
                                                            $xEditSavedValues = '';
                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                if ($value['fieldId'] == $cbName){
                                                                    $checked = 'checked';
                                                                    $btColor = 'background-color:#e6e6e6;';
                                                                    $xEditSavedValues = $value['fieldValues'];
                                                                }
                                                            }
                                                            if (empty($checked)){
                                                                $prd_consCBIds = $prd_consCBIds . 'input[id=\'' . $cbName . '\'], ';
                                                                $prd_consBtIds = $prd_consBtIds . 'a[id=\'' . $btName . '\'], ';
                                                            }
                                                            else{
                                                                $prd_consCBIds_ckd = $prd_consCBIds_ckd . 'input[id=\'' . $cbName . '\'], ';
                                                                $prd_consBtIds_ckd = $prd_consBtIds_ckd . 'a[id=\'' . $btName . '\'], ';
                                                            }
                                                        ?>
                                                            <?php if ($tmpFlag){ ?>
                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                <label class="col-md-3 control-label"><?php echo getLocaleText('EDIT_ORG_MSG_26', TXT_A); ?> </label>
                                                            <?php $tmpFlag = FALSE; } else { ?> 
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label"></label>
                                                            <?php } ?>
                                                                <div class="col-md-6">
                                                                    <label class="col-md-12">
                                                                        <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $prdDynaField->getIdDynaFields(); ?>" onclick="javascript:toggleDynaFieldsCheckbox('<?php echo $cbName; ?>','<?php echo $btName; ?>');" <?php echo $checked; ?> >
                                                                        <span class="col-md-12">
                                                                            <a href="javascript:toggleDynaFieldsButton('<?php echo $cbName; ?>','<?php echo $btName; ?>');" id="<?php echo $btName; ?>" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="<?php echo $prdDynaField->getHtmlType(); ?>" 
                                                                               data-content="<?php echo $prdDynaField->getDescription(); ?>" style="margin-left: 15px;width: 300px;<?php echo $btColor; ?>"><?php echo $prdDynaField->getName(); ?><strong></strong></a>                                                                    
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <?php if (!empty($childList) && sizeof($childList) > 0){
                                                                    $xeditId = 'x-'.$cbName;
                                                                    //$listArr = explode('|', $childList);
                                                                    $listArr = $childList;
                                                                    $xeditSrc = '';
                                                                    foreach ($listArr as $key => $value) {
                                                                        $xeditSrc = $xeditSrc . '{value: \'' . $key . '\', text: \'' . $value . '\'}, ';
                                                                    }
                                                                    $xeditSrc = substr($xeditSrc, 0, strlen($xeditSrc)-2);
                                                                    $xEditControl = array(
                                                                                        'xeditId' => $xeditId,
                                                                                        'xsource' => $xeditSrc,
                                                                                        'xSelectedValues' => $xEditSavedValues
                                                                                    );
                                                                    array_push($xEditControls, $xEditControl);
                                                                    ?>
                                                                    <div class="form-group">
                                                                        <label class="col-md-3 control-label"></label>
                                                                        <div class="col-md-8" style="margin-left: 60px;margin-top: -12px;margin-bottom: 5px;">
                                                                            <label>&nbsp;&nbsp;<?php echo getLocaleText('EDIT_ORG_MSG_46', TXT_A); ?> : </label>
                                                                            <a href="form-x-editable.html#" id="<?php echo $xeditId; ?>" data-original-title="<?php echo getLocaleText('EDIT_ORG_MSG_47', TXT_A); ?>" data-placement="right" ></a>
                                                                        </div>
                                                                    </div>
                                                            <?php } ?>
                                                    <?php } 
                                                    $prd_consCBIds = substr($prd_consCBIds, 0, strlen($prd_consCBIds)-2);
                                                    $prd_consBtIds = substr($prd_consBtIds, 0, strlen($prd_consBtIds)-2);
                                                    $prd_consCBIds_ckd = substr($prd_consCBIds_ckd, 0, strlen($prd_consCBIds_ckd)-2);
                                                    $prd_consBtIds_ckd = substr($prd_consBtIds_ckd, 0, strlen($prd_consBtIds_ckd)-2);
                                                    ?>

                                                </fieldset>

                                                <div class="form-actions" style="border: none;background: none;border-top: 1px solid rgba(0,0,0,.1);">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <button type="button" class="btn btn-default bg-color-blueLight txt-color-white" id="btnCancel-prd">
                                                                <i class="glyphicon glyphicon-remove"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_1', TXT_A); ?>
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-default" id="btnReset-prd">
                                                                <i class="fa fa-refresh"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_2', TXT_A); ?>
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-primary" id="btnUpdate-prd">
                                                                <i class="fa fa-save"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_3', TXT_A); ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>

                                        <div class="tab-pane" id="tab-r4">

                                            <form class="form-horizontal" id="dynaTsksForm" name="dynaTsksForm" method="post" action="">
                                                <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                                <input type="hidden" name="formcancel" id="formcancel" value=""/>
                                                <input type="hidden" name="orgid" id="orgid" value="<?php echo $orgDetails->getIdorgs(); ?>"/>

                                                <h3><?php echo getLocaleText('EDIT_ORG_MSG_27', TXT_A); ?></h3>
                                                <p><?php echo getLocaleText('EDIT_ORG_MSG_28', TXT_A); ?></p>

                                                <fieldset style="min-height: 260px">

                                                    <legend></legend>
                                                    <br/>

                                                    <div class="alert alert-block alert-form-wizard-error" id="divDynaTsks" style="display: none">
                                                        <?php echo getLocaleText('EDIT_ORG_MSG_29', TXT_A); ?>
                                                    </div>

                                                    <?php
                                                        $orgDynaTask = $orgDao->getOrgTaskDynaFields();
                                                        $dynaFieldsProcessedDetails = $orgDynaTask->getDynaFieldsProcessedDetails();
                                                        $tmpFlag = TRUE;
                                                        foreach($tasksDynaFields as $tsksDynaField){ 
                                                            $cbName = $tsksDynaField->getIdDynaFields();
                                                            $btName = 'bt_'.$tsksDynaField->getIdDynaFields();

                                                            $childList = $tsksDynaField->getHtmlListValues();
                                                            
                                                            $checked = '';
                                                            $btColor = '';
                                                            $xEditSavedValues = '';
                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                if ($value['fieldId'] == $cbName){
                                                                    $checked = 'checked';
                                                                    $btColor = 'background-color:#e6e6e6;';
                                                                    $xEditSavedValues = $value['fieldValues'];
                                                                    //$xEditSavedValues = str_replace(',', ',&nbsp;&nbsp;&nbsp;&nbsp;', $xEditSavedValues);
                                                                }
                                                            }
                                                            if (empty($checked)){
                                                                $tsks_consCBIds = $tsks_consCBIds . 'input[id=\'' . $cbName . '\'], ';
                                                                $tsks_consBtIds = $tsks_consBtIds . 'a[id=\'' . $btName . '\'], ';
                                                            }
                                                            else{
                                                                $tsks_consCBIds_ckd = $tsks_consCBIds_ckd . 'input[id=\'' . $cbName . '\'], ';
                                                                $tsks_consBtIds_ckd = $tsks_consBtIds_ckd . 'a[id=\'' . $btName . '\'], ';
                                                            }
                                                        ?>
                                                            <?php if ($tmpFlag){ ?>
                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                <label class="col-md-3 control-label"><?php echo getLocaleText('EDIT_ORG_MSG_30', TXT_A); ?></label>
                                                            <?php $tmpFlag = FALSE; } else { ?> 
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label"></label>
                                                            <?php } ?>
                                                                <div class="col-md-6">
                                                                    <label class="col-md-12">
                                                                        <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $tsksDynaField->getIdDynaFields(); ?>" onclick="javascript:toggleDynaFieldsCheckbox('<?php echo $cbName; ?>','<?php echo $btName; ?>');" <?php echo $checked; ?> >
                                                                        <span class="col-md-12">
                                                                            <a href="javascript:toggleDynaFieldsButton('<?php echo $cbName; ?>','<?php echo $btName; ?>');" id="<?php echo $btName; ?>" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="<?php echo $tsksDynaField->getHtmlType(); ?>" 
                                                                               data-content="<?php echo $tsksDynaField->getDescription(); ?>" style="margin-left: 15px;width: 300px;<?php echo $btColor; ?>"><?php echo $tsksDynaField->getName(); ?><strong></strong></a>                                                                    
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <?php if (!empty($childList) && sizeof($childList) > 0){
                                                                    $xeditId = 'x-'.$cbName;
                                                                    //$listArr = explode('|', $childList);
                                                                    $listArr = $childList;
                                                                    $xeditSrc = '';
                                                                    foreach ($listArr as $key => $value) {
                                                                        $xeditSrc = $xeditSrc . '{value: \'' . $key . '\', text: \'' . $value . '\'}, ';
                                                                    }
                                                                    $xeditSrc = substr($xeditSrc, 0, strlen($xeditSrc)-2);
                                                                    $xEditControl = array(
                                                                                        'xeditId' => $xeditId,
                                                                                        'xsource' => $xeditSrc,
                                                                                        'xSelectedValues' => $xEditSavedValues
                                                                                    );
                                                                    array_push($xEditControls, $xEditControl);
                                                                    ?>
                                                                    <div class="form-group">
                                                                        <label class="col-md-3 control-label"></label>
                                                                        <div class="col-md-8" style="margin-left: 60px;margin-top: -12px;margin-bottom: 5px;">
                                                                            <label>&nbsp;&nbsp;<?php echo getLocaleText('EDIT_ORG_MSG_46', TXT_A); ?> : </label>
                                                                            <a href="form-x-editable.html#" id="<?php echo $xeditId; ?>" data-original-title="<?php echo getLocaleText('EDIT_ORG_MSG_47', TXT_A); ?>" data-placement="right" ></a>
                                                                        </div>
                                                                    </div>
                                                            <?php } ?>
                                                    <?php } 
                                                    $tsks_consCBIds = substr($tsks_consCBIds, 0, strlen($tsks_consCBIds)-2);
                                                    $tsks_consBtIds = substr($tsks_consBtIds, 0, strlen($tsks_consBtIds)-2);
                                                    $tsks_consCBIds_ckd = substr($tsks_consCBIds_ckd, 0, strlen($tsks_consCBIds_ckd)-2);
                                                    $tsks_consBtIds_ckd = substr($tsks_consBtIds_ckd, 0, strlen($tsks_consBtIds_ckd)-2);
                                                    ?>

                                                </fieldset>

                                                <div class="form-actions" style="border: none;background: none;border-top: 1px solid rgba(0,0,0,.1);">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <button type="button" class="btn btn-default bg-color-blueLight txt-color-white" id="btnCancel-tsk">
                                                                <i class="glyphicon glyphicon-remove"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_1', TXT_A); ?>
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-default" id="btnReset-tsk">
                                                                <i class="fa fa-refresh"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_2', TXT_A); ?>
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-primary" id="btnUpdate-tsk">
                                                                <i class="fa fa-save"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_3', TXT_A); ?>
                                                            </button>
                                                            <br/><br/>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>

                                        <div class="tab-pane" id="tab-r5">

                                            <form class="form-horizontal" id="dynaWrksForm" name="dynaWrksForm" method="post" action="">
                                                <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                                <input type="hidden" name="formcancel" id="formcancel" value=""/>
                                                <input type="hidden" name="orgid" id="orgid" value="<?php echo $orgDetails->getIdorgs(); ?>"/>

                                                <h3><?php echo getLocaleText('EDIT_ORG_MSG_31', TXT_A); ?></h3>
                                                <p><?php echo getLocaleText('EDIT_ORG_MSG_32', TXT_A); ?></p>

                                                <fieldset style="min-height: 260px">

                                                    <legend></legend>
                                                    <br/>

                                                    <div class="alert alert-block alert-form-wizard-error" id="divDynaWrks" style="display: none">
                                                        <?php echo getLocaleText('EDIT_ORG_MSG_33', TXT_A); ?>
                                                    </div>

                                                    <?php
                                                        $orgDynaWorker = $orgDao->getOrgWorkerDynaField();
                                                        $dynaFieldsProcessedDetails = $orgDynaWorker->getDynaFieldsProcessedDetails();
                                                        $tmpFlag = TRUE;
                                                        foreach($workerDynaFields as $wrksDynaField){ 
                                                            $cbName = $wrksDynaField->getIdDynaFields();
                                                            $btName = 'bt_'.$wrksDynaField->getIdDynaFields();

                                                            $childList = $wrksDynaField->getHtmlListValues();
                                                            
                                                            $checked = '';
                                                            $btColor = '';
                                                            $xEditSavedValues = '';
                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                if ($value['fieldId'] == $cbName){
                                                                    $checked = 'checked';
                                                                    $btColor = 'background-color:#e6e6e6;';
                                                                    $xEditSavedValues = $value['fieldValues'];
                                                                    //$xEditSavedValues = str_replace(',', ',&nbsp;&nbsp;&nbsp;&nbsp;', $xEditSavedValues);
                                                                }
                                                            }
                                                            if (empty($checked)){
                                                                $wrks_consCBIds = $wrks_consCBIds . 'input[id=\'' . $cbName . '\'], ';
                                                                $wrks_consBtIds = $wrks_consBtIds . 'a[id=\'' . $btName . '\'], ';
                                                            }
                                                            else{
                                                                $wrks_consCBIds_ckd = $wrks_consCBIds_ckd . 'input[id=\'' . $cbName . '\'], ';
                                                                $wrks_consBtIds_ckd = $wrks_consBtIds_ckd . 'a[id=\'' . $btName . '\'], ';
                                                            }

                                                            
                                                        ?>
                                                            <?php if ($tmpFlag){ ?>
                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                <label class="col-md-3 control-label"><?php echo getLocaleText('EDIT_ORG_MSG_44', TXT_A); ?> </label>
                                                            <?php $tmpFlag = FALSE; } else { ?> 
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label"></label>
                                                            <?php } ?>
                                                                <div class="col-md-6">
                                                                    <label class="col-md-12">
                                                                        <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $wrksDynaField->getIdDynaFields(); ?>" onclick="javascript:toggleDynaFieldsCheckbox('<?php echo $cbName; ?>','<?php echo $btName; ?>');" <?php echo $checked; ?> >
                                                                        <span class="col-md-12">
                                                                            <a href="javascript:toggleDynaFieldsButton('<?php echo $cbName; ?>','<?php echo $btName; ?>');" id="<?php echo $btName; ?>" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="<?php echo $wrksDynaField->getHtmlType(); ?>" 
                                                                               data-content="<?php echo $wrksDynaField->getDescription(); ?>" style="margin-left: 15px;width: 300px;<?php echo $btColor; ?>"><?php echo $wrksDynaField->getName(); ?><strong></strong></a>                                                                    
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                           <?php if (!empty($childList) && sizeof($childList) > 0){
                                                                    $xeditId = 'x-'.$cbName;
                                                                    //$listArr = explode('|', $childList);
                                                                    $listArr = $childList;
                                                                    $xeditSrc = '';
                                                                    foreach ($listArr as $key => $value) {
                                                                        $xeditSrc = $xeditSrc . '{value: \'' . $key . '\', text: \'' . $value . '\'}, ';
                                                                    }
                                                                    $xeditSrc = substr($xeditSrc, 0, strlen($xeditSrc)-2);
                                                                    $xEditControl = array(
                                                                                        'xeditId' => $xeditId,
                                                                                        'xsource' => $xeditSrc,
                                                                                        'xSelectedValues' => $xEditSavedValues
                                                                                    );
                                                                    array_push($xEditControls, $xEditControl);
                                                                    ?>
                                                                    <div class="form-group">
                                                                        <label class="col-md-3 control-label"></label>
                                                                        <div class="col-md-8" style="margin-left: 55px;margin-top: -12px;margin-bottom: 5px;">
                                                                            <label>&nbsp;&nbsp;<?php echo getLocaleText('EDIT_ORG_MSG_46', TXT_A); ?> : </label>
                                                                            <a href="form-x-editable.html#" id="<?php echo $xeditId; ?>" data-original-title="<?php echo getLocaleText('EDIT_ORG_MSG_47', TXT_A); ?>" data-placement="right" ></a>
                                                                        </div>
                                                                    </div>
                                                            <?php } ?>
                                                    <?php }
                                                    $wrks_consCBIds = substr($wrks_consCBIds, 0, strlen($wrks_consCBIds)-2);
                                                    $wrks_consBtIds = substr($wrks_consBtIds, 0, strlen($wrks_consBtIds)-2);
                                                    $wrks_consCBIds_ckd = substr($wrks_consCBIds_ckd, 0, strlen($wrks_consCBIds_ckd)-2);
                                                    $wrks_consBtIds_ckd = substr($wrks_consBtIds_ckd, 0, strlen($wrks_consBtIds_ckd)-2);
                                                    ?>

                                                </fieldset>

                                                <div class="form-actions" style="border: none;background: none;border-top: 1px solid rgba(0,0,0,.1);">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <button type="button" class="btn btn-default bg-color-blueLight txt-color-white" id="btnCancel-wrk">
                                                                <i class="glyphicon glyphicon-remove"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_1', TXT_A); ?>
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-default" id="btnReset-wrk">
                                                                <i class="fa fa-refresh"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_2', TXT_A); ?>
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-primary" id="btnUpdate-wrk">
                                                                <i class="fa fa-save"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_3', TXT_A); ?>
                                                            </button>
                                                            <br/><br/>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>

                                        <div class="tab-pane" id="tab-r6">

                                            <form class="form-horizontal" id="dynaCstmrsForm" name="dynaCstmrsForm" method="post" action="">
                                                <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                                <input type="hidden" name="formcancel" id="formcancel" value=""/>
                                                <input type="hidden" name="orgid" id="orgid" value="<?php echo $orgDetails->getIdorgs(); ?>"/>

                                                <h3><?php echo getLocaleText('EDIT_ORG_MSG_34', TXT_A); ?></h3>
                                                <p><?php echo getLocaleText('EDIT_ORG_MSG_35', TXT_A); ?></p>

                                                <fieldset style="min-height: 260px">

                                                    <legend></legend>
                                                    <br/>

                                                    <div class="alert alert-block alert-form-wizard-error" id="divDynaCstmrs" style="display: none">
                                                        <?php echo getLocaleText('EDIT_ORG_MSG_36', TXT_A); ?>
                                                    </div>

                                                    <?php
                                                        $orgDynaCustomer = $orgDao->getOrgCustomerDynaFields();
                                                        $dynaFieldsProcessedDetails = $orgDynaCustomer->getDynaFieldsProcessedDetails();
                                                        $tmpFlag = TRUE;
                                                        foreach($customerDynaFields as $cstmrDynaField){ 
                                                            $cbName = $cstmrDynaField->getIdDynaFields();
                                                            $btName = 'bt_'.$cstmrDynaField->getIdDynaFields();
                                                            
                                                            $childList = $cstmrDynaField->getHtmlListValues();
                                                            
                                                            $checked = '';
                                                            $btColor = '';
                                                            $xEditSavedValues = '';
                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                if ($value['fieldId'] == $cbName){
                                                                    $checked = 'checked';
                                                                    $btColor = 'background-color:#e6e6e6;';
                                                                    $xEditSavedValues = $value['fieldValues'];
                                                                    //$xEditSavedValues = str_replace(',', ',&nbsp;&nbsp;&nbsp;&nbsp;', $xEditSavedValues);
                                                                }
                                                            }
                                                            if (empty($checked)){
                                                                $cstmrs_consCBIds = $cstmrs_consCBIds . 'input[id=\'' . $cbName . '\'], ';
                                                                $cstmrs_consBtIds = $cstmrs_consBtIds . 'a[id=\'' . $btName . '\'], ';
                                                            }
                                                            else{
                                                                $cstmrs_consCBIds_ckd = $cstmrs_consCBIds_ckd . 'input[id=\'' . $cbName . '\'], ';
                                                                $cstmrs_consBtIds_ckd = $cstmrs_consBtIds_ckd . 'a[id=\'' . $btName . '\'], ';
                                                            }
                                                            
                                                        ?>
                                                            <?php if ($tmpFlag){ ?>
                                                            <div class="form-group" style="margin-bottom: 0px;">
                                                                <label class="col-md-3 control-label"><?php echo getLocaleText('EDIT_ORG_MSG_45', TXT_A); ?> </label>
                                                            <?php $tmpFlag = FALSE; } else { ?> 
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label"></label>
                                                            <?php } ?>
                                                                <div class="col-md-6">
                                                                    <label class="col-md-12">
                                                                        <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $cstmrDynaField->getIdDynaFields(); ?>" onclick="javascript:toggleDynaFieldsCheckbox('<?php echo $cbName; ?>','<?php echo $btName; ?>');" <?php echo $checked; ?> >
                                                                        <span class="col-md-12">
                                                                            <a href="javascript:toggleDynaFieldsButton('<?php echo $cbName; ?>','<?php echo $btName; ?>');" id="<?php echo $btName; ?>" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="<?php echo $cstmrDynaField->getHtmlType(); ?>" 
                                                                               data-content="<?php echo $cstmrDynaField->getDescription(); ?>" style="margin-left: 15px;width: 300px;<?php echo $btColor; ?>"><?php echo $cstmrDynaField->getName(); ?><strong></strong></a>                                                                    
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <?php if (!empty($childList) && sizeof($childList) > 0){
                                                                    $xeditId = 'x-'.$cbName;
                                                                    //$listArr = explode('|', $childList);
                                                                    $listArr = $childList;
                                                                    $xeditSrc = '';
                                                                    foreach ($listArr as $key => $value) {
                                                                        $xeditSrc = $xeditSrc . '{value: \'' . $key . '\', text: \'' . $value . '\'}, ';
                                                                    }
                                                                    $xeditSrc = substr($xeditSrc, 0, strlen($xeditSrc)-2);
                                                                    $xEditControl = array(
                                                                                        'xeditId' => $xeditId,
                                                                                        'xsource' => $xeditSrc,
                                                                                        'xSelectedValues' => $xEditSavedValues
                                                                                    );
                                                                    array_push($xEditControls, $xEditControl);
                                                                    ?>
                                                                    <div class="form-group">
                                                                        <label class="col-md-3 control-label"></label>
                                                                        <div class="col-md-8" style="margin-left: 60px;margin-top: -12px;margin-bottom: 5px;">
                                                                            <label>&nbsp;&nbsp;<?php echo getLocaleText('EDIT_ORG_MSG_46', TXT_A); ?> : </label>
                                                                            <a href="form-x-editable.html#" id="<?php echo $xeditId; ?>" data-original-title="<?php echo getLocaleText('EDIT_ORG_MSG_47', TXT_A); ?>" data-placement="right" ></a>
                                                                        </div>
                                                                    </div>
                                                            <?php } ?>

                                                    <?php } 
                                                    $cstmrs_consCBIds = substr($cstmrs_consCBIds, 0, strlen($cstmrs_consCBIds)-2);
                                                    $cstmrs_consBtIds = substr($cstmrs_consBtIds, 0, strlen($cstmrs_consBtIds)-2);
                                                    $cstmrs_consCBIds_ckd = substr($cstmrs_consCBIds_ckd, 0, strlen($cstmrs_consCBIds_ckd)-2);
                                                    $cstmrs_consBtIds_ckd = substr($cstmrs_consBtIds_ckd, 0, strlen($cstmrs_consBtIds_ckd)-2);
                                                    ?>

                                                </fieldset>

                                                <div class="form-actions" style="border: none;background: none;border-top: 1px solid rgba(0,0,0,.1);">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <button type="button" class="btn btn-default bg-color-blueLight txt-color-white" id="btnCancel-cst">
                                                                <i class="glyphicon glyphicon-remove"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_1', TXT_A); ?>
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-default" id="btnReset-cst">
                                                                <i class="fa fa-refresh"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_2', TXT_A); ?>
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-primary" id="btnUpdate-cst">
                                                                <i class="fa fa-save"></i> <?php echo getLocaleText('EDIT_ORG_MSG_BTN_3', TXT_A); ?>
                                                            </button>
                                                            <br/><br/>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>
                            <!-- end widget content -->

                        </div>
                        <!-- end widget div -->

                    </div>
                    <!-- end widget -->

                </article>
                <!-- WIDGET END -->

            </div>
            <!-- end row -->

        </section>
        
        <?php
        }
        else if (empty ($orgDao->getMsgType())) { ?>
            <div class="alert alert-danger fade in">
                <i class="fa-fw fa fa-times"></i>
                <?php echo getLocaleText('EDIT_ORG_MSG_37', TXT_A); ?>
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

<script src="<?php echo ASSETS_URL; ?>/js/plugin/fuelux/wizard/wizard.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/dropzone/dropzone.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/x-editable/moment.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/x-editable/jquery.mockjax.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/x-editable/x-editable.min.js"></script>


<script>

    $(document).ready(function() {
        
        pageSetUp();
        
        var $validator = $("#orgDetailsForm").validate({

                rules: {
                    orgname: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    uname: {
                        required: true,
                        minlength : 5,
                        maxlength : 20
                    },
                    pwd: {
                        required: true,
                        minlength : 5,
                        maxlength : 20
                    }
                },

                messages: {
                    orgname: {
                        required: "<?php echo getLocaleText('EDIT_ORG_MSG_VALID_1', TXT_A); ?>"
                    },
                    email: {
                        required: "<?php echo getLocaleText('EDIT_ORG_MSG_VALID_2', TXT_A); ?>",
                        email: "<?php echo getLocaleText('EDIT_ORG_MSG_VALID_3', TXT_A); ?>"
                    },
                    uname: {
                        required: "<?php echo getLocaleText('EDIT_ORG_MSG_VALID_4', TXT_A); ?>",
                        minlength: "<?php echo getLocaleText('EDIT_ORG_MSG_VALID_5', TXT_A); ?>",
                        maxlength: "<?php echo getLocaleText('EDIT_ORG_MSG_VALID_6', TXT_A); ?>"
                    },
                    pwd: {
                        required: "<?php echo getLocaleText('EDIT_ORG_MSG_VALID_7', TXT_A); ?>",
                        minlength: "<?php echo getLocaleText('EDIT_ORG_MSG_VALID_8', TXT_A); ?>",
                        maxlength: "<?php echo getLocaleText('EDIT_ORG_MSG_VALID_9', TXT_A); ?>"
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
            
            
        /*
         * Saving the forms
         */    
        $('#btnUpdate-org').on('click', function () {
            var $valid = $("#orgDetailsForm").valid();
            //alert($valid);
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            else{
                document.forms['orgDetailsForm']['formsubmit'].value = '<?php echo ORG_UPDATE_PART_ORG_DETAILS; ?>';
                $("#orgDetailsForm").submit();
            }
        });
        
        $('#btnUpdate-cobrnd').on('click', function () {
            document.forms['coBrandForm']['formsubmit'].value = '<?php echo ORG_UPDATE_PART_ORG_COBRADING; ?>';
            $("#coBrandForm").submit();
        });
        
        $('#btnUpdate-prd').on('click', function () {
            if (!$().dynaFieldsValidate_Prd()){
                document.getElementById('divDynaPrd').style.display = 'block';
                return false;
            }
            else{
                document.forms['dynaPrdsForm']['formsubmit'].value = '<?php echo ORG_UPDATE_PART_ORG_PRODUCT_DETAILS; ?>';
                <?php
                foreach($xEditControls as $xEditControl){
                    $xeditId = $xEditControl['xeditId'];
                    $xeditSelValues = $xEditControl['xSelectedValues'];
                    if (strpos($xeditId, DYNAFIELDS_PRODUCT_CBID_PREFIX)){ ?>
                        $('#<?php echo $xeditId; ?>').editable('submit');
                <?php 
                    }
                }
                ?>
                $("#dynaPrdsForm").submit();
            }
        });
        
        $('#btnUpdate-tsk').on('click', function () {
            if (!$().dynaFieldsValidate_Tsks()){
                document.getElementById('divDynaTsks').style.display = 'block';
                return false;
            }
            else{
                document.forms['dynaTsksForm']['formsubmit'].value = '<?php echo ORG_UPDATE_PART_ORG_TASK_DETAILS; ?>';
                <?php
                foreach($xEditControls as $xEditControl){
                    $xeditId = $xEditControl['xeditId'];
                    $xeditSelValues = $xEditControl['xSelectedValues'];
                    if (strpos($xeditId, DYNAFIELDS_TASK_CBID_PREFIX)){ ?>
                        $('#<?php echo $xeditId; ?>').editable('submit');
                <?php 
                    }
                }
                ?>
                $("#dynaTsksForm").submit();
            }
        })
        
        $('#btnUpdate-wrk').on('click', function () {
            if (!$().dynaFieldsValidate_Wrks()){
                document.getElementById('divDynaWrks').style.display = 'block';
                return false;
            }
            else{
                document.forms['dynaWrksForm']['formsubmit'].value = '<?php echo ORG_UPDATE_PART_ORG_WORKER_DETAILS; ?>';
                <?php
                foreach($xEditControls as $xEditControl){
                    $xeditId = $xEditControl['xeditId'];
                    $xeditSelValues = $xEditControl['xSelectedValues'];
                    if (strpos($xeditId, DYNAFIELDS_WORKER_CBID_PREFIX)){ ?>
                        $('#<?php echo $xeditId; ?>').editable('submit');
                <?php 
                    }
                }
                ?>
                $("#dynaWrksForm").submit();
            }
        });
        
        $('#btnUpdate-cst').on('click', function () {
            if (!$().dynaFieldsValidate_Cstmrs()){
                document.getElementById('divDynaCstmrs').style.display = 'block';
                return false;
            }
            else{
                document.forms['dynaCstmrsForm']['formsubmit'].value = '<?php echo ORG_UPDATE_PART_ORG_CUSTOMER_DETAILS; ?>';
                <?php
                foreach($xEditControls as $xEditControl){
                    $xeditId = $xEditControl['xeditId'];
                    $xeditSelValues = $xEditControl['xSelectedValues'];
                    if (strpos($xeditId, DYNAFIELDS_CUSTOMER_CBID_PREFIX)){ ?>
                        $('#<?php echo $xeditId; ?>').editable('submit');
                <?php 
                    }
                }
                ?>
                $("#dynaCstmrsForm").submit();
            }
        });
        
        /*
         * Cancelling the forms
         */  
        $('#btnCancel-org').on('click', function () {
            location.href = 'org.php';
        });
        
        $('#btnCancel-cobrnd').on('click', function () {
            document.forms['coBrandForm']['formsubmit'].value = '<?php echo ORG_UPDATE_PART_ORG_COBRADING; ?>';
            document.forms['coBrandForm']['formcancel'].value = 'true';
            $("#coBrandForm").submit();
        });
        
        $('#btnCancel-prd').on('click', function () {
            document.forms['dynaPrdsForm']['formsubmit'].value = '<?php echo ORG_UPDATE_PART_ORG_PRODUCT_DETAILS; ?>';
            document.forms['dynaPrdsForm']['formcancel'].value = 'true';
            $("#dynaPrdsForm").submit();
        });
        
        $('#btnCancel-tsk').on('click', function () {
            document.forms['dynaTsksForm']['formsubmit'].value = '<?php echo ORG_UPDATE_PART_ORG_TASK_DETAILS; ?>';
            document.forms['dynaTsksForm']['formcancel'].value = 'true';
            $("#dynaTsksForm").submit();
        });
        
        $('#btnCancel-wrk').on('click', function () {
            document.forms['dynaWrksForm']['formsubmit'].value = '<?php echo ORG_UPDATE_PART_ORG_WORKER_DETAILS; ?>';
            document.forms['dynaWrksForm']['formcancel'].value = 'true';
            $("#dynaWrksForm").submit();
        });
        
        $('#btnCancel-cst').on('click', function () {
            document.forms['dynaCstmrsForm']['formsubmit'].value = '<?php echo ORG_UPDATE_PART_ORG_CUSTOMER_DETAILS; ?>';
            document.forms['dynaCstmrsForm']['formcancel'].value = 'true';
            $("#dynaCstmrsForm").submit();
        });
        
        /*
         * Reset Forms
         */
        $('#btnReset-org').on('click', function () {
            $validator.resetForm();
            document.getElementById('orgDetailsForm').reset();
        });
        
        $('#btnReset-prd').on('click', function () {
            $("<?php echo $prd_consCBIds; ?>").prop('checked',false);
            $("<?php echo $prd_consCBIds_ckd; ?>").prop('checked',true);
            $("<?php echo $prd_consBtIds; ?>").css('background-color', '#ffffff');
            $("<?php echo $prd_consBtIds_ckd; ?>").css('background-color', '#e6e6e6');
            document.getElementById('divDynaPrd').style.display = 'none';
            
            <?php
            foreach($xEditControls as $xEditControl){
                $xeditId = $xEditControl['xeditId'];
                $xeditSelValues = $xEditControl['xSelectedValues'];
                if (strpos($xeditId, DYNAFIELDS_PRODUCT_CBID_PREFIX)){ ?>
                    $('#<?php echo $xeditId; ?>').editable('setValue', '<?php echo $xeditSelValues; ?>', '<?php echo $xeditSelValues; ?>');
            <?php 
                }
            }
            ?>
        });
        
        $('#btnReset-tsk').on('click', function () {
            $("<?php echo $tsks_consCBIds; ?>").prop('checked',false);
            $("<?php echo $tsks_consCBIds_ckd; ?>").prop('checked',true);
            $("<?php echo $tsks_consBtIds; ?>").css('background-color', '#ffffff');
            $("<?php echo $tsks_consBtIds_ckd; ?>").css('background-color', '#e6e6e6');
            document.getElementById('divDynaTsks').style.display = 'none';
            
            <?php
            foreach($xEditControls as $xEditControl){
                $xeditId = $xEditControl['xeditId'];
                $xeditSelValues = $xEditControl['xSelectedValues'];
                if (strpos($xeditId, DYNAFIELDS_TASK_CBID_PREFIX)){ ?>
                    $('#<?php echo $xeditId; ?>').editable('setValue', '<?php echo $xeditSelValues; ?>', '<?php echo $xeditSelValues; ?>');
            <?php 
                }
            }
            ?>
        });
        
        $('#btnReset-wrk').on('click', function () {
            $("<?php echo $wrks_consCBIds; ?>").prop('checked',false);
            $("<?php echo $wrks_consCBIds_ckd; ?>").prop('checked',true);
            $("<?php echo $wrks_consBtIds; ?>").css('background-color', '#ffffff');
            $("<?php echo $wrks_consBtIds_ckd; ?>").css('background-color', '#e6e6e6');
            document.getElementById('divDynaWrks').style.display = 'none';
            
            <?php
            foreach($xEditControls as $xEditControl){
                $xeditId = $xEditControl['xeditId'];
                $xeditSelValues = $xEditControl['xSelectedValues'];
                if (strpos($xeditId, DYNAFIELDS_WORKER_CBID_PREFIX)){ ?>
                    $('#<?php echo $xeditId; ?>').editable('setValue', '<?php echo $xeditSelValues; ?>', '<?php echo $xeditSelValues; ?>');
            <?php 
                }
            }
            ?>
        });
        
        $('#btnReset-cst').on('click', function () {
            $("<?php echo $cstmrs_consCBIds; ?>").prop('checked',false);
            $("<?php echo $cstmrs_consCBIds_ckd; ?>").prop('checked',true);
            $("<?php echo $cstmrs_consBtIds; ?>").css('background-color', '#ffffff');
            $("<?php echo $cstmrs_consBtIds_ckd; ?>").css('background-color', '#e6e6e6');
            document.getElementById('divDynaCstmrs').style.display = 'none';
            
            <?php
            foreach($xEditControls as $xEditControl){
                $xeditId = $xEditControl['xeditId'];
                $xeditSelValues = $xEditControl['xSelectedValues'];
                if (strpos($xeditId, DYNAFIELDS_CUSTOMER_CBID_PREFIX)){ ?>
                    $('#<?php echo $xeditId; ?>').editable('setValue', '<?php echo $xeditSelValues; ?>', '<?php echo $xeditSelValues; ?>');
            <?php 
                }
            }
            ?>
        });
        
        /*
        * Dyna Fields Functions
        */
        $.fn.dynaFieldsValidate_Prd = function(){

            var fields1 = $("<?php echo $prd_consCBIds; ?>").serializeArray();
            var fields2 = $("<?php echo $prd_consCBIds_ckd; ?>").serializeArray();
            return fields1.length + fields2.length > 0;
        }
        $.fn.dynaFieldsValidate_Tsks = function(){
            
            var fields1 = $("<?php echo $tsks_consCBIds; ?>").serializeArray();
            var fields2 = $("<?php echo $tsks_consCBIds_ckd; ?>").serializeArray();
            return fields1.length + fields2.length > 0;
        }
        $.fn.dynaFieldsValidate_Wrks = function(){
            
            var fields1 = $("<?php echo $wrks_consCBIds; ?>").serializeArray();
            var fields2 = $("<?php echo $wrks_consCBIds_ckd; ?>").serializeArray();
            return fields1.length + fields2.length > 0;
        }
        $.fn.dynaFieldsValidate_Cstmrs = function(){
            
            var fields1 = $("<?php echo $cstmrs_consCBIds; ?>").serializeArray();
            var fields2 = $("<?php echo $cstmrs_consCBIds_ckd; ?>").serializeArray();
            return fields1.length + fields2.length > 0;
        }
        
        /*
         * File upload functions
         */
        Dropzone.autoDiscover = false;
        $("div#logoupload").dropzone({
            //url: "/newOrg.php",
            addRemoveLinks : true,
            maxFilesize: 3,
            paramName: 'logo',
            maxFiles: 1,
            dictDefaultMessage: '<span class="text-center"><span class="font-md visible-xs-block visible-sm-block visible-lg-block"><span class="font-md"><i class="fa fa-caret-right text-danger"></i> <?php echo getLocaleText("EDIT_ORG_MSG_38-1", TXT_A); ?> <span class="font-xs"><?php echo getLocaleText("EDIT_ORG_MSG_38-2", TXT_A); ?></span></span><span>&nbsp&nbsp<h4 class="display-inline"> <?php echo getLocaleText("EDIT_ORG_MSG_39", TXT_A); ?></h4></span>',
            dictResponseError: '<?php echo getLocaleText("EDIT_ORG_MSG_40", TXT_A); ?>',
            acceptedFiles: '.jpg, .jpeg, .png',
            dictInvalidFileType: '<?php echo getLocaleText("EDIT_ORG_MSG_41", TXT_A); ?>',
            dictFileTooBig: '<?php echo getLocaleText("EDIT_ORG_MSG_42-1", TXT_A); ?> ({{filesize}}MB) <?php echo getLocaleText("EDIT_ORG_MSG_42-2", TXT_A); ?> ({{maxFilesize}}MB)',
            dictMaxFilesExceeded: '<?php echo getLocaleText("EDIT_ORG_MSG_43", TXT_A); ?>'
        });
            
        
    });
    
    function toggleDynaFieldsButton(chkbox, btnId){
        document.getElementById('divDynaPrd').style.display = 'none';
        document.getElementById('divDynaTsks').style.display = 'none';
        document.getElementById('divDynaWrks').style.display = 'none';
        document.getElementById('divDynaCstmrs').style.display = 'none';
        
        if (document.getElementById(chkbox).checked) {
            document.getElementById(chkbox).checked=false;
            document.getElementById(btnId).style.backgroundColor='#ffffff';
            document.getElementById(btnId).type=0;
        }
        else{
            document.getElementById(chkbox).checked=true;
            document.getElementById(btnId).style.backgroundColor='#e6e6e6';
            document.getElementById(btnId).type=1;
        }
    }
    
    function toggleDynaFieldsCheckbox(chkbox, btnId){
        document.getElementById('divDynaPrd').style.display = 'none';
        document.getElementById('divDynaTsks').style.display = 'none';
        document.getElementById('divDynaWrks').style.display = 'none';
        document.getElementById('divDynaCstmrs').style.display = 'none';
        
        if (document.getElementById(chkbox).checked) {
            document.getElementById(chkbox).checked=true;
            document.getElementById(btnId).style.backgroundColor='#e6e6e6';
            document.getElementById(btnId).type=1;
        }
        else{
            document.getElementById(chkbox).checked=false;
            document.getElementById(btnId).style.backgroundColor='#ffffff';
            document.getElementById(btnId).type=0;
        }
    }
    
    <?php
    
    foreach($xEditControls as $xEditControl){
        $xeditId = $xEditControl['xeditId'];
        $xeditSrc = $xEditControl['xsource'];
        $xeditSelValues = $xEditControl['xSelectedValues'];
        ?>
            
        $('#<?php echo $xeditId; ?>').editable({
            url: 'xEdit.php',
            pk: 1,
            source: [
                <?php echo $xeditSrc; ?> 
            ],
            unsavedclass: null,
            type: 'checklist',
            emptytext: '<?php echo getLocaleText("EDIT_ORG_MSG_48", TXT_A); ?>',
            inputclass: 'popover-content-2',
            display: function(value, sourceData) {
                    var html = [],
                        checked = $.fn.editableutils.itemsByValue(value, sourceData);
                    if(checked.length) {
                        $.each(checked, function(i, v) { html.push($.fn.editableutils.escape(v.text)); });
                        $(this).html(html.join(',&nbsp;&nbsp;&nbsp;&nbsp;'));
                    } else {
                        $(this).empty(); 
                    }
                 }
        });
        //$('#<?php echo $xeditId; ?>').editable('setValue', 'Scheduled,Start,Stop,Incidence,Job Report', 'Scheduled,Start,Stop,Incidence,Job Report');
        $('#<?php echo $xeditId; ?>').editable('setValue', '<?php echo $xeditSelValues; ?>', '<?php echo $xeditSelValues; ?>');

    <?php 
    }
    ?>
    
</script>

<?php

    function preInputsInspection(OrgInputs $orgInputs, OrgDao $orgDao, $updatedPart){
        
        $orgInputs->setIdorgs(mysql_real_escape_string($_POST['orgid']));
        
        switch ($updatedPart) {
            
            case ORG_UPDATE_PART_ORG_DETAILS:
                
                $orgInputs->setIn_name(mysql_real_escape_string($_POST['orgname']));
                $orgInputs->setIn_phone(mysql_real_escape_string($_POST['phone']));
                $orgInputs->setIn_email(mysql_real_escape_string($_POST['email']));
                $orgInputs->setIn_username(mysql_real_escape_string($_POST['uname']));
                $orgInputs->setIn_password(mysql_real_escape_string($_POST['pwd']));
                
                break;
            
            case ORG_UPDATE_PART_ORG_COBRADING:
                
                break;
            
            case ORG_UPDATE_PART_ORG_PRODUCT_DETAILS:
                
                $postPrdIds = '';
                $productDynaFields = $orgDao->getProductDynaFields();
                foreach($productDynaFields as $prdDynaField){ 
                    $cbId = $prdDynaField->getIdDynaFields();
                    if (isset($_POST[$cbId])){
                        $postPrdIds = $postPrdIds . substr($_POST[$cbId], strlen(DYNAFIELDS_PRODUCT_CBID_PREFIX)) . '|';
                    }
                }
                if (!empty($postPrdIds)){
                    $postPrdIds = substr($postPrdIds, 0, strlen($postPrdIds)-1);
                    $orgInputs->setPrdDynaCBIds($postPrdIds);
                }
                
                break;
            
            case ORG_UPDATE_PART_ORG_TASK_DETAILS:
                
                $postTskIds = '';
                $tasksDynaFields = $orgDao->getTaskDynaFields();
                foreach($tasksDynaFields as $tsksDynaField){
                    $cbId = $tsksDynaField->getIdDynaFields();
                    if (isset($_POST[$cbId])){
                        $postTskIds = $postTskIds . substr($_POST[$cbId], strlen(DYNAFIELDS_TASK_CBID_PREFIX)) . '|';
                    }
                }
                if (!empty($postTskIds)){
                    $postTskIds = substr($postTskIds, 0, strlen($postTskIds)-1);
                    $orgInputs->setTskDynaCBIds($postTskIds);
                }
                    
                break;

            case ORG_UPDATE_PART_ORG_WORKER_DETAILS:
                
                $postWrkIds = '';
                $workerDynaFields = $orgDao->getWorkerDynaFields();
                foreach($workerDynaFields as $wrksDynaField){
                    $cbId = $wrksDynaField->getIdDynaFields();
                    if (isset($_POST[$cbId])){
                        $postWrkIds = $postWrkIds . substr($_POST[$cbId], strlen(DYNAFIELDS_WORKER_CBID_PREFIX)) . '|';
                    }
                }
                if (!empty($postWrkIds)){
                    $postWrkIds = substr($postWrkIds, 0, strlen($postWrkIds)-1);
                    $orgInputs->setWrkDynaCBIds($postWrkIds);
                }

                break;

            case ORG_UPDATE_PART_ORG_CUSTOMER_DETAILS:
                
                $postCstIds = '';
                $customerDynaFields = $orgDao->getCustomerDynaFields();
                foreach($customerDynaFields as $cstsDynaField){
                    $cbId = $cstsDynaField->getIdDynaFields();
                    if (isset($_POST[$cbId])){
                        $postCstIds = $postCstIds . substr($_POST[$cbId], strlen(DYNAFIELDS_CUSTOMER_CBID_PREFIX)) . '|';
                    }
                }
                if (!empty($postCstIds)){
                    $postCstIds = substr($postCstIds, 0, strlen($postCstIds)-1);
                    $orgInputs->setCstDynaCBIds($postCstIds);
                }

                break;
            
            default:
                break;
                
        }

        
    }

?>
