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
$page_title = getLocaleText('VIEW_ORG_MSG_1', TXT_A);
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

if (isset($_POST['formsubmit']) && $_POST['formsubmit'] != 'listorgsubmit'){
    
}
else if (!empty($loadFlag) && isset($_POST['formsubmit']) && $_POST['formsubmit'] == 'listorgsubmit' && isset($_POST['mode'])){
    
    if ($_POST['mode'] == ORG_LIST_MODE_VIEW){
        $orgId = $_POST['orgid'];
        $orgInputs->setIdorgs($orgId);

        $fetchFlag = $orgDao->viewOrg($orgInputs);

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
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-building"></i><?php echo getLocaleText('VIEW_ORG_MSG_2', TXT_A); ?> </h1>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <a href="org.php" class="btn bg-color-blueLight txt-color-white btn-lg pull-right header-btn hidden-mobile"><i class="fa fa-circle-arrow-up fa-lg"></i> <?php echo getLocaleText('VIEW_ORG_MSG_3', TXT_A); ?></a>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <?php
        
        if ($isDetailsFetched) { ?>
            
            <section id="widget-grid" class="">
                
                <div class="row">
                    
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        
                        <div class="jarviswidget" id="wid-id-0" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">
                            
                            <header>
                                <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                                <h2><?php echo getLocaleText('VIEW_ORG_MSG_4', TXT_A); ?> - <strong class="text-primary"><i><?php echo $orgDetails->getName(); ?></i></strong> </h2>
                            </header>
                            
                            <!-- widget div-->
                            <div>
                                
                                <div class="jarviswidget-editbox">

                                </div>
                                
                                <div class="widget-body" style="min-height: 450px;">
                                    
                                    <form class="form-horizontal" id="viewOrgDetailsForm" name="viewOrgDetailsForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                        <input type="hidden" name="orgid" id="orgid" value=""/>
                                        <input type="hidden" name="orgname" id="orgname" value=""/>
                                        <input type="hidden" name="mode" id="mode" value=""/>
                                        
                                        <div class="col-md-12">
                                            <div class="col-md-9">
                                                <h3><strong><?php echo getLocaleText('VIEW_ORG_MSG_5', TXT_A); ?></strong></h3>
                                                <p><?php echo getLocaleText('VIEW_ORG_MSG_6', TXT_A); ?></p>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-actions" style="border: none;background: none;">
                                                    <div class="row">
                                                        <div class="col-sm-12" >
                                                            <button type="button" class="btn btn-default btn-primary" id="edit" style="font-size: 14px;">
                                                                &nbsp;&nbsp;<i class="fa fa-edit"></i> &nbsp;<?php echo getLocaleText('VIEW_ORG_MSG_BTN_1', TXT_A); ?>&nbsp;&nbsp;
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-default btn-danger" id="delete" style="font-size: 14px;">
                                                                &nbsp;&nbsp;<i class="fa fa-trash-o"></i>&nbsp;<?php echo getLocaleText('VIEW_ORG_MSG_BTN_2', TXT_A); ?> &nbsp;&nbsp;
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <legend></legend>
                                        <br/>

                                        <div class="col-md-6">
                                            <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                <header>
                                                    <h2><?php echo getLocaleText('VIEW_ORG_MSG_7', TXT_A); ?> </h2>
                                                </header>
                                                <div>

                                                    <div class="jarviswidget-editbox">
                                                    </div>

                                                    <div class="widget-body">
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><strong><?php echo getLocaleText('VIEW_ORG_MSG_8', TXT_A); ?> : </strong></label>
                                                            <label class="col-md-8 control-label" style="text-align: left"><?php echo $orgDetails->getName(); ?></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><strong><?php echo getLocaleText('VIEW_ORG_MSG_9', TXT_A); ?> : </strong></label>
                                                            <label class="col-md-8 control-label" style="text-align: left"><?php echo $orgDetails->getPhone(); ?></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><strong><?php echo getLocaleText('VIEW_ORG_MSG_10', TXT_A); ?> : </strong></label>
                                                            <label class="col-md-8 control-label" style="text-align: left"><?php echo $orgDetails->getEmail(); ?></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><strong><?php echo getLocaleText('VIEW_ORG_MSG_11', TXT_A); ?> : </strong></label>
                                                            <label class="col-md-8 control-label" style="text-align: left"><?php echo $orgDetails->getUsername(); ?></label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><strong><?php echo getLocaleText('VIEW_ORG_MSG_12', TXT_A); ?> : </strong></label>
                                                            <label class="col-md-8 control-label" style="text-align: left"><?php echo $orgDetails->getPassword(); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                <header>
                                                    <h2><?php echo getLocaleText('VIEW_ORG_MSG_13', TXT_A); ?> </h2>
                                                </header>
                                                <div>

                                                    <div class="jarviswidget-editbox">
                                                    </div>

                                                    <div class="widget-body" style="min-height: 215px;">
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label"><strong><?php echo getLocaleText('VIEW_ORG_MSG_14', TXT_A); ?> </strong></label>
                                                            <div class="col-md-5">
                                                                <?php
                                                                if (file_exists($orgDetails->getLogoPath())){ ?>
                                                                <div class="input-group input-group-md" id="imagepreview" style="margin-top: 6px;">
                                                                    <img src="<?php echo $orgDetails->getLogoPath(); ?>" style="max-width: 200px;">
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
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="jarviswidget" id="wid-id-3" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                <header>
                                                    <h2><?php echo getLocaleText('VIEW_ORG_MSG_15', TXT_A); ?> </h2>
                                                </header>
                                                <div>

                                                    <div class="jarviswidget-editbox">
                                                    </div>

                                                    <div class="widget-body">
                                                        <div class="form-group">
                                                            <label class="col-md-12"><?php echo getLocaleText('VIEW_ORG_MSG_16', TXT_A); ?> </label>
                                                            <br/><br/>
                                                            <label class="col-md-12" style="margin-left: 20px;">
                                                                <div id="sumPrds">
                                                                    <ol id="ol-sumPrds" class="list-unstyled" style="font-size: 110%!important;">
                                                                        <?php
                                                                        $orgDynaProduct = $orgDao->getOrgProductDynaFields();
                                                                        $dynaFieldsProcessedDetails = $orgDynaProduct->getDynaFieldsProcessedDetails();

                                                                        foreach($productDynaFields as $prdDynaField){
                                                                            $cbName = $prdDynaField->getIdDynaFields();
                                                                            $childList = $prdDynaField->getHtmlListValuesEn();

                                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                                if ($value['fieldId'] == $cbName){
                                                                                    $xEditSavedValues = $value['fieldValues'];
                                                                                    $fieldName = $prdDynaField->getNameEn();
                                                                                    ?>
                                                                                    <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> <?php echo $fieldName; ?></li>
                                                                                    <?php 
                                                                                    if (!empty($xEditSavedValues) && $xEditSavedValues != 'none'){
                                                                                        $xEditSavedValuesArr = explode(',', $xEditSavedValues);
                                                                                        ?>
                                                                                        <div id="xlist">
                                                                                            <ol style="list-style-type:none;font-size: 90%!important;margin-bottom: 10px;margin-left: -10px;">
                                                                                                <li><span><i class="fa fa-lg fa-check-circle" style="color: #739e73"></i> Options Chosen</span>
                                                                                                    <?php
                                                                                                    foreach ($xEditSavedValuesArr as $xValue){ ?>
                                                                                                        <ol style="list-style-type:none;margin-left: -10px;">
                                                                                                            <li>&ndash; <?php echo $xValue; ?></li>
                                                                                                        </ol>
                                                                                                    <?php    
                                                                                                    }  ?> 
                                                                                                </li>
                                                                                            </ol>
                                                                                        </div>
                                                                                    <?php
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </ol>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                <header>
                                                    <h2><?php echo getLocaleText('VIEW_ORG_MSG_17', TXT_A); ?> </h2>
                                                </header>
                                                <div>

                                                    <div class="jarviswidget-editbox">
                                                    </div>

                                                    <div class="widget-body">
                                                        <div class="form-group">
                                                            <label class="col-md-12"><?php echo getLocaleText('VIEW_ORG_MSG_18', TXT_A); ?> </label>
                                                            <br/><br/>
                                                            <label class="col-md-12" style="margin-left: 20px;">
                                                                <div id="sumTsks">
                                                                    <ol id="ol-sumTsks" class="list-unstyled" style="font-size: 110%!important;">
                                                                    <?php
                                                                        $orgDynaTask = $orgDao->getOrgTaskDynaFields();
                                                                        $dynaFieldsProcessedDetails = $orgDynaTask->getDynaFieldsProcessedDetails();

                                                                        foreach($tasksDynaFields as $tsksDynaField){
                                                                            $cbName = $tsksDynaField->getIdDynaFields();
                                                                            $childList = $tsksDynaField->getHtmlListValuesEn();

                                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                                if ($value['fieldId'] == $cbName){
                                                                                    $xEditSavedValues = $value['fieldValues'];
                                                                                    $fieldName = $tsksDynaField->getNameEn();
                                                                                    ?>
                                                                                    <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> <?php echo $fieldName; ?></li>
                                                                                    <?php 
                                                                                    if (!empty($xEditSavedValues) && $xEditSavedValues != 'none'){
                                                                                        $xEditSavedValuesArr = explode(',', $xEditSavedValues);
                                                                                        ?>
                                                                                        <div id="xlist">
                                                                                            <ol style="list-style-type:none;font-size: 90%!important;margin-bottom: 10px;margin-left: -10px;">
                                                                                                <li><span><i class="fa fa-lg fa-check-circle" style="color: #739e73"></i> Options Chosen</span>
                                                                                                    <?php
                                                                                                    foreach ($xEditSavedValuesArr as $xValue){ ?>
                                                                                                        <ol style="list-style-type:none;margin-left: -10px;">
                                                                                                            <li>&ndash; <?php echo $xValue; ?></li>
                                                                                                        </ol>
                                                                                                    <?php    
                                                                                                    }  ?> 
                                                                                                </li>
                                                                                            </ol>
                                                                                        </div>
                                                                                    <?php
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </ol>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="jarviswidget" id="wid-id-5" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                <header>
                                                    <h2><?php echo getLocaleText('VIEW_ORG_MSG_19', TXT_A); ?> </h2>
                                                </header>
                                                <div>

                                                    <div class="jarviswidget-editbox">
                                                    </div>

                                                    <div class="widget-body">
                                                        <div class="form-group">
                                                            <label class="col-md-12"><?php echo getLocaleText('VIEW_ORG_MSG_20', TXT_A); ?> </label>
                                                            <br/><br/>
                                                            <label class="col-md-12" style="margin-left: 20px;">
                                                                <div id="sumWrks">
                                                                    <ol id="ol-sumWrks" class="list-unstyled" style="font-size: 110%!important;">
                                                                        <?php
                                                                        $orgDynaWorker = $orgDao->getOrgWorkerDynaField();
                                                                        $dynaFieldsProcessedDetails = $orgDynaWorker->getDynaFieldsProcessedDetails();

                                                                        foreach($workerDynaFields as $wrksDynaField){ 
                                                                            $cbName = $wrksDynaField->getIdDynaFields();
                                                                            $childList = $wrksDynaField->getHtmlListValuesEn();

                                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                                if ($value['fieldId'] == $cbName){
                                                                                    $xEditSavedValues = $value['fieldValues'];
                                                                                    $fieldName = $wrksDynaField->getNameEn();
                                                                                    ?>
                                                                                    <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> <?php echo $fieldName; ?></li>
                                                                                    <?php 
                                                                                    if (!empty($xEditSavedValues) && $xEditSavedValues != 'none'){
                                                                                        $xEditSavedValuesArr = explode(',', $xEditSavedValues);
                                                                                        ?>
                                                                                        <div id="xlist">
                                                                                            <ol style="list-style-type:none;font-size: 90%!important;margin-bottom: 10px;margin-left: -10px;">
                                                                                                <li><span><i class="fa fa-lg fa-check-circle" style="color: #739e73"></i> Options Chosen</span>
                                                                                                    <?php
                                                                                                    foreach ($xEditSavedValuesArr as $xValue){ ?>
                                                                                                        <ol style="list-style-type:none;margin-left: -10px;">
                                                                                                            <li>&ndash; <?php echo $xValue; ?></li>
                                                                                                        </ol>
                                                                                                    <?php    
                                                                                                    }  ?> 
                                                                                                </li>
                                                                                            </ol>
                                                                                        </div>
                                                                                    <?php
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </ol>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="jarviswidget" id="wid-id-6" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                                <header>
                                                    <h2><?php echo getLocaleText('VIEW_ORG_MSG_21', TXT_A); ?> </h2>
                                                </header>
                                                <div>

                                                    <div class="jarviswidget-editbox">
                                                    </div>

                                                    <div class="widget-body">
                                                        <div class="form-group">
                                                            <label class="col-md-12"><?php echo getLocaleText('VIEW_ORG_MSG_22', TXT_A); ?> </label>
                                                            <br/><br/>
                                                            <label class="col-md-12" style="margin-left: 20px;">
                                                                <div id="sumCstmrs">
                                                                    <ol id="ol-sumCstmrs" class="list-unstyled" style="font-size: 110%!important;">
                                                                        <?php
                                                                       $orgDynaCustomer = $orgDao->getOrgCustomerDynaFields();
                                                                        $dynaFieldsProcessedDetails = $orgDynaCustomer->getDynaFieldsProcessedDetails();

                                                                       foreach($customerDynaFields as $cstmrDynaField){ 
                                                                            $cbName = $cstmrDynaField->getIdDynaFields();
                                                                            $childList = $cstmrDynaField->getHtmlListValuesEn();

                                                                            foreach ($dynaFieldsProcessedDetails as $key => $value) {
                                                                                if ($value['fieldId'] == $cbName){
                                                                                    $xEditSavedValues = $value['fieldValues'];
                                                                                    $fieldName = $cstmrDynaField->getNameEn();
                                                                                    ?>
                                                                                    <li style="padding-bottom: 10px"><i class="fa fa-check text-info"></i> <?php echo $fieldName; ?></li>
                                                                                    <?php 
                                                                                    if (!empty($xEditSavedValues) && $xEditSavedValues != 'none'){
                                                                                        $xEditSavedValuesArr = explode(',', $xEditSavedValues);
                                                                                        ?>
                                                                                        <div id="xlist">
                                                                                            <ol style="list-style-type:none;font-size: 90%!important;margin-bottom: 10px;margin-left: -10px;">
                                                                                                <li><span><i class="fa fa-lg fa-check-circle" style="color: #739e73"></i> Options Chosen</span>
                                                                                                    <?php
                                                                                                    foreach ($xEditSavedValuesArr as $xValue){ ?>
                                                                                                        <ol style="list-style-type:none;margin-left: -10px;">
                                                                                                            <li>&ndash; <?php echo $xValue; ?></li>
                                                                                                        </ol>
                                                                                                    <?php    
                                                                                                    }  ?> 
                                                                                                </li>
                                                                                            </ol>
                                                                                        </div>
                                                                                    <?php
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                  </ol>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    
                                    </form>
                                    
                                </div>
                                
                            </div>
                                 
                        </div>
                        
                    </article>
                    
                </div>

            </section>
        
            <div id="deleteConf" title="">
                <br/>
                <h5><?php echo getLocaleText('VIEW_ORG_MSG_23', TXT_A); ?></h5>
                <br/>
            </div>
        
        <?php
        }
        else if (empty ($orgDao->getMsgType())) { ?>
            <div class="alert alert-danger fade in">
                <i class="fa-fw fa fa-times"></i>
                <?php echo getLocaleText('VIEW_ORG_MSG_24', TXT_A); ?>
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
        
        
        $('#deleteConf').dialog({
                autoOpen : false,
                width : 600,
                resizable : false,
                modal : true,
                //title : "<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> On Delete Confirmation!</h4></div>",
                buttons : [{
                        html : "<i class='fa fa-trash-o'></i>&nbsp; <?php echo getLocaleText('VIEW_ORG_MSG_25', TXT_A); ?>",
                        "class" : "btn btn-danger",
                        click : function() {
                                document.getElementById('mode').value = '<?php echo ORG_LIST_MODE_DELETE; ?>';
                                document.getElementById('orgid').value = <?php echo $orgDetails->getIdorgs(); ?>;
                                document.getElementById('orgname').value = '<?php echo $orgDetails->getName(); ?>';
                                document.getElementById('formsubmit').value = 'vieworgsubmit';
                                document.forms['viewOrgDetailsForm'].action = 'org.php';
                                $("#viewOrgDetailsForm").submit();
                        }
                }, {
                        html : "<i class='fa fa-times'></i>&nbsp; <?php echo getLocaleText('VIEW_ORG_MSG_26', TXT_A); ?>",
                        "class" : "btn btn-default",
                        click : function() {
                                $(this).dialog("close");
                        }
                }],
                open: function(event, ui){
                        $(this).parent().find('.ui-dialog-title').append("<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> <?php echo getLocaleText('VIEW_ORG_MSG_27', TXT_A); ?></h4></div>");
                    },
                close: function(event, ui){
                        $(this).parent().find('.widget-header').remove();
                    }
        });
        
        
        $('#edit').on('click', function () {
            document.getElementById('mode').value = '<?php echo ORG_LIST_MODE_EDIT; ?>';
            document.getElementById('orgid').value = <?php echo $orgDetails->getIdorgs(); ?>;
            document.getElementById('orgname').value = '<?php echo $orgDetails->getName(); ?>';
            document.getElementById('formsubmit').value = 'vieworgsubmit';
            document.forms['viewOrgDetailsForm'].action = 'editOrg.php';
            $("#viewOrgDetailsForm").submit();
        });
        
        $('#delete').on('click', function () {
            
            $('#deleteConf').dialog('open');
            
//            $.SmartMessageBox({
//                    title : "On Delete Confirmation!",
//                    content : "Are you sure want to delete this organization from the system, this operation cannot be undone, once deleted the details of the organization cannot be recovered.",
//                    buttons : '[No][Yes]'
//            }, function(ButtonPressed) {
//                    if (ButtonPressed === "Yes") {
//                        document.getElementById('mode').value = '<?php echo ORG_LIST_MODE_DELETE; ?>';
//                        document.getElementById('orgid').value = <?php echo $orgDetails->getIdorgs(); ?>;
//                        document.getElementById('orgname').value = '<?php echo $orgDetails->getName(); ?>';
//                        document.getElementById('formsubmit').value = 'vieworgsubmit';
//                        document.forms['viewOrgDetailsForm'].action = 'org.php';
//                        $("#viewOrgDetailsForm").submit();
//                    }
//                    if (ButtonPressed === "No") {
//                        
//                    }
//            });
            
        });
        

    });

</script>