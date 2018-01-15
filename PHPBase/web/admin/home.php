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
$page_title = getLocaleText('HOME_MSG_1', TXT_A);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["dashboard"]["active"] = true;
include("inc/nav.php");


/*------------- Form Submissions ---------*/

$orgInputs = new OrgInputs();
$orgDao = new OrgDao();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

//Load data
$loadFlag = $orgDao->loadDashBoardDetails();
$loadFlag = TRUE;
//$isDataLoaded = FALSE;
$totalOrgs = 0;
$rptTmpl_totalConfigs = 0;
$rptTmpl_totalNonConfigs = 0;
$rptQFilters_totalConfigs = 0;
$rptQFilters_totalNonConfigs = 0;
$rptStructs_totalConfigs = 0;
$rptStructs_totalNonConfigs = 0;
$lastlogin = '';
$orgRptsCountDataSet = '';

if ($loadFlag){
    
    $lastlogin = $orgDao->getLastLogin();
    $totalOrgs = $orgDao->getTotalOrgs();
    $rptStructs_totalConfigs = $orgDao->getRptStructs_totalConfigs();
    $rptStructs_totalNonConfigs = $orgDao->getRptStructs_totalNonConfigs();
    $rptTmpl_totalConfigs = $orgDao->getRptTmpl_totalConfigs();
    $rptTmpl_totalNonConfigs = $orgDao->getRptTmpl_totalNonConfigs();
    $rptQFilters_totalConfigs = $orgDao->getRptQFilters_totalConfigs();
    $rptQFilters_totalNonConfigs = $orgDao->getRptQFilters_totalNonConfigs();
    $orgRptsCountDataSet = $orgDao->getGraphOrgsRptsCountDataSet();
    
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
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> <?php echo getLocaleText('HOME_MSG_2', TXT_A); ?> </h1>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
                <ul id="sparks" class="">
                    <li class="sparks-info">
                        <h5> <?php echo getLocaleText('HOME_MSG_3', TXT_A); ?><span class="txt-color-blue" style="text-align: center;font-size: 16px"><?php echo $lastlogin; ?></span></h5>
                    </li>
                </ul>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <?php
        
        if ($loadFlag) { ?>
            
            <section id="widget-grid" class="">
                
                <div class="row">
                    
                    <article class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        
                        <!-- Organizations -->
                        <div class="jarviswidget jarviswidget-color-darkblue" id="wid-id-3" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-building"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_4', TXT_A); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php echo getLocaleText('HOME_MSG_5', TXT_A); ?>
                                    <?php echo getLocaleText('HOME_MSG_6', TXT_A); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_7', TXT_A); ?>
                                    <br/><br/>
                                    <strong class="txt-color-red"><?php echo getLocaleText('HOME_MSG_8', TXT_A); ?></strong>

                                    <br/><br/>
                                    
                                    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-info"></i><?php echo getLocaleText('HOME_MSG_9', TXT_A); ?> </li>
                                                <li><i class="fa fa-check text-info"></i><?php echo getLocaleText('HOME_MSG_10', TXT_A); ?> </li>
                                                <li><i class="fa fa-check text-info"></i><?php echo getLocaleText('HOME_MSG_11', TXT_A); ?> </li>
                                                <li><i class="fa fa-check text-info"></i><?php echo getLocaleText('HOME_MSG_12', TXT_A); ?> </li>
                                                <li><i class="fa fa-check text-info"></i><?php echo getLocaleText('HOME_MSG_13', TXT_A); ?> </li>
                                            </ul>
                                            <br/>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12" style="text-align: right;padding-left: 0px; padding-right: 0px;margin-bottom: 30px">
                                            <span class="label label-warning" style="font-size: 13px;padding: 10px;font-weight: normal;"><?php echo getLocaleText('HOME_MSG_14', TXT_A); ?>&nbsp; <span class="badge bg-color-white-txt-color-warning" style="font-size: 13px;">&nbsp;<?php echo $totalOrgs; ?>&nbsp;</span></span>
                                        </div>
                                    </div>
                                    
                                    <?php echo getLocaleText('HOME_MSG_15', TXT_A); ?>
                                    <?php echo getLocaleText('HOME_MSG_16', TXT_A); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_17', TXT_A); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_18', TXT_A); ?>
                                    <br/><br/><br/>
                                    
                                    <div style="text-align: center"><a href="org.php" class="btn btn-primary"><?php echo getLocaleText('HOME_MSG_19', TXT_A); ?></a></div>
                                    <br/>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                        <!-- Report Structure -->
                        <div class="jarviswidget jarviswidget-color-darkblue" id="wid-id-1" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-cubes"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_20', TXT_A); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php echo getLocaleText('HOME_MSG_21', TXT_A); ?>
                                    <?php echo getLocaleText('HOME_MSG_22', TXT_A); ?>
                                    <br/>
                                    <strong class="txt-color-red"><?php echo getLocaleText('HOME_MSG_23', TXT_A); ?> </strong>
                                    <?php echo getLocaleText('HOME_MSG_24', TXT_A); ?>
                                    <br/><br/>
                                    
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;padding-right: 0px;">
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_25', TXT_A); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_26', TXT_A); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_27', TXT_A); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_28', TXT_A); ?></li>
                                            </ul>
                                            <br/>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12" style="text-align: right;padding-left: 0px; padding-right: 0px;margin-bottom: 30px">
                                            <span class="label label-warning" style="font-size: 13px;padding: 6px;font-weight: normal;display: inline-block;"><?php echo getLocaleText('HOME_MSG_29', TXT_A); ?>&nbsp; <span class="badge bg-color-white-txt-color-warning" style="font-size: 13px;">&nbsp;<?php echo $rptStructs_totalConfigs; ?>&nbsp;</span></span>
                                            <span class="label label-default" style="font-size: 13px;padding: 6px;font-weight: normal;display: inline-block;margin-top: 4px;"><?php echo getLocaleText('HOME_MSG_30', TXT_A); ?>&nbsp; <span class="badge bg-color-white-txt-color-muted" style="font-size: 13px;">&nbsp;<?php echo $rptStructs_totalNonConfigs; ?>&nbsp;</span></span>
                                        </div>
                                    </div>
                                    
                                    <?php echo getLocaleText('HOME_MSG_31', TXT_A); ?>
                                    <?php echo getLocaleText('HOME_MSG_32', TXT_A); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_33', TXT_A); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_34', TXT_A); ?>
                                    <br/><br/><br/>
                                    
                                    <div style="text-align: center"><a href="rptStructs.php" class="btn btn-primary"><?php echo getLocaleText('HOME_MSG_35', TXT_A); ?></a></div>
                                    <br/>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                    </article>
                    
                    
                    <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        
                        <!-- Last 7 Activities -->
                        <div class="jarviswidget jarviswidget-color-tealLight" id="wid-id-3" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_36', TXT_A); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php
                                    if (empty($orgRptsCountDataSet) || empty($orgRptsCountDataSet['lables'])){ ?>
                                    <span class="text-danger" style="font-weight: 500;font-size: 14px"><?php echo getLocaleText('HOME_MSG_37', TXT_A); ?></span>
                                        <br/><br/><br/>
                                    <?php
                                    }
                                    else { ?>
                                        <canvas id="orgRptsChart" height="120"></canvas>
                                        <br/><br/>
                                    <?php
                                    }
                                    ?>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                        <!-- Report Templates -->
                        <div class="jarviswidget jarviswidget-color-darkblue" id="wid-id-1" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-file-pdf-o"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_38', TXT_A); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php echo getLocaleText('HOME_MSG_39', TXT_A); ?>
                                    <?php echo getLocaleText('HOME_MSG_40', TXT_A); ?>
                                    <?php echo getLocaleText('HOME_MSG_41', TXT_A); ?>
                                    <br/><br/>
                                    
                                    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_42', TXT_A); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_43', TXT_A); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_44', TXT_A); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_45', TXT_A); ?></li>
                                            </ul>
                                            <br/>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12" style="text-align: right;padding-left: 0px; padding-right: 0px;margin-bottom: 30px">
                                            <span class="label label-warning" style="font-size: 13px;padding: 6px;font-weight: normal;display: inline-block;"><?php echo getLocaleText('HOME_MSG_46', TXT_A); ?>&nbsp; <span class="badge bg-color-white-txt-color-warning" style="font-size: 13px;">&nbsp;<?php echo $rptTmpl_totalConfigs; ?>&nbsp;</span></span>
                                            <span class="label label-default" style="font-size: 13px;padding: 6px;font-weight: normal;display: inline-block;margin-top: 4px;"><?php echo getLocaleText('HOME_MSG_47', TXT_A); ?>&nbsp; <span class="badge bg-color-white-txt-color-muted" style="font-size: 13px;">&nbsp;<?php echo $rptTmpl_totalNonConfigs; ?>&nbsp;</span></span>
                                        </div>
                                    </div>
                                    
                                    <?php echo getLocaleText('HOME_MSG_48', TXT_A); ?>
                                    <?php echo getLocaleText('HOME_MSG_49', TXT_A); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_50', TXT_A); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_51', TXT_A); ?>
                                    <br/><br/><br/>
                                    
                                    <div style="text-align: center"><a href="rptTemplates.php" class="btn btn-primary"><?php echo getLocaleText('HOME_MSG_52', TXT_A); ?></a></div>
                                    <br/>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                        <!-- Report Query Filters -->
                        <div class="jarviswidget jarviswidget-color-darkblue" id="wid-id-2" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-database"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_53', TXT_A); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php echo getLocaleText('HOME_MSG_54', TXT_A); ?>
                                    <?php echo getLocaleText('HOME_MSG_55', TXT_A); ?>
                                    <br/><br/>
                                    
                                    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_56', TXT_A); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_57', TXT_A); ?></li>
                                            </ul>
                                            <br/>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12" style="text-align: right;padding-left: 0px; padding-right: 0px;margin-bottom: 30px">
                                            <span class="label label-warning" style="font-size: 13px;padding: 6px;font-weight: normal;display: inline-block;"><?php echo getLocaleText('HOME_MSG_58', TXT_A); ?>&nbsp; <span class="badge bg-color-white-txt-color-warning" style="font-size: 13px;">&nbsp;<?php echo $rptQFilters_totalConfigs; ?>&nbsp;</span></span>
                                            <span class="label label-default" style="font-size: 13px;padding: 6px;font-weight: normal;display: inline-block;margin-top: 4px;"><?php echo getLocaleText('HOME_MSG_59', TXT_A); ?>&nbsp; <span class="badge bg-color-white-txt-color-muted" style="font-size: 13px;">&nbsp;<?php echo $rptQFilters_totalNonConfigs; ?>&nbsp;</span></span>
                                        </div>
                                    </div>
                                    
                                    <?php echo getLocaleText('HOME_MSG_60', TXT_A); ?>
                                    <?php echo getLocaleText('HOME_MSG_61', TXT_A); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_62', TXT_A); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_63', TXT_A); ?>

                                    <br/><br/><br/>
                                    
                                    <div style="text-align: center"><a href="rptQFilters.php" class="btn btn-primary"><?php echo getLocaleText('HOME_MSG_64', TXT_A); ?></a></div>
                                    <br/>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                    </article>
                    
                </div>

            </section>
        
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

<script src="<?php echo ASSETS_URL; ?>/js/plugin/chartjs/chart.min.js"></script>

<script>

	$(document).ready(function() {
		
            pageSetUp();
            
            
            var barOptions = {
                    scaleBeginAtZero : true,
                    scaleShowGridLines : true,
                    scaleGridLineColor : "rgba(0,0,0,.05)",
                    scaleGridLineWidth : 1,
                    barShowStroke : true,
                    barStrokeWidth : 1,
                    barValueSpacing : 5,
                    barDatasetSpacing : 1,
                    responsive: true,
                    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
            }
            
            var orgRptsBarData = {
                labels: [<?php echo $orgRptsCountDataSet['lables']; ?> ],
                datasets:   [
                                {
                                    label: "Org Reports dataset",
                                    fillColor: "rgba(121,172,160,0.5)",
                                    strokeColor: "rgba(121,172,160,0.8)",
                                    highlightFill: "rgba(121,172,160,0.75)",
                                    highlightStroke: "rgba(121,172,160,1)",

                                    data: [<?php echo $orgRptsCountDataSet['dataset']; ?> ]
                                }
                            ]
            };
            
            if (document.getElementById("orgRptsChart") != null){
                var ctxOrgRptsChart = document.getElementById("orgRptsChart").getContext("2d");
                var orgRptBarChart = new Chart(ctxOrgRptsChart).Bar(orgRptsBarData, barOptions);
            }
            
            
	});

</script>
