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
$page_title = getLocaleText('ORG_MSG_1', TXT_A);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["organizations"]["active"] = true;
include("inc/nav.php");

/*------------- Form Submissions ---------*/

$orgInputs = new OrgInputs();
$orgDao = new OrgDao();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$orgDetails = new Organization();


if (isset($_POST['formsubmit'])){
    $mode = $_POST['mode'];
    
    if ($mode == ORG_LIST_MODE_ACTIVATE){
        preInputsInspection($orgInputs, ORG_LIST_MODE_ACTIVATE);
        $updateFlag = $orgDao->actDeactOrg($orgInputs);
    }
    else if ($mode == ORG_LIST_MODE_DEACTIVATE){
        preInputsInspection($orgInputs, ORG_LIST_MODE_DEACTIVATE);
        $updateFlag = $orgDao->actDeactOrg($orgInputs);
    }
    else if ($mode == ORG_LIST_MODE_DELETE){
        preInputsInspection($orgInputs, ORG_LIST_MODE_DELETE);
        $updateFlag = $orgDao->deleteOrg($orgInputs);
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
$loadFlag = $orgDao->loadOrgList(ORG_LIST_DEFAULT);
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
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-building"></i><i class="fa-fw fa fa-building" style="margin-left: -20px;"></i><?php echo getLocaleText('ORG_MSG_2', TXT_A); ?>  </h1>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
                <ul id="sparks" class="">
                    <li class="sparks-info">
                        <h5> <?php echo getLocaleText('ORG_MSG_3', TXT_A); ?> <span class="txt-color-blue" style="text-align: center"><?php echo $totalOrgs; ?></span></h5>
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
                                <h2> <?php echo getLocaleText('ORG_MSG_4', TXT_A); ?></h2>
                            </header>
                            
                            <!-- widget div-->
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body no-padding">
                                    
                                    <form class="form-horizontal" id="orgListForm" name="orgListForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                        <input type="hidden" name="orgid" id="orgid" value=""/>
                                        <input type="hidden" name="mode" id="mode" value=""/>
                                        <input type="hidden" name="orgname" id="orgname" value=""/>
                                        <input type="hidden" name="actdeact" id="actdeact" value=""/>
                                        
                                        <?php 
                                        if ($isListLoaded === FALSE){ ?>
                                            <div class="alert alert-warning fade in">
                                                <button class="close" data-dismiss="alert">
                                                        ×
                                                </button>
                                                <i class="fa-fw fa fa-warning"></i>
                                                <?php echo getLocaleText('ORG_MSG_5', TXT_A); ?>
                                            </div>
                                        <?php
                                        } 
                                        ?>
                                        <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">
                                            
                                            <thead>
                                                <tr>
                                                    <th class="hasinput" style="width:25%">
                                                        <input type="text" class="form-control" placeholder="<?php echo getLocaleText('ORG_MSG_6', TXT_A); ?>" />
                                                    </th>
                                                    <th class="hasinput" style="width:12%">
                                                        <input type="text" class="form-control" placeholder="<?php echo getLocaleText('ORG_MSG_7', TXT_A); ?>" />
                                                    </th>
                                                    <th class="hasinput">
                                                        <input type="text" class="form-control" placeholder="<?php echo getLocaleText('ORG_MSG_8', TXT_A); ?>" />
                                                    </th>
                                                    <th class="hasinput" style="width:20%">
                                                        <input type="text" class="form-control" placeholder="<?php echo getLocaleText('ORG_MSG_9', TXT_A); ?>" />
                                                    </th>
                                                    <th class="hasinput" style="width:12%;">

                                                    </th>

                                                </tr>
                                                <tr>
                                                    <th data-class="expand"><?php echo getLocaleText('ORG_MSG_6', TXT_A); ?></th>
                                                    <th ><?php echo getLocaleText('ORG_MSG_7', TXT_A); ?></th>
                                                    <th data-hide="phone, tablet"><?php echo getLocaleText('ORG_MSG_8', TXT_A); ?></th>
                                                    <th data-hide="phone,tablet"><?php echo getLocaleText('ORG_MSG_9', TXT_A); ?></th>
                                                    <th><?php echo getLocaleText('ORG_MSG_10', TXT_A); ?></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                
                                                <?php 
                                                if ($isListLoaded === TRUE){
                                                    foreach ($listOrgs as $org){ ?>
                                                        <tr>
                                                            <td style="padding-top: 14px;"><?php echo $org->getName(); ?><?php if ($org->getActivated() == ORG_DEACTIVATED){ ?> &nbsp;&nbsp;<sup class="badge bg-color-orange bounceIn animated" style="font-weight: normal;font-size: 90%;"><?php echo getLocaleText('ORG_MSG_11', TXT_A); ?></sup><?php } ?></td>
                                                            <td style="padding-top: 14px;"><?php echo $org->getPhone(); ?></td>
                                                            <td style="padding-top: 14px;"><?php echo $org->getEmail(); ?></td>
                                                            <td style="padding-top: 14px;"><?php echo $org->getUsername(); ?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button class="btn bg-color-greylight txt-color-white btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo getLocaleText('ORG_MSG_BTN_6', TXT_A); ?> <span class="caret"></span></button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a href="javascript:actionSubmit('<?php echo ORG_LIST_MODE_VIEW; ?>', <?php echo $org->getIdorgs(); ?>, '<?php echo $org->getName(); ?>');"><?php echo getLocaleText('ORG_MSG_BTN_1', TXT_A); ?></a></li>
                                                                        <li><a href="javascript:actionSubmit('<?php echo ORG_LIST_MODE_EDIT; ?>', <?php echo $org->getIdorgs(); ?>, '<?php echo $org->getName(); ?>');"><?php echo getLocaleText('ORG_MSG_BTN_2', TXT_A); ?></a></li>
                                                                        <?php if ($org->getActivated() == ORG_DEACTIVATED){ ?>
                                                                            <li><a href="javascript:actionSubmit('<?php echo ORG_LIST_MODE_ACTIVATE; ?>', <?php echo $org->getIdorgs(); ?>, '<?php echo $org->getName(); ?>');"><?php echo getLocaleText('ORG_MSG_BTN_3', TXT_A); ?></a></li>
                                                                        <?php 
                                                                        }
                                                                        else { ?>
                                                                            <li><a href="javascript:actionSubmit('<?php echo ORG_LIST_MODE_DEACTIVATE; ?>', <?php echo $org->getIdorgs(); ?>, '<?php echo $org->getName(); ?>');"><?php echo getLocaleText('ORG_MSG_BTN_4', TXT_A); ?></a></li>
                                                                        <?php 
                                                                        }
                                                                        ?>
                                                                        <li><a href="javascript:actionSubmit('<?php echo ORG_LIST_MODE_DELETE; ?>', <?php echo $org->getIdorgs(); ?>, '<?php echo $org->getName(); ?>');"><?php echo getLocaleText('ORG_MSG_BTN_5', TXT_A); ?></a></li>
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
                <h5><?php echo getLocaleText('ORG_MSG_12', TXT_A); ?></h5>
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

        /* BASIC ;*/
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
                    //"bInfo": false,
                    //"bLengthChange": false
                    //"bAutoWidth": false,
                    //"bPaginate": false,
                    //"bStateSave": true // saves sort state using localStorage
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

        //$("div.toolbar").html('<div class="text-right"><img src="../img/misc/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
        $("div.toolbar").html('<div class="text-right"><a href="javascript:newOrg();" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-mobile"><?php echo getLocaleText("ORG_MSG_13", TXT_A); ?></span></a></div>');

        $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

            otable
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();

        } );
        

    });
    
    var dialogOrgId;
    var dialogOrgName;
    
    $('#deleteConf').dialog({
            autoOpen : false,
            width : 600,
            resizable : false,
            modal : true,
            //title : "<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> On Delete Confirmation!</h4></div>",
            buttons : [{
                    html : "<i class='fa fa-trash-o'></i>&nbsp; <?php echo getLocaleText('ORG_MSG_15', TXT_A); ?>",
                    "class" : "btn btn-danger",
                    click : function() {
                            document.getElementById('mode').value = '<?php echo ORG_LIST_MODE_DELETE; ?>';
                            document.getElementById('orgid').value = dialogOrgId;
                            document.getElementById('orgname').value = dialogOrgName;
                            document.getElementById('formsubmit').value = 'listorgsubmit';
                            dialogOrgId = null;
                            dialogOrgName = null;
                            $("#orgListForm").submit();
                    }
            }, {
                    html : "<i class='fa fa-times'></i>&nbsp; <?php echo getLocaleText('ORG_MSG_16', TXT_A); ?>",
                    "class" : "btn btn-default",
                    click : function() {
                            $(this).dialog("close");
                    }
            }],
            open: function(event, ui){
                    $(this).parent().find('.ui-dialog-title').append("<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> <?php echo getLocaleText("ORG_MSG_14", TXT_A); ?></h4></div>");
                },
            close: function(event, ui){
                    $(this).parent().find('.widget-header').remove();
                }
    });
    

    function newOrg(){
        location.href = "newOrg.php";
    }
    
    function actionSubmit(command, orgid, orgname){
        
        switch (command){
            
            case '<?php echo ORG_LIST_MODE_VIEW; ?>':
                document.getElementById('mode').value = '<?php echo ORG_LIST_MODE_VIEW; ?>';
                document.getElementById('orgid').value = orgid;
                document.getElementById('orgname').value = orgname;
                document.getElementById('formsubmit').value = 'listorgsubmit';
                document.forms['orgListForm'].action = 'viewOrg.php';
                $("#orgListForm").submit();
                break;
                
            case '<?php echo ORG_LIST_MODE_EDIT; ?>':
                document.getElementById('mode').value = '<?php echo ORG_LIST_MODE_EDIT; ?>';
                document.getElementById('orgid').value = orgid;
                document.getElementById('orgname').value = orgname;
                document.getElementById('formsubmit').value = 'listorgsubmit';
                document.forms['orgListForm'].action = 'editOrg.php';
                $("#orgListForm").submit();
                break;
                
            case '<?php echo ORG_LIST_MODE_ACTIVATE; ?>':
                document.getElementById('mode').value = '<?php echo ORG_LIST_MODE_ACTIVATE; ?>';
                document.getElementById('orgid').value = orgid;
                document.getElementById('orgname').value = orgname;
                document.getElementById('actdeact').value = <?php echo ORG_ACTIVATED; ?>;
                document.getElementById('formsubmit').value = 'listorgsubmit';
                $("#orgListForm").submit();
                break;
                
            case '<?php echo ORG_LIST_MODE_DEACTIVATE; ?>':
                document.getElementById('mode').value = '<?php echo ORG_LIST_MODE_DEACTIVATE; ?>';
                document.getElementById('orgid').value = orgid;
                document.getElementById('orgname').value = orgname;
                document.getElementById('actdeact').value = <?php echo ORG_DEACTIVATED; ?>;
                document.getElementById('formsubmit').value = 'listorgsubmit';
                $("#orgListForm").submit();
                break;
                
            case '<?php echo ORG_LIST_MODE_DELETE; ?>':
                        
                dialogOrgId = orgid;
                dialogOrgName = orgname;
                        
                $('#deleteConf').dialog('open');
                        
//                $.SmartMessageBox({
//                        title : "On Delete Confirmation!",
//                        content : "Are you sure want to delete this organization from the system, this operation cannot be undone, once deleted the details of the organization cannot be recovered.",
//                        buttons : '[No][Yes]'
//                }, function(ButtonPressed) {
//                        if (ButtonPressed === "Yes") {
//                            document.getElementById('mode').value = '<?php echo ORG_LIST_MODE_DELETE; ?>';
//                            document.getElementById('orgid').value = orgid;
//                            document.getElementById('orgname').value = orgname;
//                            document.getElementById('formsubmit').value = 'listorgsubmit';
//                            $("#orgListForm").submit();
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

    function preInputsInspection(OrgInputs $orgInputs, $mode){
        
        $orgInputs->setIdorgs(mysql_real_escape_string($_POST['orgid']));
        
        switch ($mode) {
            
            case ORG_LIST_MODE_ACTIVATE:
                
                $orgInputs->setIn_name($_POST['orgname']);
                $orgInputs->setActDeactivated($_POST['actdeact']);
                
                break;
            
            case ORG_LIST_MODE_DEACTIVATE:
                
                $orgInputs->setIn_name($_POST['orgname']);
                $orgInputs->setActDeactivated($_POST['actdeact']);
                
                break;
            
            case ORG_LIST_MODE_DELETE:
                
                $orgInputs->setIn_name($_POST['orgname']);
                
                break;
            
        }

    }

?>