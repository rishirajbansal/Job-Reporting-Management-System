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

include_once("sessionMgmt.php");

require_once(dirname(__FILE__) . "/../commonInc/init.php");
require_once(dirname(__FILE__) . "/inc/config.ui.php");


/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('NEW_ORG_MSG_1', TXT_A);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "fuelux.css";

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
$tsks_consCBIds = '';
$wrks_consCBIds = '';
$cstmrs_consCBIds = '';
$prd_consBtIds = '';
$tsks_consBtIds = '';
$wrks_consBtIds = '';
$cstmrs_consBtIds = '';

$xEditControls = array();


//Load prerequisties for Orgnaization creation
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

if (isset($_POST['formsubmit'])){
    
    $isFormCancel = $_POST['formcancel'];
    
    if ($isFormCancel === 'true'){
        $orgDao->cancelOrg();
        //header("location: org.php");
        echo("<script>location.href = 'org.php';</script>");
        exit();
    }
    
    preInputsInspection($orgInputs, $orgDao);
    
    $saveFlag = $orgDao->saveOrg($orgInputs);
    
    if ($saveFlag){
        
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

}
else{
    //Remove the images everytime this page is loaded first time to handle the case if the file is removed from dropzone but not removed from file server and it get saved eventhough it was removed
    //Check it later on
    /*$ds = DIRECTORY_SEPARATOR; 
    $storeFolder = '..' . $ds . 'tempuploads';
    $storeFolder_thumb = '..' . $ds . 'tempuploads' . $ds . 'thumbs';
    removeImageFiles($storeFolder, $storeFolder_thumb);*/
}

/*------------- End Form Submissions ---------*/

?>

<!-- MAIN PANEL -->
<div id="main" role="main">
    
    <?php
        $breadcrumbs[getLocaleText('NEW_ORG_MSG_2', TXT_A)] = "org.php";
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-building"></i> <?php echo getLocaleText('NEW_ORG_MSG_3', TXT_A); ?> </h1>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <a href="org.php" class="btn bg-color-blueLight txt-color-white btn-lg pull-right header-btn hidden-mobile"><i class="fa fa-circle-arrow-up fa-lg"></i> <?php echo getLocaleText('NEW_ORG_MSG_4', TXT_A); ?></a>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <?php
            if (null == $orgDao->getMsgType() || ($orgDao->getMsgType() != SUBMISSION_MSG_TYPE_SUCCESS && $orgDao->getMsgType() != SUBMISSION_MSG_TYPE_COMPLETESUCCESS)){ ?>
        
                <section id="widget-grid" class="">

                    <!-- row -->
                    <div class="row">

                        <!-- NEW WIDGET START -->
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            <!-- Widget ID (each widget will need unique ID)-->
                            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" 
                                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">

                                <header>
                                    <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                                    <h2><?php echo getLocaleText('NEW_ORG_MSG_5', TXT_A); ?> </h2>
                                </header>

                                <!-- widget div-->
                                <div>

                                    <!-- widget edit box -->
                                    <div class="jarviswidget-editbox">

                                    </div>
                                    <!-- end widget edit box -->

                                    <!-- widget content -->
                                    <div class="widget-body fuelux">

                                        <form class="form-horizontal" id="orgDetailsForm" name="orgDetailsForm" method="post" action="">
                                            <input type="hidden" name="formsubmit" id="formsubmit" />
                                            <input type="hidden" name="formcancel" id="formcancel" />

                                            <div class="wizard" data-initialize="wizard" id="orgDetailsWiz">

                                                <div class="steps-container">

                                                    <ul class="steps">
                                                        <li data-step="1" class="active">
                                                            <span class="badge badge-info">1</span><?php echo getLocaleText('NEW_ORG_MSG_44', TXT_A); ?> 1<span class="chevron"></span>
                                                        </li>
                                                        <li data-step="2">
                                                            <span class="badge">2</span><?php echo getLocaleText('NEW_ORG_MSG_44', TXT_A); ?> 2<span class="chevron"></span>
                                                        </li>
                                                        <li data-step="3">
                                                            <span class="badge">3</span><?php echo getLocaleText('NEW_ORG_MSG_44', TXT_A); ?> 3<span class="chevron"></span>
                                                        </li>
                                                        <li data-step="4">
                                                            <span class="badge">4</span><?php echo getLocaleText('NEW_ORG_MSG_44', TXT_A); ?> 4<span class="chevron"></span>
                                                        </li>
                                                        <li data-step="5">
                                                            <span class="badge">5</span><?php echo getLocaleText('NEW_ORG_MSG_44', TXT_A); ?> 5<span class="chevron"></span>
                                                        </li>
                                                        <li data-step="6">
                                                            <span class="badge">6</span><?php echo getLocaleText('NEW_ORG_MSG_44', TXT_A); ?> 6<span class="chevron"></span>
                                                        </li>
                                                        <li data-step="7">
                                                            <span class="badge">7</span><?php echo getLocaleText('NEW_ORG_MSG_44', TXT_A); ?> 7<span class="chevron"></span>
                                                        </li>
                                                    </ul>

                                                </div>

                                                <div class="actions">
                                                    <button type="button" class="btn btn-default btn-sm " id="btnCancel" style="font-size: 13px;">
                                                        <i class="glyphicon glyphicon-remove"></i>&nbsp;<?php echo getLocaleText('NEW_ORG_MSG_BTN_1', TXT_A); ?>
                                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <button type="button" class="btn btn-sm btn-primary btn-prev" id="btnPrev" style="font-size: 13px;">
                                                        <i class="fa fa-arrow-left"></i><?php echo getLocaleText('NEW_ORG_MSG_BTN_2', TXT_A); ?>
                                                    </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <button type="button" class="btn btn-sm btn-success btn-next" id="btnNext" data-last="Finish" style="font-size: 13px;">
                                                        <?php echo getLocaleText('NEW_ORG_MSG_BTN_3', TXT_A); ?><i class="fa fa-arrow-right"></i>
                                                    </button>
                                                </div>

                                                <div class="step-content" style="background-color: #ffffff;">

                                                    <div class="step-pane active" id="step1" data-step="1">

                                                        <h3><strong><?php echo getLocaleText('NEW_ORG_MSG_12', TXT_A); ?></strong></h3>
                                                        <p><?php echo getLocaleText('NEW_ORG_MSG_13', TXT_A); ?></p>

                                                        <fieldset>

                                                            <legend></legend>
                                                            <br/>

                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"><?php echo getLocaleText('NEW_ORG_MSG_14', TXT_A); ?> </label>
                                                                <div class="col-md-5">
                                                                    <div class="input-group input-group-md">
                                                                        <span class="input-group-addon "><i class="fa fa-building fa-fw"></i></span>
                                                                        <input class="form-control" placeholder="<?php echo getLocaleText('NEW_ORG_MSG_14', TXT_A); ?>" type="text" name="orgname" id="orgname">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"><?php echo getLocaleText('NEW_ORG_MSG_15', TXT_A); ?> </label>
                                                                <div class="col-md-3">
                                                                    <div class="input-group input-group-md">
                                                                        <span class="input-group-addon "><i class="fa fa-phone fa-fw"></i></span>
                                                                        <input class="form-control" data-mask="+99 (999) 999-9999" data-mask-placeholder= "X" placeholder="+34" type="text" name="phone" id="phone">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"><?php echo getLocaleText('NEW_ORG_MSG_16', TXT_A); ?> </label>
                                                                <div class="col-md-5">
                                                                    <div class="input-group input-group-md">
                                                                        <span class="input-group-addon "><i class="fa fa-envelope fa-fw"></i></span>
                                                                        <input class="form-control" placeholder="email@address.com" type="text" name="email" id="email">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"><?php echo getLocaleText('NEW_ORG_MSG_17', TXT_A); ?> </label>
                                                                <div class="col-md-3">
                                                                    <div class="input-group input-group-md">
                                                                        <span class="input-group-addon "><i class="fa fa-user fa-fw"></i></span>
                                                                        <input class="form-control" placeholder="<?php echo getLocaleText('NEW_ORG_MSG_17', TXT_A); ?>" type="text" name="uname" id="uname">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"><?php echo getLocaleText('NEW_ORG_MSG_18', TXT_A); ?> </label>
                                                                <div class="col-md-3">
                                                                    <div class="input-group input-group-md">
                                                                        <span class="input-group-addon "><i class="fa fa-lock fa-fw"></i></span>
                                                                        <input class="form-control" placeholder="<?php echo getLocaleText('NEW_ORG_MSG_18', TXT_A); ?>" type="password" name="pwd" id="pwd">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </fieldset>

                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <button type="button" class="btn btn-default" id="btnReset">
                                                                        <i class="fa fa-refresh"></i> <?php echo getLocaleText('NEW_ORG_MSG_BTN_4', TXT_A); ?>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="step-pane" id="step2" data-step="2">

                                                        <h3><strong><?php echo getLocaleText('NEW_ORG_MSG_19', TXT_A); ?></strong></h3>
                                                        <p><?php echo getLocaleText('NEW_ORG_MSG_20', TXT_A); ?></p>

                                                        <fieldset>

                                                            <legend></legend>
                                                            <br/>

                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"><strong><?php echo getLocaleText('NEW_ORG_MSG_21', TXT_A); ?> </strong></label>
                                                                <div class="col-md-5">
                                                                    <div class="input-group input-group-md">
                                                                        <div class="dropzone" id="logoupload" action="cobrand.php">
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <br/><br/><br/><br/>

                                                        </fieldset>

                                                    </div>
                                                    
                                                    <div class="step-pane" id="step3" data-step="3">

                                                        <h3><strong><?php echo getLocaleText('NEW_ORG_MSG_23', TXT_A); ?></strong></h3>
                                                        <p><?php echo getLocaleText('NEW_ORG_MSG_24', TXT_A); ?></p>

                                                        <fieldset>

                                                            <legend></legend>
                                                            <br/>
                                                            
                                                            <div class="alert alert-block alert-form-wizard-error" id="divDynaPrd" style="display: none">
                                                                <?php echo getLocaleText('NEW_ORG_MSG_25', TXT_A); ?>
                                                            </div>
                                                            
                                                            <?php
                                                                $tmpFlag = TRUE;
                                                                foreach($productDynaFields as $prdDynaField){ 
                                                                    $cbName = $prdDynaField->getIdDynaFields();
                                                                    $btName = 'bt_'.$prdDynaField->getIdDynaFields();
                                                                    
                                                                    $childList = $prdDynaField->getHtmlListValues();
                                                                    
                                                                    $prd_consCBIds = $prd_consCBIds . 'input[id=\'' . $cbName . '\'], ';
                                                                    $prd_consBtIds = $prd_consBtIds . 'a[id=\'' . $btName . '\'], ';
                                                                ?>
                                                                    <?php if ($tmpFlag){ ?>
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <label class="col-md-2 control-label"><?php echo getLocaleText('NEW_ORG_MSG_26', TXT_A); ?> </label>
                                                                    <?php $tmpFlag = FALSE; } else { ?> 
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label"></label>
                                                                    <?php } ?>
                                                                        <div class="col-md-6">
                                                                            <label class="col-md-12">
                                                                                <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $prdDynaField->getIdDynaFields(); ?>" onclick="javascript:toggleDynaFieldsCheckbox('<?php echo $cbName; ?>','<?php echo $btName; ?>');">
                                                                                <span class="col-md-12">
                                                                                    <a href="javascript:toggleDynaFieldsButton('<?php echo $cbName; ?>','<?php echo $btName; ?>');" id="<?php echo $btName; ?>" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="<?php echo $prdDynaField->getHtmlType(); ?>" 
                                                                                       data-content="<?php echo $prdDynaField->getDescription(); ?>" style="margin-left: 15px;width: 300px;"><?php echo $prdDynaField->getName(); ?><strong></strong></a>                                                                    
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
                                                                                                'xsource' => $xeditSrc
                                                                                            );
                                                                            array_push($xEditControls, $xEditControl);
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <label class="col-md-2 control-label"></label>
                                                                                <div class="col-md-8" style="margin-left: 60px;margin-top: -12px;margin-bottom: 5px;">
                                                                                    <label>&nbsp;&nbsp;<?php echo getLocaleText('NEW_ORG_MSG_62', TXT_A); ?> : </label>
                                                                                    <a href="form-x-editable.html#" id="<?php echo $xeditId; ?>" data-original-title="<?php echo getLocaleText('NEW_ORG_MSG_63', TXT_A); ?>" data-placement="right" ></a>
                                                                                </div>
                                                                            </div>
                                                                    <?php } ?>
                                                                    
                                                            <?php } 
                                                            $prd_consCBIds = substr($prd_consCBIds, 0, strlen($prd_consCBIds)-2);
                                                            $prd_consBtIds = substr($prd_consBtIds, 0, strlen($prd_consBtIds)-2);
                                                            ?>
                                                            
                                                            <!--<div class="form-group" style="margin-bottom: 0px;">
                                                                <label class="col-md-2 control-label">Select the fields from the list applicable for this organization that will appear on <i><strong>New Product Creation</i></strong> screen </label>
                                                                <div class="col-md-6">
                                                                    <label class="col-md-12">
                                                                        <input type="checkbox" class="checkbox style-0" name="E1" id="E1" onclick="javascript:toggleDynaFieldsCheckbox('E1','BE1');">
                                                                        <span class="col-md-12">
                                                                            <a href="javascript:toggleDynaFieldsButton('E1','BE1');" id="BE1" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="Control Type: Text Box" 
                                                                               data-content="This control is used to assign the prodcut name" style="margin-left: 15px;width: 300px;">Product Name<strong></strong></a>                                                                    
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"></label>
                                                                <div class="col-md-6">
                                                                    <label class="col-md-12">
                                                                        <input type="checkbox" class="checkbox style-0" name="E2" id="E2" onclick="javascript:toggleDynaFieldsCheckbox('E2','BE2');">
                                                                        <span class="col-md-12">
                                                                            <a href="javascript:toggleDynaFieldsButton('E2','BE2');" id="BE2" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="Control Type: Text Box" 
                                                                               data-content="This control is used to assign the prodcut name" style="margin-left: 15px;width: 300px;">Product Code<strong></strong></a>                                                                    
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"></label>
                                                                <div class="col-md-6">
                                                                    <label class="col-md-12">
                                                                        <input type="checkbox" class="checkbox style-0" name="E3" id="E3" onclick="javascript:toggleDynaFieldsCheckbox('E3','BE3');">
                                                                        <span class="col-md-12">
                                                                            <a href="javascript:toggleDynaFieldsButton('E3','BE3');" id="BE3" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="Control Type: Text Box" 
                                                                               data-content="This control is used to assign the prodcut name" style="margin-left: 15px;width: 300px;">Product Description<strong></strong></a>                                                                    
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div> -->

                                                        </fieldset>
                                                        
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <button type="button" class="btn btn-default" id="btnReset-prd">
                                                                        <i class="fa fa-refresh"></i> <?php echo getLocaleText('NEW_ORG_MSG_BTN_4', TXT_A); ?>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    
                                                    <div class="step-pane" id="step4" data-step="4">

                                                        <h3><strong><?php echo getLocaleText('NEW_ORG_MSG_27', TXT_A); ?></strong></h3>
                                                        <p><?php echo getLocaleText('NEW_ORG_MSG_28', TXT_A); ?></p>

                                                        <fieldset>

                                                            <legend></legend>
                                                            <br/>
                                                            
                                                            <div class="alert alert-block alert-form-wizard-error" id="divDynaTsks" style="display: none">
                                                                <?php echo getLocaleText('NEW_ORG_MSG_29', TXT_A); ?>
                                                            </div>
                                                            
                                                            <?php
                                                                $tmpFlag = TRUE;
                                                                foreach($tasksDynaFields as $tsksDynaField){ 
                                                                    $cbName = $tsksDynaField->getIdDynaFields();
                                                                    $btName = 'bt_'.$tsksDynaField->getIdDynaFields();
                                                                    
                                                                    $childList = $tsksDynaField->getHtmlListValues();
                                                                    
                                                                    $tsks_consCBIds = $tsks_consCBIds . 'input[id=\'' . $cbName . '\'], ';
                                                                    $tsks_consBtIds = $tsks_consBtIds . 'a[id=\'' . $btName . '\'], ';
                                                                ?>
                                                                    <?php if ($tmpFlag){ ?>
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <label class="col-md-2 control-label"><?php echo getLocaleText('NEW_ORG_MSG_30', TXT_A); ?> </label>
                                                                    <?php $tmpFlag = FALSE; } else { ?> 
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label"></label>
                                                                    <?php } ?>
                                                                        <div class="col-md-6">
                                                                            <label class="col-md-12">
                                                                                <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $tsksDynaField->getIdDynaFields(); ?>" onclick="javascript:toggleDynaFieldsCheckbox('<?php echo $cbName; ?>','<?php echo $btName; ?>');">
                                                                                <span class="col-md-12">
                                                                                    <a href="javascript:toggleDynaFieldsButton('<?php echo $cbName; ?>','<?php echo $btName; ?>');" id="<?php echo $btName; ?>" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="<?php echo $tsksDynaField->getHtmlType(); ?>" 
                                                                                       data-content="<?php echo $tsksDynaField->getDescription(); ?>" style="margin-left: 15px;width: 300px;"><?php echo $tsksDynaField->getName(); ?><strong></strong></a>                                                                    
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
                                                                                                'xsource' => $xeditSrc
                                                                                            );
                                                                            array_push($xEditControls, $xEditControl);
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <label class="col-md-2 control-label"></label>
                                                                                <div class="col-md-8" style="margin-left: 60px;margin-top: -12px;margin-bottom: 5px;">
                                                                                    <label>&nbsp;&nbsp;<?php echo getLocaleText('NEW_ORG_MSG_62', TXT_A); ?> : </label>
                                                                                    <a href="form-x-editable.html#" id="<?php echo $xeditId; ?>" data-original-title="<?php echo getLocaleText('NEW_ORG_MSG_63', TXT_A); ?>" data-placement="right" ></a>
                                                                                </div>
                                                                            </div>
                                                                    <?php } ?>
                                                            <?php } 
                                                            $tsks_consCBIds = substr($tsks_consCBIds, 0, strlen($tsks_consCBIds)-2);
                                                            $tsks_consBtIds = substr($tsks_consBtIds, 0, strlen($tsks_consBtIds)-2);
                                                            ?>

                                                        </fieldset>
                                                        
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <button type="button" class="btn btn-default" id="btnReset-tsk">
                                                                        <i class="fa fa-refresh"></i> <?php echo getLocaleText('NEW_ORG_MSG_BTN_4', TXT_A); ?>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    
                                                    <div class="step-pane" id="step5" data-step="5">

                                                        <h3><strong><?php echo getLocaleText('NEW_ORG_MSG_31', TXT_A); ?></strong></h3>
                                                        <p><?php echo getLocaleText('NEW_ORG_MSG_32', TXT_A); ?></p>

                                                        <fieldset>

                                                            <legend></legend>
                                                            <br/>
                                                            
                                                            <div class="alert alert-block alert-form-wizard-error" id="divDynaWrks" style="display: none">
                                                                <?php echo getLocaleText('NEW_ORG_MSG_33', TXT_A); ?>
                                                            </div>
                                                            
                                                            <?php
                                                                $tmpFlag = TRUE;
                                                                foreach($workerDynaFields as $wrksDynaField){ 
                                                                    $cbName = $wrksDynaField->getIdDynaFields();
                                                                    $btName = 'bt_'.$wrksDynaField->getIdDynaFields();
                                                                    
                                                                    $childList = $wrksDynaField->getHtmlListValues();
                                                                    
                                                                    $wrks_consCBIds = $wrks_consCBIds . 'input[id=\'' . $cbName . '\'], ';
                                                                    $wrks_consBtIds = $wrks_consBtIds . 'a[id=\'' . $btName . '\'], ';
                                                                ?>
                                                                    <?php if ($tmpFlag){ ?>
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <label class="col-md-2 control-label"><?php echo getLocaleText('NEW_ORG_MSG_45', TXT_A); ?> </label>
                                                                    <?php $tmpFlag = FALSE; } else { ?> 
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label"></label>
                                                                    <?php } ?>
                                                                        <div class="col-md-6">
                                                                            <label class="col-md-12">
                                                                                <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $wrksDynaField->getIdDynaFields(); ?>" onclick="javascript:toggleDynaFieldsCheckbox('<?php echo $cbName; ?>','<?php echo $btName; ?>');">
                                                                                <span class="col-md-12">
                                                                                    <a href="javascript:toggleDynaFieldsButton('<?php echo $cbName; ?>','<?php echo $btName; ?>');" id="<?php echo $btName; ?>" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="<?php echo $wrksDynaField->getHtmlType(); ?>" 
                                                                                       data-content="<?php echo $wrksDynaField->getDescription(); ?>" style="margin-left: 15px;width: 300px;"><?php echo $wrksDynaField->getName(); ?><strong></strong></a>                                                                    
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
                                                                                                'xsource' => $xeditSrc
                                                                                            );
                                                                            array_push($xEditControls, $xEditControl);
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <label class="col-md-2 control-label"></label>
                                                                                <div class="col-md-8" style="margin-left: 60px;margin-top: -12px;margin-bottom: 5px;">
                                                                                    <label>&nbsp;&nbsp;<?php echo getLocaleText('NEW_ORG_MSG_62', TXT_A); ?> : </label>
                                                                                    <a href="form-x-editable.html#" id="<?php echo $xeditId; ?>" data-original-title="<?php echo getLocaleText('NEW_ORG_MSG_63', TXT_A); ?>" data-placement="right" ></a>
                                                                                </div>
                                                                            </div>
                                                                    <?php } ?>
                                                            <?php }
                                                            $wrks_consCBIds = substr($wrks_consCBIds, 0, strlen($wrks_consCBIds)-2);
                                                            $wrks_consBtIds = substr($wrks_consBtIds, 0, strlen($wrks_consBtIds)-2);
                                                            ?>
                                                            
                                                            <!--<div class="form-group" style="margin-bottom: 0px;">
                                                                <label class="col-md-2 control-label">Select the fields from the list applicable for this organization that will appear on <i><strong>New Worker Creation</i></strong> screen </label>
                                                                <div class="col-md-6">
                                                                    <label class="col-md-12">
                                                                        <input type="checkbox" class="checkbox style-0" name="CC1" id="CC1" onclick="javascript:toggleDynaFieldsCheckbox('CC1','BB1');">
                                                                        <span class="col-md-12">
                                                                            <a href="javascript:toggleDynaFieldsButton('CC1','BB1');" id="BB1" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="Control Type: Text Box" 
                                                                               data-content="This control is used to assign the worker name" style="margin-left: 15px;width: 300px;">Worker Name<strong></strong></a>                                                                    
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"></label>
                                                                <div class="col-md-6">
                                                                    <label class="col-md-12">
                                                                        <input type="checkbox" class="checkbox style-0" name="CC4" id="CC4" onclick="javascript:toggleDynaFieldsCheckbox('CC4','BB4');">
                                                                        <span class="col-md-12">
                                                                            <a href="javascript:toggleDynaFieldsButton('CC4','BB4');" id="BB4" class="btn btn-default" id="B1" rel="popover-hover" data-placement="right" data-original-title="Control Type: Text Box" 
                                                                               data-content="This control is used to assign the task name" style="margin-left: 15px;width: 300px;" >Worker Type<strong></strong></a>                                                                    
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"></label>
                                                                <div class="col-md-8" style="margin-left: 55px;margin-top: -12px;margin-bottom: 5px;">
                                                                    <label>&nbsp;&nbsp;Choose options : </label>
                                                                    <a href="form-x-editable.html#" id="workertypes" data-type="checklist" data-value="" data-original-title="Select Worker type" data-placement="right" ></a>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"></label>
                                                                <div class="col-md-8">
                                                                    <label class="col-md-12">
                                                                        <input type="checkbox" class="checkbox style-0" name="CC2" id="CC2" onclick="javascript:toggleDynaFieldsCheckbox('CC2','BB2');">
                                                                        <span class="col-md-12">
                                                                            <a href="javascript:toggleDynaFieldsButton('CC2','BC2');" id="BB2" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="Control Type: Text Box" 
                                                                               data-content="This control is used to assign the task name" style="margin-left: 15px;width: 300px;">Worker Phone<strong></strong></a>
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label"></label>
                                                                <div class="col-md-6">
                                                                    <label class="col-md-12">
                                                                        <input type="checkbox" class="checkbox style-0" name="CC3" id="CC3" onclick="javascript:toggleDynaFieldsCheckbox('CC3','BB3');">
                                                                        <span class="col-md-12">
                                                                            <a href="javascript:toggleDynaFieldsButton('CC3','BB3');" id="BB3" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="Control Type: Text Box" 
                                                                               data-content="This control is used to assign the task name" style="margin-left: 15px;width: 300px;">Worker Email<strong></strong></a>                                                                    
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>-->

                                                        </fieldset>
                                                        
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <button type="button" class="btn btn-default" id="btnReset-wrk">
                                                                        <i class="fa fa-refresh"></i> <?php echo getLocaleText('NEW_ORG_MSG_BTN_4', TXT_A); ?>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        </fieldset>

                                                    </div>
                                                    
                                                    <div class="step-pane" id="step6" data-step="6">

                                                        <h3><strong><?php echo getLocaleText('NEW_ORG_MSG_34', TXT_A); ?></strong></h3>
                                                        <p><?php echo getLocaleText('NEW_ORG_MSG_35', TXT_A); ?></p>

                                                        <fieldset>

                                                            <legend></legend>
                                                            <br/>
                                                            
                                                            <div class="alert alert-block alert-form-wizard-error" id="divDynaCstmrs" style="display: none">
                                                                <?php echo getLocaleText('NEW_ORG_MSG_36', TXT_A); ?>
                                                            </div>
                                                            
                                                            <?php
                                                                $tmpFlag = TRUE;
                                                                foreach($customerDynaFields as $cstmrDynaField){ 
                                                                    $cbName = $cstmrDynaField->getIdDynaFields();
                                                                    $btName = 'bt_'.$cstmrDynaField->getIdDynaFields();
                                                                    
                                                                    $childList = $cstmrDynaField->getHtmlListValues();
                                                                    
                                                                    $cstmrs_consCBIds = $cstmrs_consCBIds . 'input[id=\'' . $cbName . '\'], ';
                                                                    $cstmrs_consBtIds = $cstmrs_consBtIds . 'a[id=\'' . $btName . '\'], ';
                                                                ?>
                                                                    <?php if ($tmpFlag){ ?>
                                                                    <div class="form-group" style="margin-bottom: 0px;">
                                                                        <label class="col-md-2 control-label"><?php echo getLocaleText('NEW_ORG_MSG_46', TXT_A); ?> </label>
                                                                    <?php $tmpFlag = FALSE; } else { ?> 
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 control-label"></label>
                                                                    <?php } ?>
                                                                        <div class="col-md-6">
                                                                            <label class="col-md-12">
                                                                                <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $cstmrDynaField->getIdDynaFields(); ?>" onclick="javascript:toggleDynaFieldsCheckbox('<?php echo $cbName; ?>','<?php echo $btName; ?>');">
                                                                                <span class="col-md-12">
                                                                                    <a href="javascript:toggleDynaFieldsButton('<?php echo $cbName; ?>','<?php echo $btName; ?>');" id="<?php echo $btName; ?>" class="btn btn-default" rel="popover-hover" data-placement="right" data-original-title="<?php echo $cstmrDynaField->getHtmlType(); ?>" 
                                                                                       data-content="<?php echo $cstmrDynaField->getDescription(); ?>" style="margin-left: 15px;width: 300px;"><?php echo $cstmrDynaField->getName(); ?><strong></strong></a>                                                                    
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
                                                                                                'xsource' => $xeditSrc
                                                                                            );
                                                                            array_push($xEditControls, $xEditControl);
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <label class="col-md-2 control-label"></label>
                                                                                <div class="col-md-8" style="margin-left: 60px;margin-top: -12px;margin-bottom: 5px;">
                                                                                    <label>&nbsp;&nbsp;<?php echo getLocaleText('NEW_ORG_MSG_62', TXT_A); ?> : </label>
                                                                                    <a href="form-x-editable.html#" id="<?php echo $xeditId; ?>" data-original-title="<?php echo getLocaleText('NEW_ORG_MSG_63', TXT_A); ?>" data-placement="right" ></a>
                                                                                </div>
                                                                            </div>
                                                                    <?php } ?>
                                                                    
                                                            <?php } 
                                                            $cstmrs_consCBIds = substr($cstmrs_consCBIds, 0, strlen($cstmrs_consCBIds)-2);
                                                            $cstmrs_consBtIds = substr($cstmrs_consBtIds, 0, strlen($cstmrs_consBtIds)-2);
                                                            ?>

                                                        </fieldset>
                                                        
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <button type="button" class="btn btn-default" id="btnReset-cst">
                                                                        <i class="fa fa-refresh"></i> <?php echo getLocaleText('NEW_ORG_MSG_BTN_4', TXT_A); ?>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    
                                                    <div class="step-pane" id="step7" data-step="7">

                                                        <h3><strong><?php echo getLocaleText('NEW_ORG_MSG_47', TXT_A); ?></strong></h3>
                                                        <p><?php echo getLocaleText('NEW_ORG_MSG_48', TXT_A); ?></p>

                                                        <fieldset>

                                                            <legend></legend>
                                                            <br/>
                                                            
                                                            <div class="col-md-6">
                                                                <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                                    <header>
                                                                        <h2><?php echo getLocaleText('NEW_ORG_MSG_49', TXT_A); ?> </h2>
                                                                    </header>
                                                                    <div>

                                                                        <div class="jarviswidget-editbox">
                                                                        </div>

                                                                        <div class="widget-body">
                                                                            <div class="form-group">
                                                                                <label class="col-md-4 control-label"><strong><?php echo getLocaleText('NEW_ORG_MSG_14', TXT_A); ?> : </strong></label>
                                                                                <label class="col-md-8 control-label" style="text-align: left"><span id="sum_orgname"></span></label>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="col-md-4 control-label"><strong><?php echo getLocaleText('NEW_ORG_MSG_15', TXT_A); ?> : </strong></label>
                                                                                <label class="col-md-8 control-label" style="text-align: left"><span id="sum_phone"></span></label>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="col-md-4 control-label"><strong><?php echo getLocaleText('NEW_ORG_MSG_16', TXT_A); ?> : </strong></label>
                                                                                <label class="col-md-8 control-label" style="text-align: left"><span id="sum_email"></span></label>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="col-md-4 control-label"><strong><?php echo getLocaleText('NEW_ORG_MSG_17', TXT_A); ?> : </strong></label>
                                                                                <label class="col-md-8 control-label" style="text-align: left"><span id="sum_uname"></span></label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                                    <header>
                                                                        <h2><?php echo getLocaleText('NEW_ORG_MSG_50', TXT_A); ?> </h2>
                                                                    </header>
                                                                    <div>

                                                                        <div class="jarviswidget-editbox">
                                                                        </div>

                                                                        <div class="widget-body" style="min-height: 174px;">
                                                                            <div class="form-group">
                                                                                <label class="col-md-4 control-label"><strong><?php echo getLocaleText('NEW_ORG_MSG_21', TXT_A); ?> </strong></label>
                                                                                <div class="col-md-5">
                                                                                    <div class="input-group input-group-md" id="imagepreview">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-3">
                                                                <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                                    <header>
                                                                        <h2><?php echo getLocaleText('NEW_ORG_MSG_51', TXT_A); ?> </h2>
                                                                    </header>
                                                                    <div>

                                                                        <div class="jarviswidget-editbox">
                                                                        </div>

                                                                        <div class="widget-body">
                                                                            <div class="form-group">
                                                                                <label class="col-md-12"><?php echo getLocaleText('NEW_ORG_MSG_52', TXT_A); ?> </label>
                                                                                <br/><br/>
                                                                                <label class="col-md-12" style="margin-left: 20px;">
                                                                                    <div id="sumPrds">
                                                                                        <ol id="ol-sumPrds" class="list-unstyled" style="font-size: 110%!important;">
                                                                                            <!--<li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Product Name</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Product Code</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Product Description</li>-->
                                                                                        </ol>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-3">
                                                                <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                                    <header>
                                                                        <h2><?php echo getLocaleText('NEW_ORG_MSG_53', TXT_A); ?> </h2>
                                                                    </header>
                                                                    <div>

                                                                        <div class="jarviswidget-editbox">
                                                                        </div>

                                                                        <div class="widget-body">
                                                                            <div class="form-group">
                                                                                <label class="col-md-12"><?php echo getLocaleText('NEW_ORG_MSG_52', TXT_A); ?> </label>
                                                                                <br/><br/>
                                                                                <label class="col-md-12" style="margin-left: 20px;">
                                                                                    <div id="sumTsks">
                                                                                        <ol id="ol-sumTsks" class="list-unstyled" style="font-size: 110%!important;">
                                                                                            <!--<li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Task Name</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Task Type

                                                                                            </li>
                                                                                            <div class="exp-col1">
                                                                                                <ol style="list-style-type:none;font-size: 90%!important;margin-bottom: 10px;margin-left: -10px;" >
                                                                                                    <li>
                                                                                                        <span style="border-bottom: dashed 1px #08c;"><i class="fa fa-lg fa-plus-circle" style="color: #739e73"></i> Options Chosen</span>
                                                                                                        <ol style="list-style-type:none;margin-left: -10px;">
                                                                                                            <li style="display: none">
                                                                                                                &ndash; Scheduled
                                                                                                            </li>
                                                                                                            <li style="display: none">
                                                                                                                &ndash; Incidence
                                                                                                            </li>
                                                                                                        </ol>
                                                                                                    </li>
                                                                                                </ol>
                                                                                            </div>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Task Description</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Assigned Worker Name</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Is Dependent on other task</li>-->
                                                                                        </ol>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-3">
                                                                <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                                    <header>
                                                                        <h2><?php echo getLocaleText('NEW_ORG_MSG_54', TXT_A); ?> </h2>
                                                                    </header>
                                                                    <div>

                                                                        <div class="jarviswidget-editbox">
                                                                        </div>

                                                                        <div class="widget-body">
                                                                            <div class="form-group">
                                                                                <label class="col-md-12"><?php echo getLocaleText('NEW_ORG_MSG_52', TXT_A); ?> </label>
                                                                                <br/><br/>
                                                                                <label class="col-md-12" style="margin-left: 20px;">
                                                                                    <div id="sumWrks">
                                                                                        <ol id="ol-sumWrks" class="list-unstyled" style="font-size: 110%!important;">
                                                                                            <!--<li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Worker Name</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Worker Phone</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Worker Email</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Worker Type

                                                                                            </li>
                                                                                            <div class="exp-col1">
                                                                                                <ol style="list-style-type:none;font-size: 90%!important;margin-bottom: 10px;margin-left: -10px;" >
                                                                                                    <li>
                                                                                                        <span style="border-bottom: dashed 1px #08c;"><i class="fa fa-lg fa-plus-circle" style="color: #739e73"></i> Options Chosen</span>
                                                                                                        <ol style="list-style-type:none;margin-left: -10px;">
                                                                                                            <li style="display: none">
                                                                                                                &ndash; Plumber
                                                                                                            </li>
                                                                                                            <li style="display: none">
                                                                                                                &ndash; Mechanic
                                                                                                            </li>
                                                                                                            <li style="display: none">
                                                                                                                &ndash; Engineer
                                                                                                            </li>
                                                                                                            <li style="display: none">
                                                                                                                &ndash; Driver
                                                                                                            </li>
                                                                                                        </ol>
                                                                                                    </li>
                                                                                                </ol>
                                                                                            </div>-->
                                                                                        </ol>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-3">
                                                                <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                                    <header>
                                                                        <h2><?php echo getLocaleText('NEW_ORG_MSG_55', TXT_A); ?> </h2>
                                                                    </header>
                                                                    <div>

                                                                        <div class="jarviswidget-editbox">
                                                                        </div>

                                                                        <div class="widget-body">
                                                                            <div class="form-group">
                                                                                <label class="col-md-12"><?php echo getLocaleText('NEW_ORG_MSG_52', TXT_A); ?> </label>
                                                                                <br/><br/>
                                                                                <label class="col-md-12" style="margin-left: 20px;">
                                                                                    <div id="sumCstmrs">
                                                                                        <ol id="ol-sumCstmrs" class="list-unstyled" style="font-size: 110%!important;">
                                                                                            <!--<li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Customer Name</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Address</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Phone</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Email</li>
                                                                                            <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> Customer Code</li> -->
                                                                                      </ol>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </fieldset>

                                                    </div>

                                                </div>

                                            </div>

                                            <!--<div class="form-actions">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-default" id="btnReset">
                                                            <i class="fa fa-refresh"></i> Reset
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>-->

                                        </form>

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
                
        <?php }
        ?>
        
        <?php
        
        if (null != $orgDao->getMsgType() && ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_SUCCESS || $orgDao->getMsgType() == SUBMISSION_MSG_TYPE_COMPLETESUCCESS)){ ?>
        
            <!-- Displaying success results -->
            <div class="row">
                <br/>
                <div class="col-md-12">

                    <div class="well">
                        <div class="row">
                            
                            <div class="col-md-6 col-md-offset-3">
                                <div class="well" style="background-color: #f7f7f7;">
                                    <div class="row">
                            
                                        <div class="col-sm-12 ">
                                            <br/>
                                            <div class="alert alert-info alert-block">
                                                <span class="label label-primary" style="float: right;font-size: 12px"><?php echo getLocaleText('NEW_ORG_MSG_56', TXT_A); ?></span>
                                                <h4 class="alert-heading"><?php echo getLocaleText('NEW_ORG_MSG_57', TXT_A); ?></h4> 
                                                <?php echo getLocaleText('NEW_ORG_MSG_58', TXT_A); ?>
                                            </div>
                                            <h3 class="text-primary" style="font-size: 22px"><?php echo getLocaleText('NEW_ORG_MSG_59', TXT_A); ?></h3>

                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width:50%"></th>
                                                        <th style="width:50%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align: center">
                                                    <tr class="warning">
                                                        <td><?php echo getLocaleText('NEW_ORG_MSG_14', TXT_A); ?></td>
                                                        <td><strong><?php echo $_POST['orgname']; ?></strong></td>
                                                    </tr>
                                                    <tr class="warning">
                                                        <td><?php echo getLocaleText('NEW_ORG_MSG_17', TXT_A); ?></td>
                                                        <td><strong><?php echo $_POST['uname']; ?></strong></td>
                                                    </tr>
                                                    <tr class="warning">
                                                        <td><?php echo getLocaleText('NEW_ORG_MSG_18', TXT_A); ?></td>
                                                        <td><strong><?php echo $_POST['pwd']; ?></strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <br/>
                                        </div>
                            
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <br/><br/>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary btn-lg" id="btnSubmit" style="font-weight: 400;" onclick="javascript:location.href='newOrg.php'">
                                <i class="fa fa-plus-circle fa-fw"></i> <?php echo getLocaleText('NEW_ORG_MSG_BTN_5', TXT_A); ?>
                            </button>
                        </div>
                        <br/><br/>
                    </div>

                </div>
            </div>
            <!-- End Displaying success results -->
        
        <?php }
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
                        required: "<?php echo getLocaleText('NEW_ORG_MSG_VALID_1', TXT_A); ?>"
                    },
                    email: {
                        required: "<?php echo getLocaleText('NEW_ORG_MSG_VALID_2', TXT_A); ?>",
                        email: "<?php echo getLocaleText('NEW_ORG_MSG_VALID_3', TXT_A); ?>"
                    },
                    uname: {
                        required: "<?php echo getLocaleText('NEW_ORG_MSG_VALID_4', TXT_A); ?>",
                        minlength: "<?php echo getLocaleText('NEW_ORG_MSG_VALID_5', TXT_A); ?>",
                        maxlength: "<?php echo getLocaleText('NEW_ORG_MSG_VALID_6', TXT_A); ?>"
                    },
                    pwd: {
                        required: "<?php echo getLocaleText('NEW_ORG_MSG_VALID_7', TXT_A); ?>",
                        minlength: "<?php echo getLocaleText('NEW_ORG_MSG_VALID_8', TXT_A); ?>",
                        maxlength: "<?php echo getLocaleText('NEW_ORG_MSG_VALID_9', TXT_A); ?>"
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
            
        /*jQuery.validator.addMethod('dynaFieldsValidate1', function(value, element) {
            var fields = $("input.E1, input.E2, input.E3").serializeArray();
                return fields.length > 0;
          });*/
          
        
        /*
         * Wizard functions
         */
        var wizard = $("#orgDetailsWiz").wizard();
        wizard.on('actionclicked.fu.wizard', function (e, data) {
            var $valid = $("#orgDetailsForm").valid();
            //alert($valid);
            if (!$valid) {
                $validator.focusInvalid();
                e.preventDefault();
                return false;
            }
            
            var currentStep = $('#orgDetailsWiz').wizard('selectedItem');
            if (currentStep.step == 3){
                if (!$().dynaFieldsValidate_Prd()){
                    document.getElementById('divDynaPrd').style.display = 'block';
                    e.preventDefault();
                    return false;
                }
            }
            if (currentStep.step == 4){
                if (!$().dynaFieldsValidate_Tsks()){
                    document.getElementById('divDynaTsks').style.display = 'block';
                    e.preventDefault();
                    return false;
                }
            }
            if (currentStep.step == 5){
                if (!$().dynaFieldsValidate_Wrks()){
                    document.getElementById('divDynaWrks').style.display = 'block';
                    e.preventDefault();
                    return false;
                }
            }
            if (currentStep.step == 6){
                if (!$().dynaFieldsValidate_Cstmrs()){
                    document.getElementById('divDynaCstmrs').style.display = 'block';
                    e.preventDefault();
                    return false;
                }
                else{
                    displaySummary();
                }
            }
            
        });

        wizard.on('finished.fu.wizard', function (e, data) {
            $.smallBox({
                title: "<?php echo getLocaleText('NEW_ORG_MSG_60', TXT_A); ?>",
                content: "<i class='fa fa-clock-o'></i> <i><?php echo getLocaleText('NEW_ORG_MSG_61', TXT_A); ?></i>",
                color: "#5F895F",
                iconSmall: "fa fa-check bounce animated",
                timeout: 10000
            });
            document.getElementById('formsubmit').value = 'submit';
            $("#orgDetailsForm").submit();
        });
        
        $('#btnCancel').on('click', function () {
            var currentStep = $('#orgDetailsWiz').wizard('selectedItem');
            if (currentStep.step == 1){
                location.href = 'org.php';
            }
            else{
                document.getElementById('formsubmit').value = 'submit';
                document.getElementById('formcancel').value = 'true';
                $("#orgDetailsForm").submit();
            }
        });

         /*
         * Reset functions
         */
        $('#btnReset').on('click', function () {
            $validator.resetForm();
            document.getElementById('orgDetailsForm').reset();
        });
        
        $('#btnReset-prd').on('click', function () {
            $("<?php echo $prd_consCBIds; ?>").prop('checked',false);
            $("<?php echo $prd_consBtIds; ?>").css('background-color', '#ffffff');
            document.getElementById('divDynaPrd').style.display = 'none';
            
            <?php
            foreach($xEditControls as $xEditControl){
                $xeditId = $xEditControl['xeditId'];
                if (strpos($xeditId, DYNAFIELDS_PRODUCT_CBID_PREFIX)){ ?>
                    $('#<?php echo $xeditId; ?>')
                        .editable('setValue', null);
            <?php 
                }
            }
            ?>
        });

        $('#btnReset-tsk').on('click', function () {
            $("<?php echo $tsks_consCBIds; ?>").prop('checked',false);
            $("<?php echo $tsks_consBtIds; ?>").css('background-color', '#ffffff');
            document.getElementById('divDynaTsks').style.display = 'none';
            
            <?php
            foreach($xEditControls as $xEditControl){
                $xeditId = $xEditControl['xeditId'];
                if (strpos($xeditId, DYNAFIELDS_TASK_CBID_PREFIX)){ ?>
                    $('#<?php echo $xeditId; ?>')
                        .editable('setValue', null);
            <?php 
                }
            }
            ?>
        });
        
        $('#btnReset-wrk').on('click', function () {
            $("<?php echo $wrks_consCBIds; ?>").prop('checked',false);
            $("<?php echo $wrks_consBtIds; ?>").css('background-color', '#ffffff');
            document.getElementById('divDynaWrks').style.display = 'none';
            
            <?php
            foreach($xEditControls as $xEditControl){
                $xeditId = $xEditControl['xeditId'];
                if (strpos($xeditId, DYNAFIELDS_WORKER_CBID_PREFIX)){ ?>
                    $('#<?php echo $xeditId; ?>')
                        .editable('setValue', null);
            <?php 
                }
            }
            ?>
        });
        
        $('#btnReset-cst').on('click', function () {
            $("<?php echo $cstmrs_consCBIds; ?>").prop('checked',false);
            $("<?php echo $cstmrs_consBtIds; ?>").css('background-color', '#ffffff');
            document.getElementById('divDynaCstmrs').style.display = 'none';
            
            <?php
            foreach($xEditControls as $xEditControl){
                $xeditId = $xEditControl['xeditId'];
                if (strpos($xeditId, DYNAFIELDS_CUSTOMER_CBID_PREFIX)){ ?>
                    $('#<?php echo $xeditId; ?>')
                        .editable('setValue', null);
            <?php 
                }
            }
            ?>
        });

        /*$('#btnNext').on('click', function (e, data) {
            //alert('sdf');
            var $valid = $("#orgDetailsForm").valid();
            alert($valid);
            if (!$valid) {
                $validator.focusInvalid();
                //e.preventDefault();
                //$('#orgDetailsWiz').wizard('previous');
                //$('#orgDetailsWiz').wizard('selectedItem', {
                    //step: 1
                //});
                return false;
            }
            else{
                //$('#newOrgWiz').wizard('next');
            }
        });*/

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
            dictMaxFilesExceeded: '<?php echo getLocaleText("EDIT_ORG_MSG_43", TXT_A); ?>',
            success: function(file) {
                document.getElementById('imagepreview').innerHTML = '<img src="../tempuploads/thumbs/thumb.jpg">;';
            },
            init: function() {
                this.on("removedfile", function(file) { document.getElementById('imagepreview').innerHTML = ''; });
            }
        });

        /*
        * Dyna Fields Functions
        */
        $.fn.dynaFieldsValidate_Prd = function(){

            var fields = $("<?php echo $prd_consCBIds; ?>").serializeArray();
            return fields.length > 0;
        }
        $.fn.dynaFieldsValidate_Tsks = function(){

            var fields = $("<?php echo $tsks_consCBIds; ?>").serializeArray();
            return fields.length > 0;
        }
        $.fn.dynaFieldsValidate_Wrks = function(){

            var fields = $("<?php echo $wrks_consCBIds; ?>").serializeArray();
            return fields.length > 0;
        }
        $.fn.dynaFieldsValidate_Cstmrs = function(){

            var fields = $("<?php echo $cstmrs_consCBIds; ?>").serializeArray();
            return fields.length > 0;
        }

        /*
         * Summary functions
         */

        /*$('.exp-col1 > ol').attr('role', 'exp-col1').find('ol').attr('role', 'group');
        $('.exp-col1').find('li:has(ol)').addClass('parent_li').attr('role', 'exp-col1-item').find(' > span').attr('title', 'Click to Collapse').on('click', function(e) {
                var children = $(this).parent('li.parent_li').find(' > ol > li');
                if (children.is(':visible')) {
                    children.hide('fast');
                    $(this).attr('title', 'Click to Expand').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
                    $(this).attr('style', 'border-bottom: dashed 1px #08c;');
                } else {
                    children.show('fast');
                    $(this).attr('title', 'Click to Collapse').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
                    $(this).attr('style', 'border-bottom: none');
                }
                e.stopPropagation();
        });*/			
        
    })
    
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
        ?>
            
        $('#<?php echo $xeditId; ?>').editable({
            url: 'xEdit.php',
            pk: 1,
            source: [
                <?php echo $xeditSrc; ?> 
            ],
            unsavedclass: null,
            type: 'checklist',
            emptytext: '<?php echo getLocaleText("NEW_ORG_MSG_64", TXT_A); ?>',
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
    <?php 
    }
    ?>
        
    function displaySummary(){

        document.getElementById('sum_orgname').innerHTML = $("#orgname").val();
        document.getElementById('sum_phone').innerHTML = $("#phone").val();
        document.getElementById('sum_email').innerHTML = $("#email").val();
        document.getElementById('sum_uname').innerHTML = $("#uname").val();
        
        var dynaComps = ['sumPrds', 'sumTsks', 'sumWrks', 'sumCstmrs'];
        
        
        for (var ctr=0; ctr<dynaComps.length; ctr++){
            var step = ctr + 3;
            $("#"+dynaComps[ctr]+ " li").remove();
            $("#"+dynaComps[ctr]+ " ol ol").remove();
            //$("#"+dynaComps[ctr]+ " ol #xlist").remove();
            $("#step"+step).find('a').each(function() {
                if ($(this).attr('type') == 1){
                    $("#"+dynaComps[ctr]+ " #ol-"+dynaComps[ctr]).append('<li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> '+$(this).text()+'</li>');
                }
                else if ($(this).attr('href') == 'form-x-editable.html#'){
                    $("#"+dynaComps[ctr]+ " ol #xlist-"+ $(this).attr('id')).remove();
                    var xEditText = $(this).text();
                    var arr = xEditText.split(/,/);
                    var listValues = dynaListValuesDiv_1;
                    for (var i=0; i<arr.length; i++){
                        listValues = listValues + '<li>&ndash; ' + arr[i].trim() + '</li>';
                    }
                    listValues = listValues + dynaListValuesDiv_2;
                    var idx = listValues.indexOf('Empty');
                    if (idx == -1){
                        $("#"+dynaComps[ctr]+ " #ol-"+dynaComps[ctr]).append('<div id=\'xlist-' + $(this).attr('id') + '\'></div>');
                        $("#"+dynaComps[ctr]+ " #ol-"+dynaComps[ctr]+" #xlist-" + $(this).attr('id')).append(listValues);
                    }
                    
                }
            });
        }

    }
    
    var dynaListValuesDiv_1 = "<ol style=\'list-style-type:none;font-size: 90%!important;margin-bottom: 10px;margin-left: -10px;\' ><li><span><i class=\'fa fa-lg fa-check-circle\' style=\'color: #739e73\'></i> Options Chosen</span><ol style=\'list-style-type:none;margin-left: -10px;\'>";
    var dynaListValuesDiv_2 = "</ol></li></ol>";
 
    



</script>

<?php

    function preInputsInspection(OrgInputs $orgInputs, OrgDao $orgDao){
        
        $orgInputs->setIn_name(mysql_real_escape_string($_POST['orgname']));
        $orgInputs->setIn_phone(mysql_real_escape_string($_POST['phone']));
        $orgInputs->setIn_email(mysql_real_escape_string($_POST['email']));
        $orgInputs->setIn_username(mysql_real_escape_string($_POST['uname']));
        $orgInputs->setIn_password(mysql_real_escape_string($_POST['pwd']));
        
        
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

        
    }

?>
