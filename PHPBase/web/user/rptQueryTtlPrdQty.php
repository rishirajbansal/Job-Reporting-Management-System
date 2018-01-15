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
include_once(dirname(__FILE__) . "/../../classes/business/ExportEngine.php");
include_once(dirname(__FILE__) . "/../../classes/vo/ExportData.php");


include_once("sessionMgmt.php");

require_once(dirname(__FILE__) . "/../commonInc/init.php");
require_once(dirname(__FILE__) . "/inc/config.ui.php");

ob_start();
/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_1', TXT_U);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["reporting"]["active"] = true;
$page_nav["reporting"]["sub"]["rptQFilters"]["active"] = true;
$page_nav["reporting"]["sub"]["rptQFilters"]["sub"]["qFilter3"]["active"] = true;
include("inc/nav.php");


/*------------- Form Submissions ---------*/

$userOrgInputs = new UserOrgInputs();
$userOrgDao = new UserOrgDao();
$exportEngine = new ExportEngine();
$exportData = new ExportData();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$orgid = $_SESSION['orgid'];
$userOrgInputs->setIdorgs($orgid);

$allDetailsLoaded = FALSE;
$clientNames = array();
$workerNames = array();
$productNames = array();
$reportQFilterResSet = array();

$isListLoaded = FALSE;
$rptFlag = FALSE;
$generatedFlag = FALSE;

//Load Prerequisites
$loadFlag = $userOrgDao->fetchClients($userOrgInputs);
if ($loadFlag){
    $clientNames = $userOrgDao->getClientNamesList();
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

$loadFlag = $userOrgDao->fetchWorkers($userOrgInputs);
if ($loadFlag){
    $workerNames = $userOrgDao->getWorkerNamesList();
    $allDetailsLoaded = TRUE;
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

if (isset($_POST['formsubmit']) && $_POST['formsubmit'] == 'rptQTtlPrdQtyForm'){
    
    preInputsInspection($userOrgInputs);
    
    $filterFlag = $userOrgDao->findQFilterTtlPrdQty($userOrgInputs);
    
    if ($filterFlag){
        $reportQFilterResSet = $userOrgDao->getReportQFilterResList();
        $rptFlag = TRUE;
        if (null != $reportQFilterResSet && !empty($reportQFilterResSet)){
            $isListLoaded = TRUE;
            if (isset($_SESSION['exportData_ttlPrdQty'])){
                unset($_SESSION['exportData_ttlPrdQty']);
            }
            $exportData->setQFilterData($reportQFilterResSet);
            $_SESSION['exportData_ttlPrdQty'] = $exportData;
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
else if (isset($_POST['formsubmit']) && $_POST['formsubmit'] == 'rptQTtlPrdQtyResForm'){
    
    ex_preInputsInspection($userOrgInputs);
    
    if (isset($_SESSION['exportData_ttlPrdQty'])){
        $exportData = $_SESSION['exportData_ttlPrdQty'];
        $exportData->setSearchCriteria($userOrgInputs);
        $_SESSION['exportData_ttlPrdQty'] = $exportData;
        
        header('location: dLoader.php?qf=ttlPrdQty');
    }

//    $generatedFlag = $exportEngine->generateTtlPrdQtyReport($userOrgInputs);
//    if ($generatedFlag){
//        
//    }
//    else{
//        if ($exportEngine->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
//            $errMsgObject['msg'] = 'message';
//            $errMsgObject['text'] = $exportEngine->getMessages();
//        }
//        else if ($exportEngine->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
//            $errMsgObject['msg'] = 'error';
//            $errMsgObject['text'] = $exportEngine->getErrors();
//        }
//        else if ($exportEngine->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
//            $errMsgObject['msg'] = 'criticalError';
//            $errMsgObject['text'] = $exportEngine->getCriticalError();
//        }
//    }
    
}

/*------------- End Form Submissions ---------*/


?>


<!-- MAIN PANEL -->
<div id="main" role="main">
    
    <?php
        $breadcrumbs[getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_2', TXT_U)] = "";
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-database"></i> <?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_3', TXT_U); ?></h1>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <?php
        
        if ($allDetailsLoaded) { ?>
            
            <section id="widget-grid" class="">

                <div class="row">

                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">

                            <header>
                                <span class="widget-icon"> <i class="fa fa-search"></i> </span>
                                <h2><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_4', TXT_U); ?> </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body" style="padding-left: 20px;padding-right: 20px;">

                                    <form class="form" id="rptQTtlPrdQtyForm" name="rptQTtlPrdQtyForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>

                                        <h4><strong><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_5', TXT_U); ?></strong></h4>
                                        <p><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_6', TXT_U); ?> </p>

                                        <fieldset>

                                            <legend style="margin-bottom: 7px;"></legend>
                                            <br/>
                                            
                                            <div class="alert alert-block alert-form-wizard-error" id="divErrBlock" style="display: none">
                                                <?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_25', TXT_U); ?>
                                            </div>

                                            <div class="col-md-3" style="padding-left: 0px;">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <label class="control-label control-label-filter"><strong><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_7', TXT_U); ?></strong> </label>
                                                    </div>
                                                    
                                                    <input class="form-control" type="text" placeholder="<?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_8', TXT_U); ?>" name="sdate" id="sdate" value="<?php if (isset($userOrgInputs) && !empty($userOrgInputs->getQf_sdate())) { echo $userOrgInputs->getQf_sdate(); } ?>" >
                                                    <span class="input-group-addon "><i class="fa fa-calendar fa-fw"></i></span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <label class="control-label control-label-filter"><strong><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_9', TXT_U); ?></strong> </label>
                                                    </div>
                                                    <input class="form-control" type="text" placeholder="<?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_10', TXT_U); ?>" name="edate" id="edate" value="<?php if (isset($userOrgInputs) && !empty($userOrgInputs->getQf_edate())) { echo $userOrgInputs->getQf_edate(); } ?>" >
                                                    <span class="input-group-addon "><i class="fa fa-calendar fa-fw"></i></span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <label class="control-label control-label-filter"><strong><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_11', TXT_U); ?></strong> </label>
                                                    </div>
                                                    <select class="form-control" name="client" id="client">
                                                        <option value=""><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_12', TXT_U); ?></option>
                                                        <?php
                                                        foreach ($clientNames as $name) { ?>
                                                        <option <?php if (isset($userOrgInputs) && !empty($userOrgInputs->getQf_clientname()) && $userOrgInputs->getQf_clientname() == $name) { ?> selected <?php } ?> ><?php echo $name; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <span class="input-group-addon "><i class="fa fa-building fa-fw"></i></span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <label class="control-label control-label-filter"><strong><?php echo getLocaleText('RPT_Q_TTL_PRD_QTYW_MSG_13', TXT_U); ?></strong> </label>
                                                    </div>
                                                    <select class="form-control" name="worker" id="worker">
                                                        <option value=""><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_14', TXT_U); ?></option>
                                                        <?php
                                                        foreach ($workerNames as $name) { ?>
                                                        <option <?php if (isset($userOrgInputs) && !empty($userOrgInputs->getQf_workername()) && $userOrgInputs->getQf_workername() == $name) { ?> selected <?php } ?> ><?php echo $name; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <span class="input-group-addon "><i class="fa fa-user fa-fw"></i></span>
                                                </div>
                                            </div>

                                            <br/>
                                            <div class="form-actions" style="border: none;background: none;">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-default" id="btnClear">
                                                            <i class="fa fa-recycle"></i> <?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_BTN_1', TXT_U); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-default" id="btnReset">
                                                            <i class="fa fa-refresh"></i> <?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_BTN_2', TXT_U); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-primary" id="btnSearch">
                                                            <i class="fa fa-search"></i> <?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_BTN_3', TXT_U); ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <br/>

                                        </fieldset>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </article>

                    <?php

                    if ($rptFlag) { ?>

                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            <div class="jarviswidget jarviswidget-color-purple" id="wid-id-1" 
                                data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">

                                <header>
                                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                    <h2><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_15', TXT_U); ?> </h2>
                                </header>

                                <!-- widget div-->
                                <div>

                                    <div class="jarviswidget-editbox">

                                    </div>

                                    <div class="widget-body no-padding">

                                        <form class="form-horizontal" id="rptQTtlPrdQtyResForm" name="rptQTtlPrdQtyResForm" method="post" action="">
                                            <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                            <input type="hidden" name="exportType" id="exportType" value="" />
                                            <input type="hidden" name="ex_sdate" id="ex_sdate" value="<?php echo $userOrgInputs->getQf_sdate(); ?>" />
                                            <input type="hidden" name="ex_edate" id="ex_edate" value="<?php echo $userOrgInputs->getQf_edate(); ?>" />
                                            <input type="hidden" name="ex_client" id="ex_client" value="<?php echo $userOrgInputs->getQf_clientname(); ?>" />
                                            <input type="hidden" name="ex_worker" id="ex_worker" value="<?php echo $userOrgInputs->getQf_workername(); ?>" />

                                            <?php 
                                            if ($isListLoaded === FALSE){ ?>
                                                <div class="alert alert-warning fade in">
                                                    <button class="close" data-dismiss="alert">
                                                            ×
                                                    </button>
                                                    <i class="fa-fw fa fa-warning"></i>
                                                    <?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_16', TXT_U); ?>
                                                </div>
                                            <?php
                                            } 
                                            ?>
                                            <table id="datatable_fixed_column" class="table table-bordered " width="100%">

                                                <thead>

                                                    <tr>
                                                        <th data-class="expand" style="width:10%"><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_17', TXT_U); ?></th>
                                                        <th data-hide="phone,tablet" style="width:15%"><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_18', TXT_U); ?></th>
                                                        <th style="width:35%"><?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_19', TXT_U); ?></th>
                                                    </tr>

                                                </thead>

                                                <tbody>
                                                    <?php 
                                                    if ($isListLoaded === TRUE){
                                                        foreach ($reportQFilterResSet as $reportQFilterRes){ ?>
                                                    
                                                            <tr>
                                                                <td style="padding-top: 14px;vertical-align: middle;"><?php echo $reportQFilterRes->getRptNo(); ?></td>
                                                                <td style="padding-top: 14px;vertical-align: middle;"><?php echo $reportQFilterRes->getRptSubmitDate(); ?></td>
                                                                <td style="padding: 0px;">
                                                                    <?php
                                                                    $prdListArr = $reportQFilterRes->getPrdQtyList();
                                                                    if (!empty($prdListArr)){ ?>
                                                                    
                                                                        <table class="table  no-padding">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th data-class="expand" style="width:50%"><?php echo getLocaleText('Product', TXT_U); ?></th>
                                                                                    <th><?php echo getLocaleText('Quantity Used', TXT_U); ?></th>
                                                                                </tr>
                                                                            </thead>

                                                                            <tbody>
                                                                                <?php
                                                                                foreach ($prdListArr as $prdList){ ?>
                                                                                    <tr>
                                                                                        <td style="padding-top: 14px;"><?php echo $prdList[0]; ?></td>
                                                                                        <td style="padding-top: 14px;"><?php echo $prdList[1]; ?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </tbody>

                                                                        </table>
                                                                            
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        
                                                        <?php 
                                                        }
                                                    }
                                                    ?>

                                                </tbody>

                                            </table>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </article>

                    <?php
                    }
                    ?>

                </div>

            </section>
            
        <?php
        }
        else if (empty ($userOrgDao->getMsgType())) { ?>
            <div class="alert alert-danger fade in">
                <i class="fa-fw fa fa-times"></i>
                <?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_22', TXT_U); ?>
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

<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>


<script type="text/javascript">

    $(document).ready(function() {
        
        pageSetUp();
        
        
        var $validator = $("#rptQTtlPrdQtyForm").validate({

                rules: {
                    sdate: {
                        required: function(element){
                            return $("#edate").val().length > 0;
                        }
                    },
                    edate: {
                        required: function(element){
                            return $("#sdate").val().length > 0;
                        }
                    },
                    client: {
                        //required: true
                    },
                    worker: {
                        //required: true
                    }
                },

                messages: {
                    sdate: {
                        required: "<?php echo getLocaleText('RPT_Q_TCW_MSG_VALID_1', TXT_U); ?>"
                    },
                    edate: {
                        required: "<?php echo getLocaleText('RPT_Q_TCW_MSG_VALID_2', TXT_U); ?>"
                    },
                    client: {
                        //required: "<?php echo getLocaleText('RPT_Q_TCW_MSG_VALID_3', TXT_U); ?>"
                    },
                    worker: {
                        //required: "<?php echo getLocaleText('RPT_Q_TCW_MSG_VALID_4', TXT_U); ?>"
                    }
                },

                highlight: function (element) {
                    $(element).closest('.input-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element).closest('.input-group').removeClass('has-error').addClass('has-success');
                },

                errorElement: 'span',
                errorClass: 'help-block-qf',
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } 
                    else {
                        error.insertAfter(element);
                    }
                }

            });
        
        $('#btnSearch').on('click', function () {
            var $valid = $("#rptQTtlPrdQtyForm").valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            else if (document.forms['rptQTtlPrdQtyForm']['sdate'].value == '' && 
                    document.forms['rptQTtlPrdQtyForm']['edate'].value == '' && 
                    document.forms['rptQTtlPrdQtyForm']['client'].value == '' && 
                    document.forms['rptQTtlPrdQtyForm']['worker'].value == ''){
                
                document.getElementById('divErrBlock').style.display = 'block';
            }
            else{
                document.forms['rptQTtlPrdQtyForm']['formsubmit'].value = 'rptQTtlPrdQtyForm';
                $("#rptQTtlPrdQtyForm").submit();
            }
        });
        
        $('#btnReset').on('click', function () {
            $validator.resetForm();
            document.getElementById('rptQTtlPrdQtyForm').reset();
        });
        
        $('#btnClear').on('click', function () {
            $(":input").val('');
        });
        
        
        var responsiveHelper_dt_basic = undefined;
        var responsiveHelper_datatable_fixed_column = undefined;
        var responsiveHelper_datatable_col_reorder = undefined;
        var responsiveHelper_datatable_tabletools = undefined;

        var breakpointDefinition = {
                tablet : 1024,
                phone : 480
        };
        
        var otable = $('#datatable_fixed_column').DataTable({
                    //"bFilter": false,
                    "ordering": false,
                    "bPaginate": false,
                    "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
                                "t"+
                                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
                    "autoWidth" : true,
                    "preDrawCallback" : function() {
                            if (!responsiveHelper_datatable_fixed_column) {
                                responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
                            }
                    },
                    "rowCallback" : function(nRow) {
                            responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
                    },
                    "drawCallback" : function(oSettings) {
                            responsiveHelper_datatable_fixed_column.respond();
                    }
        });
        
        <?php 
        if ($isListLoaded === TRUE){ ?>
            $("div.toolbar").html('<div class="text-right"><a href="javascript:exportRpt(\'CSV\');" class="btn btn-default" style="min-width: 100px;"><i class="fa fa-file-text-o"></i> <span class="hidden-mobile">CSV</span></a><a href="javascript:exportRpt(\'XLSX\');" class="btn btn-default" style="min-width: 100px;"><i class="fa fa-file-excel-o"></i> <span class="hidden-mobile">Excel</span></a></div>');
        <?php
        }
        ?>

        $('#sdate').datepicker({
            dateFormat : 'dd/mm/yy',
            prevText : '<i class="fa fa-chevron-left"></i>',
            nextText : '<i class="fa fa-chevron-right"></i>',
            onSelect : function(selectedDate) { 
                            $('#edate').datepicker('option', 'minDate', selectedDate);
                        },
            onClose : function(text, inst) {
                            if (text == '') {
                                $('#edate').datepicker('option', 'minDate', null);
                            }
                        }
        });
        
        $('#edate').datepicker({
            dateFormat : 'dd/mm/yy',
            prevText : '<i class="fa fa-chevron-left"></i>',
            nextText : '<i class="fa fa-chevron-right"></i>',
            onSelect : function(selectedDate) {
                            $('#sdate').datepicker('option', 'maxDate', selectedDate);
                        },
            onClose : function(text, inst) {
                            if (text == '') {
                                $('#sdate').datepicker('option', 'maxDate', null);
                            }
                        }
        });
        
        
    })
    
    
    function exportRpt(type){
        
        $.smallBox({
            title: "<?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_23', TXT_U); ?>",
            content: "<?php echo getLocaleText('RPT_Q_TTL_PRD_QTY_MSG_24', TXT_U); ?> ",
            color: "#296191",
            iconSmall: "fa fa-check bounce animated",
            timeout: 10000
        });
        
        document.forms['rptQTtlPrdQtyResForm']['exportType'].value = type;
        document.forms['rptQTtlPrdQtyResForm']['formsubmit'].value = 'rptQTtlPrdQtyResForm';
        $("#rptQTtlPrdQtyResForm").submit();
    
    }
   


</script>


<?php

    function preInputsInspection(UserOrgInputs $userOrgInputs){
        
        $userOrgInputs->setQf_sdate(mysql_real_escape_string($_POST['sdate']));
        $userOrgInputs->setQf_edate(mysql_real_escape_string($_POST['edate']));
        $userOrgInputs->setQf_clientname(mysql_real_escape_string($_POST['client']));
        $userOrgInputs->setQf_workername(mysql_real_escape_string($_POST['worker']));

    }
    
    function ex_preInputsInspection(UserOrgInputs $userOrgInputs){
        
        $userOrgInputs->setQf_sdate(mysql_real_escape_string($_POST['ex_sdate']));
        $userOrgInputs->setQf_edate(mysql_real_escape_string($_POST['ex_edate']));
        $userOrgInputs->setQf_clientname(mysql_real_escape_string($_POST['ex_client']));
        $userOrgInputs->setQf_workername(mysql_real_escape_string($_POST['ex_worker']));
        $userOrgInputs->setEx_type(mysql_real_escape_string($_POST['exportType']));

    }
    
    ob_end_flush();
    
?>