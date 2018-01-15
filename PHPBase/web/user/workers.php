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
$page_title = getLocaleText('WRKS_MSG_1', TXT_U);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["workers"]["active"] = true;
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
    
    $updateFlag = FALSE;
    
    if ($mode == USERDYNAFIELDS_LIST_MODE_DELETE){
        preInputsInspection($userOrgInputs);
        $updateFlag = $userOrgDao->deleteWorker($userOrgInputs);
    }
    
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

//Load data for Workers list
$loadFlag = $userOrgDao->loadWorkersList($userOrgInputs);
$isListLoaded = FALSE;
$totalWrks = 0;
$tableHeadWrkrDetails = array();
$tableBodyWrkrDetailsEn = array();

if ($loadFlag){

    $tableHeadWrkrDetails = $userOrgDao->getTableHeadWrkrDetails();
    $tableBodyWrkrDetailsEn = $userOrgDao->getTableBodyWrkrDetailsEn();
    $isListLoaded = $userOrgDao->getIsListLoaded();
    $totalWrks = $userOrgDao->getTotalWrks();
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
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-users"></i> <?php echo getLocaleText('WRKS_MSG_2', TXT_U); ?> </h1>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
                <ul id="sparks" class="">
                    <li class="sparks-info">
                        <h5> <?php echo getLocaleText('WRKS_MSG_3', TXT_U); ?> <span class="txt-color-blue" style="text-align: center"><?php echo $totalWrks; ?></span></h5>
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
                                <h2><?php echo getLocaleText('WRKS_MSG_4', TXT_U); ?> </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body no-padding">

                                    <form class="form-horizontal" id="wrkrListForm" name="wrkrListForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                        <input type="hidden" name="wrkrid" id="wrkrid" value=""/>
                                        <input type="hidden" name="mode" id="mode" value=""/>
                                        
                                        <?php 
                                        if ($isListLoaded === FALSE){ ?>
                                            <div class="alert alert-warning fade in">
                                                <button class="close" data-dismiss="alert">
                                                        ×
                                                </button>
                                                <i class="fa-fw fa fa-warning"></i>
                                                <?php echo getLocaleText('WRKS_MSG_5', TXT_U); ?>
                                            </div>
                                        <?php
                                        } 
                                        ?>
                                        <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">
                                            
                                            <thead>
                                                <!--<tr>
                                                    <?php
                                                    foreach ($tableHeadWrkrDetails as $key => $value){ ?>
                                                        <th class="hasinput">
                                                            <input type="text" class="form-control" placeholder="<?php echo $value; ?>" />
                                                        </th>
                                                    <?php
                                                    }
                                                    ?>
                                                    <th class="hasinput" style="width:12%;">

                                                    </th>
                                                </tr>-->
                                                <tr>
                                                    <?php
                                                    $first = TRUE;
                                                    foreach ($tableHeadWrkrDetails as $key => $value){ 
                                                        if ($first) {
                                                            $first = FALSE;
                                                            ?>
                                                            <th data-class="expand"><?php echo $value; ?></th>
                                                        <?php
                                                        }
                                                        else{ ?>
                                                            <th data-hide="phone, tablet"><?php echo $value; ?></th>
                                                        <?php
                                                        }                                                    
                                                    }
                                                    ?>
                                                    <th style="width:12%;"><?php echo getLocaleText('WRKS_MSG_6', TXT_U); ?></th>                                                    
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <?php 
                                                if ($isListLoaded === TRUE){
                                                    foreach ($tableBodyWrkrDetailsEn as $datakey => $fieldIdValue){ 
                                                        $wrkrId = $datakey;
                                                        ?>
                                                        <tr>
                                                            <?php
                                                            foreach ($tableHeadWrkrDetails as $key1 => $value1){ ?>
                                                                <?php
                                                                if (array_key_exists($key1, $fieldIdValue)){ ?>
                                                                    <td style="padding-top: 14px;"><?php echo $fieldIdValue[$key1]; ?></td>
                                                                <?php
                                                                }
                                                                else{ ?>
                                                                    <td style="padding-top: 14px;"></td>
                                                                <?php } 
                                                            } ?>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button class="btn bg-color-greylight txt-color-white btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo getLocaleText('WRKS_MSG_BTN_1', TXT_U); ?> <span class="caret"></span></button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="javascript:actionSubmit('<?php echo USERDYNAFIELDS_LIST_MODE_VIEW; ?>', <?php echo $wrkrId; ?>);"><?php echo getLocaleText('WRKS_MSG_BTN_2', TXT_U); ?></a></li>
                                                                        <li><a href="javascript:actionSubmit('<?php echo USERDYNAFIELDS_LIST_MODE_EDIT; ?>', <?php echo $wrkrId; ?>);"><?php echo getLocaleText('WRKS_MSG_BTN_3', TXT_U); ?></a></li>
                                                                        <li><a href="javascript:actionSubmit('<?php echo USERDYNAFIELDS_LIST_MODE_DELETE; ?>', <?php echo $wrkrId; ?>);"><?php echo getLocaleText('WRKS_MSG_BTN_4', TXT_U); ?></a></li>
                                                                    </ul>
                                                                </div>
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
                <h5><?php echo getLocaleText('WRKS_MSG_7', TXT_U); ?></h5>
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
        
        $("div.toolbar").html('<div class="text-right"><a href="javascript:actionSubmit(\'<?php echo USERDYNAFIELDS_LIST_MODE_NEW; ?>\', \'\');" class="btn btn-primary" style="margin-bottom: 6px;"><i class="fa fa-plus"></i> <span class="hidden-mobile"><?php echo getLocaleText('WRKS_MSG_8', TXT_U); ?></span></a></div>');
        $("div.toolbar").attr('style', 'display: inline-block;float:right;margin-left:30px');
        
    });
    
    var dialogWrkrId;
    
    $('#deleteConf').dialog({
            autoOpen : false,
            width : 600,
            resizable : false,
            modal : true,
            //title : "<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> On Delete Confirmation!</h4></div>",
            buttons : [{
                    html : "<i class='fa fa-trash-o'></i>&nbsp; <?php echo getLocaleText('WRKS_MSG_9', TXT_U); ?>",
                    "class" : "btn btn-danger",
                    click : function() {
                            document.getElementById('mode').value = '<?php echo USERDYNAFIELDS_LIST_MODE_DELETE; ?>';
                            document.getElementById('wrkrid').value = dialogWrkrId;
                            document.getElementById('formsubmit').value = 'wrkrListForm';
                            dialogWrkrId = null;
                            $("#wrkrListForm").submit();
                    }
            }, {
                    html : "<i class='fa fa-times'></i>&nbsp; <?php echo getLocaleText('WRKS_MSG_10', TXT_U); ?>",
                    "class" : "btn btn-default",
                    click : function() {
                            $(this).dialog("close");
                    }
            }],
            open: function(event, ui){
                    $(this).parent().find('.ui-dialog-title').append("<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> <?php echo getLocaleText('WRKS_MSG_11', TXT_U); ?></h4></div>");
                },
            close: function(event, ui){
                    $(this).parent().find('.widget-header').remove();
                }
    });
    

    function actionSubmit(command, wrkrid){
        
        switch (command){
            
            case '<?php echo USERDYNAFIELDS_LIST_MODE_NEW ?>':
                document.getElementById('mode').value = '<?php echo USERDYNAFIELDS_LIST_MODE_NEW; ?>';
                document.getElementById('formsubmit').value = 'wrkrListForm';
                document.forms['wrkrListForm'].action = 'newWrkr.php';
                $("#wrkrListForm").submit();
                break;
            
            case '<?php echo USERDYNAFIELDS_LIST_MODE_VIEW ?>':
                document.getElementById('mode').value = '<?php echo USERDYNAFIELDS_LIST_MODE_VIEW; ?>';
                document.getElementById('wrkrid').value = wrkrid;
                document.getElementById('formsubmit').value = 'wrkrListForm';
                document.forms['wrkrListForm'].action = 'viewWrkr.php';
                $("#wrkrListForm").submit();
                break;
                
            case '<?php echo USERDYNAFIELDS_LIST_MODE_EDIT; ?>':
                document.getElementById('mode').value = '<?php echo USERDYNAFIELDS_LIST_MODE_EDIT; ?>';
                document.getElementById('wrkrid').value = wrkrid;
                document.getElementById('formsubmit').value = 'wrkrListForm';
                document.forms['wrkrListForm'].action = 'newWrkr.php';
                $("#wrkrListForm").submit();
                break;
                
            case '<?php echo USERDYNAFIELDS_LIST_MODE_DELETE; ?>':
                        
                dialogWrkrId = wrkrid;
        
                $('#deleteConf').dialog('open');
                        
//                $.SmartMessageBox({
//                        title : "On Delete Confirmation!",
//                        content : "Are you sure want to remove this Worker from the records.",
//                        buttons : '[No][Yes]'
//                }, function(ButtonPressed) {
//                        if (ButtonPressed === "Yes") {
//                            document.getElementById('mode').value = '<?php echo USERDYNAFIELDS_LIST_MODE_DELETE; ?>';
//                            document.getElementById('wrkrid').value = wrkrid;
//                            document.getElementById('formsubmit').value = 'wrkrListForm';
//                            $("#wrkrListForm").submit();
//                        }
//                        if (ButtonPressed === "No") {
//
//                        }
//                });
                break;
                
        }
    }

</script>


<?php

    function preInputsInspection(UserOrgInputs $userOrgInputs){
        
        $userOrgInputs->setIdwrks(mysql_real_escape_string($_POST['wrkrid']));

    }
    
?>