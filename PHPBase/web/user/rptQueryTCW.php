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
$page_title = getLocaleText('RPT_Q_TCW_MSG_1', TXT_U);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["reporting"]["active"] = true;
$page_nav["reporting"]["sub"]["rptQFilters"]["active"] = true;
$page_nav["reporting"]["sub"]["rptQFilters"]["sub"]["qFilter1"]["active"] = true;
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
$reportQFilterResSet = array();

$isListLoaded = FALSE;
$rptFlag = FALSE;
$resCtr = 1;
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

if (isset($_POST['formsubmit']) && $_POST['formsubmit'] == 'rptQTCWForm'){
    
    preInputsInspection($userOrgInputs);
    
    $filterFlag = $userOrgDao->findQFilterTCW($userOrgInputs);
    
    if ($filterFlag){
        $reportQFilterResSet = $userOrgDao->getReportQFilterResList();
        $rptFlag = TRUE;
        if (null != $reportQFilterResSet && !empty($reportQFilterResSet)){
            $isListLoaded = TRUE;
            if (isset($_SESSION['exportData_tcw'])){
                unset($_SESSION['exportData_tcw']);
            }
            $exportData->setQFilterData($reportQFilterResSet);
            $_SESSION['exportData_tcw'] = $exportData;
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
else if (isset($_POST['formsubmit']) && $_POST['formsubmit'] == 'rptQTCWResForm'){
    
    ex_preInputsInspection($userOrgInputs);
    
    if (isset($_SESSION['exportData_tcw'])){
        $exportData = $_SESSION['exportData_tcw'];
        $exportData->setSearchCriteria($userOrgInputs);
        $_SESSION['exportData_tcw'] = $exportData;
        
        header('location: dLoader.php?qf=tcw');
    }
    
//    $generatedFlag = $exportEngine->generateTCWReport($userOrgInputs);
//    if ($generatedFlag){
//        
//        //header('location: dLoader.php');
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
        $breadcrumbs[getLocaleText('RPT_Q_TCW_MSG_2', TXT_U)] = "";
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-database"></i> <?php echo getLocaleText('RPT_Q_TCW_MSG_3', TXT_U); ?></h1>
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
                                <h2><?php echo getLocaleText('RPT_Q_TCW_MSG_4', TXT_U); ?> </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body" style="padding-left: 20px;padding-right: 20px;">

                                    <form class="form" id="rptQTCWForm" name="rptQTCWForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>

                                        <h4><strong><?php echo getLocaleText('RPT_Q_TCW_MSG_5', TXT_U); ?></strong></h4>
                                        <p><?php echo getLocaleText('RPT_Q_TCW_MSG_6', TXT_U); ?> </p>

                                        <fieldset>

                                            <legend style="margin-bottom: 7px;"></legend>
                                            <br/>
                                            
                                            <div class="alert alert-block alert-form-wizard-error" id="divErrBlock" style="display: none">
                                                <?php echo getLocaleText('RPT_Q_TCW_MSG_26', TXT_U); ?>
                                            </div>

                                            <div class="col-md-3" style="padding-left: 0px;">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <label class="control-label control-label-filter"><strong><?php echo getLocaleText('RPT_Q_TCW_MSG_7', TXT_U); ?></strong> </label>
                                                    </div>
                                                    
                                                    <input class="form-control" type="text" placeholder="<?php echo getLocaleText('RPT_Q_TCW_MSG_8', TXT_U); ?>" name="sdate" id="sdate" value="<?php if (isset($userOrgInputs) && !empty($userOrgInputs->getQf_sdate())) { echo $userOrgInputs->getQf_sdate(); } ?>" >
                                                    <span class="input-group-addon "><i class="fa fa-calendar fa-fw"></i></span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <label class="control-label control-label-filter"><strong><?php echo getLocaleText('RPT_Q_TCW_MSG_9', TXT_U); ?></strong> </label>
                                                    </div>
                                                    <input class="form-control" type="text" placeholder="<?php echo getLocaleText('RPT_Q_TCW_MSG_10', TXT_U); ?>" name="edate" id="edate" value="<?php if (isset($userOrgInputs) && !empty($userOrgInputs->getQf_edate())) { echo $userOrgInputs->getQf_edate(); } ?>" >
                                                    <span class="input-group-addon "><i class="fa fa-calendar fa-fw"></i></span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <label class="control-label control-label-filter"><strong><?php echo getLocaleText('RPT_Q_TCW_MSG_11', TXT_U); ?></strong> </label>
                                                    </div>
                                                    <select class="form-control" name="client" id="client">
                                                        <option value=""><?php echo getLocaleText('RPT_Q_TCW_MSG_12', TXT_U); ?></option>
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
                                                        <label class="control-label control-label-filter"><strong><?php echo getLocaleText('RPT_Q_TCW_MSG_13', TXT_U); ?></strong> </label>
                                                    </div>
                                                    <select class="form-control" name="worker" id="worker">
                                                        <option value=""><?php echo getLocaleText('RPT_Q_TCW_MSG_14', TXT_U); ?></option>
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
                                                            <i class="fa fa-recycle"></i> <?php echo getLocaleText('RPT_Q_TCW_MSG_BTN_1', TXT_U); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-default" id="btnReset">
                                                            <i class="fa fa-refresh"></i> <?php echo getLocaleText('RPT_Q_TCW_MSG_BTN_2', TXT_U); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-primary" id="btnSearch">
                                                            <i class="fa fa-search"></i> <?php echo getLocaleText('RPT_Q_TCW_MSG_BTN_3', TXT_U); ?>
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
                                    <h2><?php echo getLocaleText('RPT_Q_TCW_MSG_15', TXT_U); ?> </h2>
                                </header>

                                <!-- widget div-->
                                <div>

                                    <div class="jarviswidget-editbox">

                                    </div>

                                    <div class="widget-body no-padding">

                                        <form class="form-horizontal" id="rptQTCWResForm" name="rptQTCWResForm" method="post" action="">
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
                                                    <?php echo getLocaleText('RPT_Q_TCW_MSG_16', TXT_U); ?>
                                                </div>
                                            <?php
                                            } 
                                            ?>
                                            <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">

                                                <thead>

                                                    <tr>
                                                        <th data-class="expand" style="width:10%"><?php echo getLocaleText('RPT_Q_TCW_MSG_17', TXT_U); ?></th>
                                                        <th data-hide="phone,tablet" style="width:15%"><?php echo getLocaleText('RPT_Q_TCW_MSG_18', TXT_U); ?></th>
                                                        <th data-hide="phone,tablet" style=""><?php echo getLocaleText('RPT_Q_TCW_MSG_19', TXT_U); ?></th>
                                                        <th data-hide="phone,tablet" style="width:14%"><?php echo getLocaleText('RPT_Q_TCW_MSG_20', TXT_U); ?></th>
                                                    </tr>

                                                </thead>

                                                <tbody>
                                                    <?php 
                                                    if ($isListLoaded === TRUE){
                                                        
                                                        foreach ($reportQFilterResSet as $reportQFilterRes){
                                                            $location = $reportQFilterRes->getLocation(); 
                                                            $location = trim($location); ?>
                                                    
                                                            <tr>
                                                                <td style="padding-top: 14px;"><?php echo $reportQFilterRes->getRptNo(); ?></td>
                                                                <td style="padding-top: 14px;"><?php echo $reportQFilterRes->getRptSubmitDate(); ?></td>
                                                                <td style="padding-top: 14px;">
                                                                    <?php
                                                                    if (strcmp(getLocaleText(LOCATION_MSG_NO_LOCATION_FOUND, TXT_A), $location) === 0 || 
                                                                        strcmp(getLocaleText(LOCATION_MSG_GPS_NOT_ENABLED, TXT_A), $location) === 0 ||
                                                                        strcmp(getLocaleText(LOCATION_MSG_GPS_ENABLED, TXT_A), $location) === 0 || 
                                                                        strcmp(getLocaleText(LOCATION_MSG_GEOCODE_TIMEDOUT, TXT_A), $location) === 0 ||
                                                                        empty($location)){ ?>
                                                                    
                                                                        <span class="text-info"><?php echo getLocaleText(UI_STRING_REPORT_TCW_LOCATION_NOT_FOUND, TXT_A); ?>
                                                                            <br/><br/>
                                                                            <span class="label label-danger"><?php echo getLocaleText('RPT_Q_TCW_MSG_21', TXT_U); ?></span>&nbsp;&nbsp;
                                                                            <i class="text-danger"><?php echo $reportQFilterRes->getLocation(); ?></i>
                                                                        </span>
                                                                        
                                                                    <?php
                                                                    }
                                                                    else{ ?>
                                                                    
                                                                        <div id="map_canvas_<?php echo $resCtr; ?>" class="google_maps" data-gmap-lat="<?php echo $reportQFilterRes->getLatitude(); ?>" data-gmap-lng="<?php echo $reportQFilterRes->getLongitude(); ?>" data-gmap-zoom="<?php echo MAP_ZOOM_LEVEL; ?>" data-gmap-src="<?php echo $reportQFilterRes->getMapMarkerFile(); ?>" >
                                                                            &nbsp;
                                                                        </div>
                                                                        
                                                                    <?php
                                                                    ++$resCtr;
                                                                    }
                                                                    ?>
                                                                
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-default bg-color-pinkDark txt-color-white" id="btnShow" style="min-width: 100px" onclick="javascript:showReport(<?php echo $reportQFilterRes->getIdorgs(); ?>, '<?php echo $reportQFilterRes->getIdOrgRpts(); ?>', '<?php echo $reportQFilterRes->getRptNo(); ?>');">
                                                                        <i class="fa fa-file-pdf-o"></i> &nbsp; <?php echo getLocaleText('RPT_Q_TCW_MSG_BTN_4', TXT_U); ?>
                                                                    </button>
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
                <?php echo getLocaleText('RPT_Q_TCW_MSG_22', TXT_U); ?>
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
        
        
        var $validator = $("#rptQTCWForm").validate({

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
            document.getElementById('divErrBlock').style.display = 'none';
            var $valid = $("#rptQTCWForm").valid();
            //alert($valid);
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            else if (document.forms['rptQTCWForm']['sdate'].value == '' && 
                    document.forms['rptQTCWForm']['edate'].value == '' && 
                    document.forms['rptQTCWForm']['client'].value == '' && 
                    document.forms['rptQTCWForm']['worker'].value == ''){
                
                document.getElementById('divErrBlock').style.display = 'block';
            }
            else{
                document.forms['rptQTCWForm']['formsubmit'].value = 'rptQTCWForm';
                $("#rptQTCWForm").submit();
            }
        });
        
        $('#btnReset').on('click', function () {
            $validator.resetForm();
            document.getElementById('rptQTCWForm').reset();
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
        
        
        var pagefunction = function() {

                /*jslint smarttabs:true */
                    var colorful_style = [{

                            "featureType" : "landscape",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "transit",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi.park",
                            "elementType" : "labels",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi.park",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "color" : "#d3d3d3"
                            }, {
                                    "visibility" : "on"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "geometry.stroke",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "landscape",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#b1bc39"
                            }]
                    }, {
                            "featureType" : "landscape.man_made",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#ebad02"
                            }]
                    }, {
                            "featureType" : "water",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#416d9f"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "labels.text.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#000000"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "labels.text.stroke",
                            "stylers" : [{
                                    "visibility" : "off"
                            }, {
                                    "color" : "#ffffff"
                            }]
                    }, {
                            "featureType" : "administrative",
                            "elementType" : "labels.text.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#000000"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#ffffff"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "labels.icon",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "water",
                            "elementType" : "labels",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "color" : "#ebad02"
                            }]
                    }, {
                            "featureType" : "poi.park",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "color" : "#8ca83c"
                            }]
                    }];

                    // Grey Scale
                    var greyscale_style = [{
                            "featureType" : "road.highway",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "landscape",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "transit",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi.park",
                            "stylers" : [{
                                    "visibility" : "on"
                            }]
                    }, {
                            "featureType" : "poi.park",
                            "elementType" : "labels",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi.park",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "color" : "#d3d3d3"
                            }, {
                                    "visibility" : "on"
                            }]
                    }, {
                            "featureType" : "poi.medical",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi.medical",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "geometry.stroke",
                            "stylers" : [{
                                    "color" : "#cccccc"
                            }]
                    }, {
                            "featureType" : "water",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#cecece"
                            }]
                    }, {
                            "featureType" : "road.local",
                            "elementType" : "labels.text.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#808080"
                            }]
                    }, {
                            "featureType" : "administrative",
                            "elementType" : "labels.text.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#808080"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#fdfdfd"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "labels.icon",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "water",
                            "elementType" : "labels",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "color" : "#d2d2d2"
                            }]
                    }];		

                    // Retro
                    var metro_style = [{
                            "featureType" : "transit",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi.park",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "color" : "#d3d3d3"
                            }, {
                                    "visibility" : "on"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "geometry.stroke",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "landscape",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#eee8ce"
                            }]
                    }, {
                            "featureType" : "water",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#b8cec9"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "labels.text.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#000000"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "labels.text.stroke",
                            "stylers" : [{
                                    "visibility" : "off"
                            }, {
                                    "color" : "#ffffff"
                            }]
                    }, {
                            "featureType" : "administrative",
                            "elementType" : "labels.text.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#000000"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#ffffff"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "geometry.stroke",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "labels.icon",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "water",
                            "elementType" : "labels",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "color" : "#d3cdab"
                            }]
                    }, {
                            "featureType" : "poi.park",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "color" : "#ced09d"
                            }]
                    }, {
                            "featureType" : "poi",
                            "elementType" : "labels",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }];		

                    // Papiro
                    var old_paper_style = [{
                            "elementType" : "geometry",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#f2e48c"
                            }]
                    }, {
                            "featureType" : "road.highway",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "transit",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi.park",
                            "elementType" : "labels",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi.park",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "color" : "#d3d3d3"
                            }, {
                                    "visibility" : "on"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "geometry.stroke",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "landscape",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#f2e48c"
                            }]
                    }, {
                            "featureType" : "landscape",
                            "elementType" : "geometry.stroke",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#592c00"
                            }]
                    }, {
                            "featureType" : "water",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#a77637"
                            }]
                    }, {
                            "elementType" : "labels.text.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#592c00"
                            }]
                    }, {
                            "elementType" : "labels.text.stroke",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#f2e48c"
                            }]
                    }, {
                            "featureType" : "administrative",
                            "elementType" : "labels.text.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#592c00"
                            }]
                    }, {
                            "featureType" : "administrative",
                            "elementType" : "labels.text.stroke",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#f2e48c"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#a5630f"
                            }]
                    }, {
                            "featureType" : "road.highway",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "visibility" : "on"
                            }, {
                                    "color" : "#592c00"
                            }]
                    }, {
                            "featureType" : "road",
                            "elementType" : "labels.icon",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "water",
                            "elementType" : "labels",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi",
                            "elementType" : "geometry.fill",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }, {
                            "featureType" : "poi",
                            "elementType" : "labels",
                            "stylers" : [{
                                    "visibility" : "off"
                            }]
                    }];

                var greyStyleMap = new google.maps.StyledMapType(greyscale_style, {
                    name: "Greyscale"
                }),
                metroStyleMap = new google.maps.StyledMapType(metro_style, {
                    name: "Metro"
                }),
                oPaperStyleMap = new google.maps.StyledMapType(old_paper_style, {
                    name: "Old Paper"
                }),
                colorfulStyleMap = new google.maps.StyledMapType(colorful_style, {
                    name: "Color"
                });
                
                
                function loadGMapsAttrs(vResCtr, vMapCanvasId) {
                    
                    var jvMapCanvasId = "#" + vMapCanvasId;

                    $mapId = $(jvMapCanvasId);
                    var vZoom = ($mapId.data("gmap-zoom") || 10);
                    var vLat = $mapId.data("gmap-lat");
                    var vLong = $mapId.data("gmap-lng");
                    var vXmlSrc = $mapId.data("gmap-src");

                    var centerLatLng = new google.maps.LatLng(vLat, vLong);
                    var mapOptions = {  zoom: vZoom,
                                        center: centerLatLng,
                                        //disableDefaultUI: true,
                                        //mapTypeId : google.maps.MapTypeId.ROADMAP
                                        mapTypeControlOptions: {mapTypeIds: [google.maps.MapTypeId.TERRAIN, 'colorful_style', 'greyscale_style', 'metro_style', 'old_paper_style']}
                                    };

                    //var bounds = new google.maps.LatLngBounds();
                    var infowindow = new google.maps.InfoWindow();
                    var map = new google.maps.Map(document.getElementById(vMapCanvasId), mapOptions);

                    map.mapTypes.set('colorful_style', colorfulStyleMap);
                    map.mapTypes.set('greyscale_style', greyStyleMap);
                    map.mapTypes.set('metro_style', metroStyleMap);
                    map.mapTypes.set('old_paper_style', oPaperStyleMap);

                    //map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
                    map.setMapTypeId('metro_style');
                                                                
                    $.get(vXmlSrc, function(data) {

                        // create the Bounds object
                        var bounds = new google.maps.LatLngBounds();

                        $(data).find("marker").each(function(){

                            var eachMarker = jQuery(this);
                            //theAddress = eachMarker.find("address").text(),
                            //mygc = new google.maps.Geocoder(theAddress);

                            marker = new google.maps.Marker({
                                        position : new google.maps.LatLng(vLat, vLong),
                                        map : map,
                                        icon : ('../img/gmap/' + eachMarker.find("icon").text()),
                                        scrollwheel : false,
                                        streetViewControl : true,
                                        title : eachMarker.find("name").text()
                            });

                            google.maps.event.addListener(marker, 'click', function() {
                                    var contentString = '<div id="info-map-' + vResCtr +'" style="width:250px; height:85px; padding:0px;"><div>' + '<div style="display:inline-block; float:left;"><h3 class="text-primary" style="margin-top: 0px;">' + eachMarker.find("name").text() + '</h3><b><?php echo getLocaleText('RPT_Q_TCW_MSG_25', TXT_U); ?> :</b><br/>' + eachMarker.find("location").text() + '<br/>' + '</div></div></div>';
                                    infowindow.setContent(contentString);
                                    infowindow.open(map, this);

                                    google.maps.event.addListener(map, 'click', function() {
                                            infowindow.close()
                                            })
                                    });

                        }); // end find marker loop

                    });	// end get data

                } // end loadGMapsAttr
                            
                
                <?php 
                    if ($isListLoaded === TRUE){
                        $resCtr = $resCtr -1;
                        while ($resCtr > 0) {
                            $mapCanvasId = 'map_canvas_' . $resCtr;
                ?>
                            
                            loadGMapsAttrs('<?php echo $resCtr; ?>', '<?php echo $mapCanvasId; ?>');
                
                <?php
                        --$resCtr;
                        }
                    }
                ?>

            };
		
        <?php 
            if ($isListLoaded === TRUE){
        ?>
            $(window).unbind('gMapsLoaded');
            $(window).bind('gMapsLoaded', pagefunction);
            window.loadGoogleMaps();
        <?php
        }
        ?>
        
    });
    
    
    function exportRpt(type){
        
        $.smallBox({
            title: "<?php echo getLocaleText('RPT_Q_TCW_MSG_23', TXT_U); ?>",
            content: "<?php echo getLocaleText('RPT_Q_TCW_MSG_24', TXT_U); ?> ",
            color: "#296191",
            iconSmall: "fa fa-check bounce animated",
            timeout: 10000
        });
        
        document.forms['rptQTCWResForm']['exportType'].value = type;
        document.forms['rptQTCWResForm']['formsubmit'].value = 'rptQTCWResForm';
        $("#rptQTCWResForm").submit();
    
    }
    
    function showReport(orgid, rptid, rptname){
        window.open('showRpt.php?orgId='+orgid+'&rptId='+rptid+'&rptname='+rptname,rptname,'top=60,left=250,width=1250,height=850,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1');
        return false;
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