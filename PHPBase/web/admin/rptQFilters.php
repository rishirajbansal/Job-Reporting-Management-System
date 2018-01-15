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
$page_title = getLocaleText('RPT_QFILTERS_MSG_1', TXT_A);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["reporting"]["active"] = true;
$page_nav["reporting"]["sub"]["rptQFilterconfig"]["active"] = true;
include("inc/nav.php");


/*------------- Form Submissions ---------*/

$orgInputs = new OrgInputs();
$orgDao = new OrgDao();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$orgDetails = new Organization();
$rptQFilters = array();

$xEditControls = array();

//Load data for Orgnaization list
$loadOrgFlag = $orgDao->loadOrgList(ORG_LIST_QFILTERS);
$isListLoaded = FALSE;
$totalOrgs = 0;
$listOrgs = array();

if ($loadOrgFlag){
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


//Load Query Filters
$loadQFFlag = $orgDao->loadRptQFilters();
if ($loadQFFlag){
    $rptQFilters = $orgDao->getRptQFiltersList();
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
       
    

}


/*------------- End Form Submissions ---------*/

?>


<!-- MAIN PANEL -->
<div id="main" role="main">
    
    <?php
        $breadcrumbs[getLocaleText('RPT_QFILTERS_MSG_2', TXT_A)] = "";
        include("inc/ribbon.php");
    ?>

    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-database"></i> <?php echo getLocaleText('RPT_QFILTERS_MSG_3', TXT_A); ?></h1>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
                <ul id="sparks" class="">
                    <li class="sparks-info">
                        <h5> <?php echo getLocaleText('RPT_QFILTERS_MSG_4', TXT_A); ?> <span class="txt-color-blue" style="text-align: center"><?php echo $totalOrgs; ?></span></h5>
                    </li>
                </ul>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <?php
        
        if ($loadOrgFlag && $loadQFFlag) { ?>
        
            <section id="widget-grid" class="">

                <div class="row">
                    
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-collapsed="true">

                            <header>
                                <span class="widget-icon"> <i class="fa fa-question-circle"></i> </span>
                                <h2><?php echo getLocaleText('RPT_QFILTERS_MSG_5', TXT_A); ?> </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body " >

                                    <table class="table table-bordered">
                                            
                                        <thead>
                                            <tr>
                                                <th data-class="expand" style="width:10%"><?php echo getLocaleText('RPT_QFILTERS_MSG_6', TXT_A); ?></th>
                                                <th><?php echo getLocaleText('RPT_QFILTERS_MSG_7', TXT_A); ?></th>
                                                <th data-hide="phone, tablet" style="width:50%"><?php echo getLocaleText('RPT_QFILTERS_MSG_8', TXT_A); ?></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php 
                                            if ($loadQFFlag === TRUE){
                                                foreach ($rptQFilters as $rptQFilter){?>

                                                    <tr>
                                                        <td style="padding-top: 14px;"><?php echo $rptQFilter->getQfilterId(); ?></td>
                                                        <td style="padding-top: 14px;"><?php echo $rptQFilter->getQfilterName(); ?></td>
                                                        <td style="padding-top: 14px;"><?php echo $rptQFilter->getQfilterDesc(); ?></td>
                                                    </tr>

                                                <?php
                                                }
                                            }
                                            ?>
                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </article>

                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">

                            <header>
                                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                <h2><?php echo getLocaleText('RPT_QFILTERS_MSG_9', TXT_A); ?> </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body no-padding" >

                                    <form class="form" id="qFiltersListForm" name="qFiltersListForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                        <input type="hidden" name="orgid" id="orgid" value=""/>
                                        
                                        <?php 
                                        if ($isListLoaded === FALSE){ ?>
                                            <div class="alert alert-warning fade in">
                                                <button class="close" data-dismiss="alert">
                                                        ×
                                                </button>
                                                <i class="fa-fw fa fa-warning"></i>
                                                <?php echo getLocaleText('RPT_QFILTERS_MSG_10', TXT_A); ?> <a href="newOrg.php" class="btn btn-xs txt-color-white" style="background-color: #bcae81;margin-top: -5px"><?php echo getLocaleText('RPT_QFILTERS_MSG_11-1', TXT_A); ?></a> <?php echo getLocaleText('RPT_QFILTERS_MSG_11-2', TXT_A); ?>
                                            </div>
                                        <?php
                                        } 
                                        ?>
                                        <table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
                                            
                                            <thead>
                                                <!--<tr>
                                                    <th class="hasinput" style="width:40%">
                                                        <input type="text" class="form-control" placeholder="Organization Name" />
                                                    <th class="hasinput" >
                                                        <input type="text" class="form-control" placeholder="Query Filters" />
                                                    </th>
                                                </tr>-->
                                                <tr>
                                                    <th data-class="expand" style="width:40%"><?php echo getLocaleText('RPT_QFILTERS_MSG_12', TXT_A); ?></th>
                                                    <th data-hide="phone, tablet"><?php echo getLocaleText('RPT_QFILTERS_MSG_13', TXT_A); ?></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <?php 
                                                if ($isListLoaded === TRUE){
                                                    $xeditSrc = '';
                                                    //$xEditSavedValues = '';
                                                    foreach ($rptQFilters as $rptQFilter){
                                                        $xeditSrc = $xeditSrc . '{value: \'' . $rptQFilter->getQfilterId() . '\', text: \'- ' . $rptQFilter->getQfilterName() . '\'}, ';
                                                    }
                                                    foreach ($listOrgs as $org){ 
                                                        $xeditId = 'x-'.$org->getIdorgs();
                                                        $xEditControl = array(
                                                                            'xeditId' => $xeditId,
                                                                            'xsource' => $xeditSrc,
                                                                            'xSelectedValues' => $org->getRptQFilters()
                                                                        );
                                                        array_push($xEditControls, $xEditControl);
                                                        ?>
                                                        <tr>
                                                            <td style="padding-top: 14px;"><?php echo $org->getName(); ?></td>
                                                            <td style="padding-top: 14px;">
                                                                <a href="form-x-editable.html#" id="<?php echo $xeditId; ?>" ></a>
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
        
        <?php
        }
        else if (empty ($userOrgDao->getMsgType())) { ?>
            <div class="alert alert-danger fade in">
                <i class="fa-fw fa fa-times"></i>
                <?php echo getLocaleText('RPT_QFILTERS_MSG_14', TXT_A); ?>
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
<script src="<?php echo ASSETS_URL; ?>/js/plugin/x-editable/moment.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/x-editable/jquery.mockjax.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/x-editable/x-editable.min.js"></script>


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
        /* END BASIC */

        /* COLUMN FILTER  */
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

  
        // Apply the filter
        $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

            otable
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();

        } );
        /* END COLUMN FILTER */  

    });
    
    <?php
    
    foreach($xEditControls as $xEditControl){
        $xeditId = $xEditControl['xeditId'];
        $xeditSrc = $xEditControl['xsource'];
        $xeditSelValues = $xEditControl['xSelectedValues'];
        ?>
            
        $('#<?php echo $xeditId; ?>').editable({
            url: 'xEditRptFilter.php',
            pk: 1,
            source: [
                <?php echo $xeditSrc; ?> 
            ],
            unsavedclass: null,
            type: 'checklist',
            mode: 'inline',
            emptytext: '<?php echo getLocaleText("RPT_QFILTERS_MSG_15", TXT_A); ?>',
            inputclass: 'popover-content-2',
            success: function (response, newValue) {
                $.smallBox({
                    title: "<?php echo getLocaleText('RPT_QFILTERS_MSG_16', TXT_A); ?>",
                    content: response,
                    color: "#296191",
                    iconSmall: "fa fa-check bounce animated",
                    timeout: 6000
                });
            },  
            error: function (response, newValue) {
                if (response.status === 400) {
                    return response.responseText;
                } else {
                    return '<?php echo getLocaleText("RPT_QFILTERS_MSG_17", TXT_A); ?>';
                }
            }
        });
        
        $('#<?php echo $xeditId; ?>').editable('setValue', '<?php echo $xeditSelValues; ?>', '<?php echo $xeditSelValues; ?>');

    <?php 
    }
    ?>
    
    

</script>

<?php

    function preInputsInspection(orgInputs $orgInputs){
        

    }

?>