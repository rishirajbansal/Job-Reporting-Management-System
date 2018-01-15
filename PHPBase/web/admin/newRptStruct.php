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

include_once(dirname(__FILE__) . "/../../classes/inputs/UserOrgInputs.php");
include_once(dirname(__FILE__) . "/../../classes/dao/UserOrgDao.php");

include_once("sessionMgmt.php");

require_once(dirname(__FILE__) . "/../commonInc/init.php");
require_once(dirname(__FILE__) . "/inc/config.ui.php");


/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('NEW_RPT_STRUCT_MSG_1', TXT_A);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["reporting"]["active"] = true;
$page_nav["reporting"]["sub"]["rptStructconfig"]["active"] = true;
include("inc/nav.php");


/*------------- Form Submissions ---------*/

$orgInputs = new OrgInputs();
$orgDao = new OrgDao();
$productDynaFields = array();
$tasksDynaFields = array();
$workerDynaFields = array();
$customerDynaFields = array();
$reportingDynaFields = array();

$orgRptStruct =array();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$isDetailsFetched = FALSE;

$orgId = $_POST['orgid'];
$orgInputs->setIdorgs($orgId);

//Load ALl Dyna Fields
$loadFlag = $orgDao->loadDynaFields();
if ($loadFlag){
    $productDynaFields = $orgDao->getProductDynaFields();
    $tasksDynaFields = $orgDao->getTaskDynaFields();
    $workerDynaFields = $orgDao->getWorkerDynaFields();
    $customerDynaFields = $orgDao->getCustomerDynaFields();
    $reportingDynaFields = $orgDao->getReportingDynaFields();
    
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

//Load ORG's saved Dyna Fields
$fetchFlag = $orgDao->fetchOrg($orgInputs);
if ($fetchFlag){
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


if (isset($_POST['formsubmit']) && $_POST['formsubmit'] != 'rptStructsListForm'){
    
    preInputsInspection($orgInputs, $orgDao);
    
    $mode = $_POST['mode'];
    
    $daoFlag = $orgDao->saveOrgRptStructure($orgInputs, $mode);
    
    if ($daoFlag){
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
    
    $fetchFlag = $orgDao->fetchRptStruct($orgInputs, $mode);
            
    if ($fetchFlag){
        $orgRptStruct = $orgDao->getOrgRptStructsFields();
        $isDetailsFetched = TRUE;
    }
    
}
else if (!empty($loadFlag) && isset($_POST['formsubmit']) && $_POST['formsubmit'] == 'rptStructsListForm' && isset($_POST['mode'])){
    
    
    $mode = $_POST['mode'];
    
    $fetchFlag = $orgDao->fetchRptStruct($orgInputs, $mode);
            
    if ($fetchFlag){
        $orgRptStruct = $orgDao->getOrgRptStructsFields();
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
    

/*------------- End Form Submissions ---------*/

?>


<!-- MAIN PANEL -->
<div id="main" role="main">
    
    <?php
        $breadcrumbs[getLocaleText('NEW_RPT_STRUCT_MSG_2', TXT_A)] = "";
        $breadcrumbs[getLocaleText('NEW_RPT_STRUCT_MSG_3', TXT_A)] = "rptStructs.php";
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
           <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-cubes"></i>
                    <?php
                    if ($mode == ORG_RPT_STRUCT_LIST_MODE_NEW){ ?>
                        <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_4', TXT_A); ?>
                    <?php
                    }
                    else if ($mode == ORG_RPT_STRUCT_LIST_MODE_EDIT){ ?>
                        <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_5', TXT_A); ?>
                    <?php
                    }
                    ?>
                </h1>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <a href="rptStructs.php" class="btn bg-color-blueLight txt-color-white btn-lg pull-right header-btn hidden-mobile"><i class="fa fa-circle-arrow-up fa-lg"></i> <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_6', TXT_A); ?></a>
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
                                <h2>
                                    <?php
                                    if ($mode == ORG_RPT_STRUCT_LIST_MODE_NEW){ ?>
                                         <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_7', TXT_A); ?>
                                    <?php
                                    }
                                    else if ($mode == ORG_RPT_STRUCT_LIST_MODE_EDIT){ ?>
                                         <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_8', TXT_A); ?>
                                    <?php
                                    }
                                    ?>
                                        <strong style="color: #95c0d6"><i> - <?php echo $orgDao->getOrgDetails()->getName(); ?></i></strong>
                                </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body" style="padding-left: 20px;padding-right: 20px;">
                                    
                                    <form class="form" id="rptStructForm" name="rptStructForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                        <input type="hidden" name="orgid" id="orgid" value="<?php echo $orgInputs->getIdorgs(); ?>"/>
                                        <input type="hidden" name="mode" id="mode" value="<?php echo $_POST['mode'] ?>"/>
                                        
                                        <h3><strong><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_9', TXT_A); ?></strong></h3>
                                        <p><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_10', TXT_A); ?> </p>
                                        
                                        <fieldset style="min-height: 380px">
                                            
                                            <legend></legend>
                                            <br/>
                                            
                                            <div class="row" style="float:right;margin-top: -20px;margin-right: 0px;">
                                                <span class="badge bg-color-red"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_12', TXT_A); ?></span> <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_11', TXT_A); ?>
                                            </div>
                                            <br/>
                                            
                                            <div class="well well-sm" style="border-radius: 2px 2px 0 0;border-bottom: none;">
                                                <h6 class="text-primary" style="margin-top: 5px;margin-bottom: 5px;"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_13', TXT_A); ?></h6>
                                            </div>
                                            <div class="well" style="background-color: #fff!important;">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <?php
                                                        $orgDynaProduct = $orgDao->getOrgProductDynaFields();
                                                        $dynaFieldsProcessedDetails = $orgDynaProduct->getDynaFieldsProcessedDetails();
                                                        
                                                        $brCtr = 0;
                                                        foreach($productDynaFields as $prdDynaField){
                                                            $cbName = $prdDynaField->getIdDynaFields();
                                                            
                                                            $checked = '';
                                                            $configured = FALSE;
                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                if ($value['fieldId'] == $cbName){
                                                                    $configured = TRUE;
                                                                    if (array_key_exists($cbName, $orgRptStruct)){
                                                                        $checked = 'checked';
                                                                    }
                                                                    break;
                                                                }
                                                            }
                                                            if ($configured){
                                                                ++$brCtr;
                                                            ?>  
                                                                <label class="col-md-3" style="margin-left: -25px;">
                                                                    <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $prdDynaField->getIdDynaFields(); ?>" <?php echo $checked; ?> >
                                                                    <span class="col-md-12" rel="popover-hover" data-placement="top" data-original-title="<?php echo $prdDynaField->getHtmlType(); ?>" 
                                                                          data-content="<?php echo $prdDynaField->getDescription(); ?>"><?php echo $prdDynaField->getName(); ?>
                                                                        <?php
                                                                        if ($prdDynaField->getRecom() == 1){ ?>
                                                                            &nbsp;&nbsp;<sup><span class="badge bg-color-red"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_12', TXT_A); ?></span></sup>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </label>
                                                            <?php
                                                                if ($brCtr == 4){
                                                                    $brCtr = 0;
                                                                ?>
                                                                <br/><br/>
                                                                <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <br/>
                                            
                                            <div class="well well-sm" style="border-radius: 2px 2px 0 0;border-bottom: none;">
                                                <h6 class="text-primary" style="margin-top: 5px;margin-bottom: 5px;"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_14', TXT_A); ?></h6>
                                            </div>
                                            <div class="well" style="background-color: #fff!important;">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <?php
                                                        $orgDynaTask = $orgDao->getOrgTaskDynaFields();
                                                        $dynaFieldsProcessedDetails = $orgDynaTask->getDynaFieldsProcessedDetails();
                                                        
                                                        $brCtr = 0;
                                                        foreach($tasksDynaFields as $tsksDynaField){ 
                                                            $cbName = $tsksDynaField->getIdDynaFields();
                                                            
                                                            $checked = '';
                                                            $configured = FALSE;
                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                if ($value['fieldId'] == $cbName){
                                                                    $configured = TRUE;
                                                                    if (array_key_exists($cbName, $orgRptStruct)){
                                                                        $checked = 'checked';
                                                                    }
                                                                    break;
                                                                }
                                                            }
                                                            if ($configured){
                                                                ++$brCtr;
                                                            ?>  
                                                                <label class="col-md-3" style="margin-left: -25px;">
                                                                    <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $tsksDynaField->getIdDynaFields(); ?>" <?php echo $checked; ?> >
                                                                    <span class="col-md-12" rel="popover-hover" data-placement="top" data-original-title="<?php echo $tsksDynaField->getHtmlType(); ?>" 
                                                                            data-content="<?php echo $tsksDynaField->getDescription(); ?>"><?php echo $tsksDynaField->getName(); ?>
                                                                    <?php
                                                                        if ($tsksDynaField->getRecom() == 1){ ?>
                                                                            &nbsp;&nbsp;<sup><span class="badge bg-color-red"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_12', TXT_A); ?></span></sup>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </label>
                                                            <?php
                                                                if ($brCtr == 4){
                                                                    $brCtr = 0;
                                                                ?>
                                                                <br/><br/>
                                                                <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <br/>
                                            
                                            <div class="well well-sm" style="border-radius: 2px 2px 0 0;border-bottom: none;">
                                                <h6 class="text-primary" style="margin-top: 5px;margin-bottom: 5px;"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_15', TXT_A); ?></h6>
                                            </div>
                                            <div class="well" style="background-color: #fff!important;">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <?php
                                                        $orgDynaWorker = $orgDao->getOrgWorkerDynaField();
                                                        $dynaFieldsProcessedDetails = $orgDynaWorker->getDynaFieldsProcessedDetails();
                                                        
                                                        $brCtr = 0;
                                                        foreach($workerDynaFields as $wrksDynaField){ 
                                                            $cbName = $wrksDynaField->getIdDynaFields();
                                                            
                                                            $checked = '';
                                                            $configured = FALSE;
                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                if ($value['fieldId'] == $cbName){
                                                                    $configured = TRUE;
                                                                    if (array_key_exists($cbName, $orgRptStruct)){
                                                                        $checked = 'checked';
                                                                    }
                                                                    break;
                                                                }
                                                            }
                                                            if ($configured){
                                                                ++$brCtr;
                                                            ?>  
                                                                <label class="col-md-3" style="margin-left: -25px;">
                                                                    <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $wrksDynaField->getIdDynaFields(); ?>" <?php echo $checked; ?> >
                                                                    <span class="col-md-12" rel="popover-hover" data-placement="top" data-original-title="<?php echo $wrksDynaField->getHtmlType(); ?>" 
                                                                               data-content="<?php echo $wrksDynaField->getDescription(); ?>"><?php echo $wrksDynaField->getName(); ?>
                                                                    <?php
                                                                        if ($wrksDynaField->getRecom() == 1){ ?>
                                                                            &nbsp;&nbsp;<sup><span class="badge bg-color-red"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_12', TXT_A); ?></span></sup>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </label>
                                                            <?php
                                                                if ($brCtr == 4){
                                                                    $brCtr = 0;
                                                                ?>
                                                                <br/><br/>
                                                                <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <br/>
                                            
                                            <div class="well well-sm" style="border-radius: 2px 2px 0 0;border-bottom: none;">
                                                <h6 class="text-primary" style="margin-top: 5px;margin-bottom: 5px;"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_16', TXT_A); ?></h6>
                                            </div>
                                            <div class="well" style="background-color: #fff!important;">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <?php
                                                        $orgDynaCustomer = $orgDao->getOrgCustomerDynaFields();
                                                        $dynaFieldsProcessedDetails = $orgDynaCustomer->getDynaFieldsProcessedDetails();
                                                        
                                                        $brCtr = 0;
                                                        foreach($customerDynaFields as $cstmrDynaField){ 
                                                            $cbName = $cstmrDynaField->getIdDynaFields();
                                                            
                                                            $checked = '';
                                                            $configured = FALSE;
                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                if ($value['fieldId'] == $cbName){
                                                                    $configured = TRUE;
                                                                    if (array_key_exists($cbName, $orgRptStruct)){
                                                                        $checked = 'checked';
                                                                    }
                                                                    break;
                                                                }
                                                            }
                                                            if ($configured){
                                                                ++$brCtr;
                                                            ?>  
                                                                <label class="col-md-3" style="margin-left: -25px;">
                                                                    <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $cstmrDynaField->getIdDynaFields(); ?>" <?php echo $checked; ?> >
                                                                    <span class="col-md-12" rel="popover-hover" data-placement="top" data-original-title="<?php echo $cstmrDynaField->getHtmlType(); ?>" 
                                                                               data-content="<?php echo $cstmrDynaField->getDescription(); ?>"><?php echo $cstmrDynaField->getName(); ?>
                                                                    <?php
                                                                        if ($cstmrDynaField->getRecom() == 1){ ?>
                                                                            &nbsp;&nbsp;<sup><span class="badge bg-color-red"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_12', TXT_A); ?></span></sup>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </label>
                                                            <?php
                                                                if ($brCtr == 4){
                                                                    $brCtr = 0;
                                                                ?>
                                                                <br/><br/>
                                                                <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <br/>
                                            
                                            <div class="well well-sm" style="border-radius: 2px 2px 0 0;border-bottom: none;">
                                                <h6 class="text-primary" style="margin-top: 5px;margin-bottom: 5px;"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_17', TXT_A); ?></h6>
                                            </div>
                                            <div class="well" style="background-color: #fff!important;">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <?php
                                                        $brCtr = 0;
                                                        foreach($reportingDynaFields as $reportingDynaField){ 
                                                            $cbName = $reportingDynaField->getIdDynaFields();
                                                            
                                                            $checked = '';
                                                            $configured = TRUE;
                                                            if ($configured){
                                                                if (array_key_exists($cbName, $orgRptStruct)){
                                                                    $checked = 'checked';
                                                                }
                                                                ++$brCtr;
                                                            ?>  
                                                                <label class="col-md-3" style="margin-left: -25px;">
                                                                    <input type="checkbox" class="checkbox style-0" name="<?php echo $cbName; ?>" id="<?php echo $cbName; ?>" value="<?php echo $reportingDynaField->getIdDynaFields(); ?>" <?php echo $checked; ?> >
                                                                    <span class="col-md-12" rel="popover-hover" data-placement="top" data-original-title="<?php echo $reportingDynaField->getHtmlType(); ?>" 
                                                                               data-content="<?php echo $reportingDynaField->getDescription(); ?>"><?php echo $reportingDynaField->getName(); ?>
                                                                    <?php
                                                                        if ($reportingDynaField->getRecom() == 1){ ?>
                                                                            &nbsp;&nbsp;<sup><span class="badge bg-color-red"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_12', TXT_A); ?></span></sup>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </label>
                                                            <?php
                                                                if ($brCtr == 4){
                                                                    $brCtr = 0;
                                                                ?>
                                                                <br/><br/>
                                                                <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <br/><br/>

                                            <div class="form-actions" style="border: none;background: none;border-top: 1px solid rgba(0,0,0,.1);">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-default bg-color-blueLight txt-color-white" id="btnCancel">
                                                            <i class="glyphicon glyphicon-remove"></i> <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_BTN_1', TXT_A); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-default" id="btnClear">
                                                            <i class="fa fa-recycle"></i> <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_BTN_2', TXT_A); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-default" id="btnReset">
                                                            <i class="fa fa-refresh"></i> <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_BTN_3', TXT_A); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-default bg-color-teal txt-color-white" id="btnPreview" data-toggle="modal" data-target="#structModal" rel="popover-hover" data-placement="top" data-content="<?php echo getLocaleText('NEW_RPT_STRUCT_MSG_23', TXT_A); ?>">
                                                            <i class="fa fa-eye"></i> <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_BTN_4', TXT_A); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-primary" id="btnSave">
                                                            <i class="fa fa-save"></i> <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_BTN_5', TXT_A); ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <br/><br/>
                                            
                                        </fieldset>
                                        
                                    </form>

                                </div>

                            </div>

                        </div>

                    </article>

                </div>

            </section>
        
            <div class="modal fade" id="structModal" tabindex="-1" role="dialog" aria-labelledby="structModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="width: 550px;">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #e7e7c6;border-radius: 4px 4px 0px 0px;">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                </button>
                                <h3 class="modal-title" id="structModalLabel" style="color: #3276b1;font-weight: 400"><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_18', TXT_A); ?></h3>
                               <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_19', TXT_A); ?>
                        </div>
                        <div class="modal-body">
                            <div class="row" style="padding-left: 30px;padding-right: 30px;">
                                <form class="form-horizontal" id="rptForm" name="rptForm" method="post" action="">

                                    <h3 style="margin-top: 0px;"><strong><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_20', TXT_A); ?></strong></h3>
                                    <p><?php echo getLocaleText('NEW_RPT_STRUCT_MSG_21', TXT_A); ?>  </p>

                                    <?php include("rptStructPreviewDynaComps.php"); ?>

                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        
        <?php
        }
        else if (empty ($orgDao->getMsgType())) { ?>
            <div class="alert alert-danger fade in">
                <i class="fa-fw fa fa-times"></i>
                <?php echo getLocaleText('NEW_RPT_STRUCT_MSG_22', TXT_A); ?>
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

        var $validator = $("#rptStructForm").validate({

                rules: {
                    
                },

                messages: {
                    
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
            
        
        $('#btnCancel').on('click', function () {
            location.href = 'rptStructs.php';
        });
        
        $('#btnSave').on('click', function () {
            var $valid = $("#rptStructForm").valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            else{
                document.forms['rptStructForm']['formsubmit'].value = 'formsubmit';
                $("#rptStructForm").submit();
            }
        });
        
        $('#btnReset').on('click', function () {
            $validator.resetForm();
            document.getElementById('rptStructForm').reset();
        });

        $('#btnClear').on('click', function () {
            $("input[type=checkbox]").each(function() {
                $(this).prop('checked',false);
            });
        });

    });
    
    function jtime(timeId){
        
        $('#'+timeId).timepicker({
            minuteStep: 1,
            showSeconds: true,
            secondStep: 1
            });
        
    }

</script>

<?php

    function preInputsInspection(orgInputs $orgInputs, OrgDao $orgDao){
        
        $orgInputs->setIdorgs(mysql_real_escape_string($_POST['orgid']));
        
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
        }
        $orgInputs->setPrdDynaCBIds($postPrdIds);
        
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
        }
        $orgInputs->setTskDynaCBIds($postTskIds);
        
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
        }
        $orgInputs->setWrkDynaCBIds($postWrkIds);
        
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
        }
        $orgInputs->setCstDynaCBIds($postCstIds);
        
        $postRptIds = '';
        $reportingDynaFields = $orgDao->getReportingDynaFields();
        foreach($reportingDynaFields as $reportingDynaField){
            $cbId = $reportingDynaField->getIdDynaFields();
            if (isset($_POST[$cbId])){
                $postRptIds = $postRptIds . substr($_POST[$cbId], strlen(DYNAFIELDS_REPORTING_CBID_PREFIX)) . '|';
            }
        }
        if (!empty($postRptIds)){
            $postRptIds = substr($postRptIds, 0, strlen($postRptIds)-1);
        }
        $orgInputs->setRptDynaCBIds($postRptIds);

    }

?>