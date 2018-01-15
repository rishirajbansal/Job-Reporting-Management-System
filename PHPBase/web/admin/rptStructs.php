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
$page_title = getLocaleText('RPT_STRUCT_MSG_1', TXT_A);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["reporting"]["active"] = true;
$page_nav["reporting"]["sub"]["rptStructconfig"]["active"] = true;
include("inc/nav.php");


/*------------- Form Submissions ---------*/

$orgInputs = new OrgInputs();
$orgDao = new OrgDao();


$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$orgDetails = new Organization();


if (isset($_POST['formsubmit'])){
    $mode = $_POST['mode'];
    
    $updateFlag = FALSE;
    
    if ($mode == ORG_RPT_STRUCT_LIST_MODE_DELETE){
        preInputsInspection($orgInputs);
        $updateFlag = $orgDao->deleteOrgRptStruct($orgInputs);
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

}

//Load data for Orgnaization list
$loadFlag = $orgDao->loadOrgList(ORG_LIST_RPTSTRUCT);
$isListLoaded = FALSE;
$totalOrgs = 0;
$listOrgs = array();

if ($loadFlag){

    $listOrgs = $orgDao->getListOrgs();
    $isListLoaded = $orgDao->getIsListLoaded();
    $totalOrgs = $orgDao->getTotalOrgs();
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

/*------------- End Form Submissions ---------*/

?>


<!-- MAIN PANEL -->
<div id="main" role="main">
    
    <?php
        $breadcrumbs[getLocaleText('RPT_STRUCT_MSG_2', TXT_A)] = "";
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-cubes"></i><?php echo getLocaleText('RPT_STRUCT_MSG_3', TXT_A); ?> </h1>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
                <ul id="sparks" class="">
                    <li class="sparks-info">
                        <h5> <?php echo getLocaleText('RPT_STRUCT_MSG_4', TXT_A); ?> <span class="txt-color-blue" style="text-align: center"><?php echo $totalOrgs; ?></span></h5>
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
                                <h2><?php echo getLocaleText('RPT_STRUCT_MSG_5', TXT_A); ?> </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body no-padding">

                                    <form class="form-horizontal" id="rptStructsListForm" name="rptStructsListForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                        <input type="hidden" name="orgid" id="orgid" value=""/>
                                        <input type="hidden" name="mode" id="mode" value=""/>
                                        
                                        <?php 
                                        if ($isListLoaded === FALSE){ ?>
                                            <div class="alert alert-warning fade in">
                                                <button class="close" data-dismiss="alert">
                                                        ×
                                                </button>
                                                <i class="fa-fw fa fa-warning"></i>
                                                <?php echo getLocaleText('RPT_STRUCT_MSG_6', TXT_A); ?> <a href="newOrg.php" class="btn btn-xs txt-color-white" style="background-color: #bcae81;margin-top: -5px"><?php echo getLocaleText('RPT_STRUCT_MSG_7-1', TXT_A); ?></a> <?php echo getLocaleText('RPT_STRUCT_MSG_7-2', TXT_A); ?>
                                            </div>
                                        <?php
                                        } 
                                        ?>
                                        <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">
                                            
                                            <thead>
                                                <tr>
                                                    <th class="hasinput" style="width:40%">
                                                        <input type="text" class="form-control" placeholder="<?php echo getLocaleText('RPT_STRUCT_MSG_8', TXT_A); ?>" />
                                                    <th class="hasinput" >
                                                        <input type="text" class="form-control" placeholder="<?php echo getLocaleText('RPT_STRUCT_MSG_9', TXT_A); ?>" />
                                                    </th>
                                                    <th class="hasinput" style="width:30%">

                                                    </th>

                                                </tr>
                                                <tr>
                                                    <th data-class="expand"><?php echo getLocaleText('RPT_STRUCT_MSG_8', TXT_A); ?></th>
                                                    <th data-hide="phone, tablet"><?php echo getLocaleText('RPT_STRUCT_MSG_9', TXT_A); ?></th>
                                                    <th>Commands</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <?php 
                                                if ($isListLoaded === TRUE){
                                                    foreach ($listOrgs as $org){ ?>
                                                        <tr>
                                                            <td style="padding-top: 14px;"><?php echo $org->getName(); ?></td>
                                                            <?php
                                                            if ($org->getIsRptStructConfigured()){ ?>
                                                                <td style="padding-top: 14px;"><span class="label label-primary bounceIn animated" style="font-weight: normal;font-size: 90%;"><?php echo getLocaleText('RPT_STRUCT_MSG_10', TXT_A); ?></span></td>
                                                            <?php
                                                            }
                                                            else{ ?>
                                                                <td style="padding-top: 14px;"><span class="label label-warning bounceIn animated" style="font-weight: normal;font-size: 90%;"><?php echo getLocaleText('RPT_STRUCT_MSG_11', TXT_A); ?></span></td>
                                                            <?php
                                                            }
                                                            ?>
                                                            
                                                            <td style="text-align: center;">
                                                                <div>
                                                                    <?php
                                                                    if ($org->getIsRptStructConfigured()){ ?>
                                                                        <button type="button" class="btn btn-sm btn-primary" id="update" style="" onclick="javascript:actionSubmit('<?php echo ORG_RPT_STRUCT_LIST_MODE_EDIT; ?>', <?php echo $org->getIdorgs(); ?>);">&nbsp;&nbsp;<i class="fa fa-edit"></i> &nbsp;<?php echo getLocaleText('RPT_STRUCT_MSG_BTN_1', TXT_A); ?>&nbsp;&nbsp;</button>
                                                                        &nbsp;&nbsp;
                                                                        <button type="button" class="btn btn-sm btn-danger" id="delete" style="" onclick="javascript:actionSubmit('<?php echo ORG_RPT_STRUCT_LIST_MODE_DELETE; ?>', <?php echo $org->getIdorgs(); ?>);">&nbsp;&nbsp;<i class="fa fa-trash-o"></i> &nbsp;<?php echo getLocaleText('RPT_STRUCT_MSG_BTN_2', TXT_A); ?>&nbsp;&nbsp;</button>
                                                                    <?php
                                                                    }
                                                                    else { ?>
                                                                        <button type="button" class="btn btn-sm btn-success btn-block" id="newTmpl" style="font-size: 14px" onclick="javascript:actionSubmit('<?php echo ORG_RPT_STRUCT_LIST_MODE_NEW; ?>', <?php echo $org->getIdorgs(); ?>);">&nbsp;&nbsp;<i class="fa fa-file-text"></i> &nbsp;<?php echo getLocaleText('RPT_STRUCT_MSG_BTN_3', TXT_A); ?>&nbsp;&nbsp;</button>
                                                                    <?php
                                                                    }
                                                                    ?>
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
                <h5><?php echo getLocaleText('RPT_STRUCT_MSG_12', TXT_A); ?></h5>
                <br/>
            </div>
        
        <?php
        }
        else if (empty ($userOrgDao->getMsgType())) { ?>
            <div class="alert alert-danger fade in">
                <i class="fa-fw fa fa-times"></i>
                <?php echo getLocaleText('RPT_STRUCT_MSG_13', TXT_A); ?>
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
                    "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
                                    "t"+
                                    "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
                    "autoWidth" : true,
                    "preDrawCallback" : function() {
                            // Initialize the responsive datatables helper once.
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

        $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

            otable
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();

        } );

    });
    
    var dialogOrgId;
    
    $('#deleteConf').dialog({
            autoOpen : false,
            width : 600,
            resizable : false,
            modal : true,
            //title : "<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> On Delete Confirmation!</h4></div>",
            buttons : [{
                    html : "<i class='fa fa-trash-o'></i>&nbsp; <?php echo getLocaleText('RPT_STRUCT_MSG_14', TXT_A); ?>",
                    "class" : "btn btn-danger",
                    click : function() {
                            document.getElementById('mode').value = '<?php echo ORG_RPT_STRUCT_LIST_MODE_DELETE; ?>';
                            document.getElementById('orgid').value = dialogOrgId;
                            document.getElementById('formsubmit').value = 'rptStructsListForm';
                            dialogOrgId = null;
                            $("#rptStructsListForm").submit();
                    }
            }, {
                    html : "<i class='fa fa-times'></i>&nbsp; <?php echo getLocaleText('RPT_STRUCT_MSG_15', TXT_A); ?>",
                    "class" : "btn btn-default",
                    click : function() {
                            $(this).dialog("close");
                    }
            }],
            open: function(event, ui){
                    $(this).parent().find('.ui-dialog-title').append("<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> On Delete Confirmation !</h4></div>");
                },
            close: function(event, ui){
                    $(this).parent().find('.widget-header').remove();
                }
    });
    
    
    function actionSubmit(command, orgid){
        
        switch (command){
            
            case '<?php echo ORG_RPT_STRUCT_LIST_MODE_NEW ?>':
                document.getElementById('mode').value = '<?php echo ORG_RPT_STRUCT_LIST_MODE_NEW; ?>';
                document.getElementById('orgid').value = orgid;
                document.getElementById('formsubmit').value = 'rptStructsListForm';
                document.forms['rptStructsListForm'].action = 'newRptStruct.php';
                $("#rptStructsListForm").submit();
                break;
                
            case '<?php echo ORG_RPT_STRUCT_LIST_MODE_EDIT; ?>':
                document.getElementById('mode').value = '<?php echo ORG_RPT_STRUCT_LIST_MODE_EDIT; ?>';
                document.getElementById('orgid').value = orgid;
                document.getElementById('formsubmit').value = 'rptStructsListForm';
                document.forms['rptStructsListForm'].action = 'newRptStruct.php';
                $("#rptStructsListForm").submit();
                break;
                
            case '<?php echo ORG_RPT_STRUCT_LIST_MODE_DELETE; ?>':
                        
                dialogOrgId = orgid;
                        
                $('#deleteConf').dialog('open');
                        
//                $.SmartMessageBox({
//                        title : "On Delete Confirmation!",
//                        content : "Are you sure want to delete the Reporting Structure for this organization.",
//                        buttons : '[No][Yes]'
//                }, function(ButtonPressed) {
//                        if (ButtonPressed === "Yes") {
//                            document.getElementById('mode').value = '<?php echo ORG_RPT_STRUCT_LIST_MODE_DELETE; ?>';
//                            document.getElementById('orgid').value = orgid;
//                            document.getElementById('formsubmit').value = 'rptStructsListForm';
//                            $("#rptStructsListForm").submit();
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

    function preInputsInspection(orgInputs $orgInputs){
        
        $orgInputs->setIdorgs(mysql_real_escape_string($_POST['orgid']));

    }

?>