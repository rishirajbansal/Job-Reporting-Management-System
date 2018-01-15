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


ob_start();
/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('DISP_RPTS_MSG_1', TXT_U);
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

$orgid = $_SESSION['orgid'];
$userOrgInputs->setIdorgs($orgid);


if (isset($_POST['formsubmit'])){
    
    $mode = $_POST['mode'];
    
    if ($mode == ORG_USERRPT_LIST_REPORT_EXPORT){
        header('location: showRpt.php?orgId=' . $orgid . '&rptId=' . $_POST['rptid'] . '&mode=' . $mode);
    }
    else{
        $updateFlag = FALSE;
    
        if ($mode == ORG_USERRPT_LIST_MODE_DELETE){
            preInputsInspection($userOrgInputs);
            $updateFlag = $userOrgDao->deleteReport($userOrgInputs);
            
            if ($updateFlag){
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
        }
        
    }
    
}


//Load Reports
$loadFlag = $userOrgDao->loadReportsList($userOrgInputs);
$isListLoaded = FALSE;
$totalRpts = 0;
$listRpts = array();

if ($loadFlag){
    $isListLoaded = $userOrgDao->getIsListLoaded();
    $totalRpts = $userOrgDao->getTotalRpts();
    $listRpts = $userOrgDao->getListRpts();
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

/*------------- End Form Submissions ---------*/

?>


<!-- MAIN PANEL -->
<div id="main" role="main">
    
    <?php
        //$breadcrumbs["Reporting Management"] = "";
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-bar-chart-o"></i> <?php echo getLocaleText('DISP_RPTS_MSG_2', TXT_U); ?> </h1>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <ul id="sparks" class="">
                    <li class="sparks-info">
                        <h5> <?php echo getLocaleText('DISP_RPTS_MSG_3', TXT_U); ?> <span class="txt-color-blue" style="text-align: center"><?php echo $totalRpts; ?></span></h5>
                    </li>
                </ul>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <?php
        
        if ($loadFlag) { ?>
        
            <section id="widget-grid" class="">

                <div class="row">

                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">

                            <header>
                                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                <h2><?php echo getLocaleText('DISP_RPTS_MSG_4', TXT_U); ?> </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body no-padding">

                                    <form class="form-horizontal" id="rptListForm" name="rptListForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                        <input type="hidden" name="rptid" id="rptid" value=""/>
                                        <input type="hidden" name="mode" id="mode" value=""/>
                                        
                                        <?php 
                                        if ($isListLoaded === FALSE){ ?>
                                            <div class="alert alert-warning fade in">
                                                <button class="close" data-dismiss="alert">
                                                        ×
                                                </button>
                                                <i class="fa-fw fa fa-warning"></i>
                                                <?php echo getLocaleText('DISP_RPTS_MSG_5', TXT_U); ?>
                                            </div>
                                        <?php
                                        } 
                                        ?>
                                        <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">
                                            
                                            <thead>
                                                
                                                <tr>
                                                    <th data-class="expand" style="width:8%"><?php echo getLocaleText('DISP_RPTS_MSG_6', TXT_U); ?></th>
                                                    <th style="width:15%"><?php echo getLocaleText('DISP_RPTS_MSG_7', TXT_U); ?></th>
                                                    <th data-hide="phone, tablet" style="width:15%"><?php echo getLocaleText('DISP_RPTS_MSG_8', TXT_U); ?></th>
                                                    <th data-hide="phone,tablet"><?php echo getLocaleText('DISP_RPTS_MSG_9', TXT_U); ?></th>
                                                    <th data-hide="phone,tablet"><?php echo getLocaleText('DISP_RPTS_MSG_10', TXT_U); ?></th>
                                                    <th style="width:30%"><?php echo getLocaleText('DISP_RPTS_MSG_11', TXT_U); ?></th>
                                                </tr>
                                                
                                            </thead>
                                            
                                            <tbody>
                                                <?php 
                                                if ($isListLoaded === TRUE){
                                                    foreach ($listRpts as $rpt){ ?>
                                                        <tr>
                                                            <td style="padding-top: 14px;"><?php echo $rpt->getRptNo(); ?></td>
                                                            <td style="padding-top: 14px;"><?php echo $rpt->getClientname(); ?></td>
                                                            <td style="padding-top: 14px;"><?php echo $rpt->getSubBy(); ?></td>
                                                            <td style="padding-top: 14px;"><?php echo $rpt->getSubDatetime(); ?></td>
                                                            <td style="padding-top: 14px;"><?php echo $rpt->getLocation(); ?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button class="btn btn-primary" style="min-width: 100px"><i class="fa fa-gear"></i>&nbsp; <?php echo getLocaleText('DISP_RPTS_MSG_BTN_1', TXT_U); ?> </button>
                                                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="javascript:actionSubmit('<?php echo ORG_USERRPT_LIST_MODE_VIEW; ?>', <?php echo $rpt->getIdOrgRpts(); ?>);"><?php echo getLocaleText('DISP_RPTS_MSG_BTN_2', TXT_U); ?></a></li>
                                                                        <li><a href="javascript:actionSubmit('<?php echo ORG_USERRPT_LIST_MODE_EDIT; ?>', <?php echo $rpt->getIdOrgRpts(); ?>);"><?php echo getLocaleText('DISP_RPTS_MSG_BTN_3', TXT_U); ?> </a></li>
                                                                        <li class="divider"></li>
                                                                        <li><a href="javascript:actionSubmit('<?php echo ORG_USERRPT_LIST_MODE_DELETE; ?>', <?php echo $rpt->getIdOrgRpts(); ?>);"><?php echo getLocaleText('DISP_RPTS_MSG_BTN_4', TXT_U); ?></a></li>
                                                                    </ul>
                                                                </div>&nbsp;&nbsp;&nbsp;
                                                                <button type="button" class="btn btn-success" id="btnExport" style="min-width: 100px" onclick="javascript:exportReport(<?php echo $rpt->getIdorgs(); ?>, <?php echo $rpt->getIdOrgRpts(); ?>);">
                                                                    <i class="fa fa-download"></i> &nbsp; <?php echo getLocaleText('DISP_RPTS_MSG_BTN_5', TXT_U); ?>
                                                                </button>&nbsp;&nbsp;&nbsp;
                                                                <button type="button" class="btn btn-default bg-color-pinkDark txt-color-white" id="btnShow" style="min-width: 100px" onclick="javascript:showReport(<?php echo $rpt->getIdorgs(); ?>, <?php echo $rpt->getIdOrgRpts(); ?>);">
                                                                    <i class="fa fa-file-pdf-o"></i> &nbsp; <?php echo getLocaleText('DISP_RPTS_MSG_BTN_6', TXT_U); ?>
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

                </div>

            </section>
        
            <div id="deleteConf" title="">
                <br/>
                <h5><?php echo getLocaleText('DISP_RPTS_MSG_12', TXT_U); ?></h5>
                <br/>
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


<script>

    $(document).ready(function() {
        
        pageSetUp();
        
        var responsiveHelper_dt_basic = undefined;
        var responsiveHelper_datatable_fixed_column = undefined;
        var responsiveHelper_datatable_col_reorder = undefined;
        var responsiveHelper_datatable_tabletools = undefined;

        var breakpointDefinition = {
                tablet : 1024,
                phone : 480
        };
        
        var otable = $('#datatable_fixed_column').DataTable({
                    "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>C>r>"+
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
        
        $("div.toolbar").html('<div class="text-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>');
        $("div.toolbar").attr('style', 'display: inline-block;float:right;margin-left:85px');

        
    });
    
    var dialogRptId;
    
    $('#deleteConf').dialog({
            autoOpen : false,
            width : 600,
            resizable : false,
            modal : true,
            //title : "<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> On Delete Confirmation!</h4></div>",
            buttons : [{
                    html : "<i class='fa fa-trash-o'></i>&nbsp; <?php echo getLocaleText('DISP_RPTS_MSG_13', TXT_U); ?>",
                    "class" : "btn btn-danger",
                    click : function() {
                            document.getElementById('mode').value = '<?php echo ORG_USERRPT_LIST_MODE_DELETE; ?>';
                            document.getElementById('rptid').value = dialogRptId;
                            document.getElementById('formsubmit').value = 'rptListForm';
                            dialogRptId = null;
                            $("#rptListForm").submit();
                    }
            }, {
                    html : "<i class='fa fa-times'></i>&nbsp; <?php echo getLocaleText('DISP_RPTS_MSG_14', TXT_U); ?>",
                    "class" : "btn btn-default",
                    click : function() {
                            $(this).dialog("close");
                    }
            }],
            open: function(event, ui){
                    $(this).parent().find('.ui-dialog-title').append("<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> <?php echo getLocaleText('DISP_RPTS_MSG_15', TXT_U); ?></h4></div>");
                },
            close: function(event, ui){
                    $(this).parent().find('.widget-header').remove();
                }
    });
    
    function actionSubmit(command, rptid){
        
        switch (command){
            
            case '<?php echo ORG_USERRPT_LIST_MODE_VIEW ?>':
                document.getElementById('mode').value = '<?php echo ORG_USERRPT_LIST_MODE_VIEW; ?>';
                document.getElementById('rptid').value = rptid;
                document.getElementById('formsubmit').value = 'rptListForm';
                document.forms['rptListForm'].action = 'viewRpt.php';
                $("#rptListForm").submit();
                break;
                
            case '<?php echo ORG_USERRPT_LIST_MODE_EDIT; ?>':
                document.getElementById('mode').value = '<?php echo ORG_USERRPT_LIST_MODE_EDIT; ?>';
                document.getElementById('rptid').value = rptid;
                document.getElementById('formsubmit').value = 'rptListForm';
                document.forms['rptListForm'].action = 'editRpt.php';
                $("#rptListForm").submit();
                break;
                
            case '<?php echo ORG_USERRPT_LIST_MODE_DELETE; ?>':
                        
                dialogRptId = rptid;
                        
                $('#deleteConf').dialog('open');
                        
//                $.SmartMessageBox({
//                        title : "On Delete Confirmation!",
//                        content : "Are you sure want to delete this Report from the records.",
//                        buttons : '[No][Yes]'
//                }, function(ButtonPressed) {
//                        if (ButtonPressed === "Yes") {
//                            document.getElementById('mode').value = '<?php echo ORG_USERRPT_LIST_MODE_DELETE; ?>';
//                            document.getElementById('rptid').value = rptid;
//                            document.getElementById('formsubmit').value = 'rptListForm';
//                            $("#rptListForm").submit();
//                        }
//                        if (ButtonPressed === "No") {
//
//                        }
//                });
                break;
                
        }
    }
    
    function showReport(orgid, rptid){
        window.open('showRpt.php?orgId='+orgid+'&rptId='+rptid+'&mode='+'<?php echo ORG_USERRPT_LIST_REPORT_SHOW;?>','','top=60,left=250,width=1250,height=850,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1');
        return false;
    }
    
    function exportReport(orgid, rptid){
        document.getElementById('mode').value = '<?php echo ORG_USERRPT_LIST_REPORT_EXPORT; ?>';
        document.getElementById('rptid').value = rptid;
        $("#rptListForm").submit();
    }
    


</script>


<?php

    function preInputsInspection(UserOrgInputs $userOrgInputs){
        
        $userOrgInputs->setIdrpts(mysql_real_escape_string($_POST['rptid']));

    }
    
    ob_end_flush();
    
?>