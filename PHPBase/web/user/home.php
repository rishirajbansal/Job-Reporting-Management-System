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
$page_title = getLocaleText('HOME_MSG_1', TXT_U);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["dashboard"]["active"] = true;
include("inc/nav.php");

/*------------- Form Submissions ---------*/

$userOrgInputs = new UserOrgInputs();
$userOrgDao = new UserOrgDao();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$orgid = $_SESSION['orgid'];
$userOrgInputs->setIdorgs($orgid);


//Load data
$loadFlag = $userOrgDao->loadDashBoardDetails($userOrgInputs);
//$isDataLoaded = FALSE;
$totalPrds = 0;
$totalTsks = 0;
$totalWrks = 0;
$totalCstmrs = 0;
$totalRpts = 0;
$rptMonthDataSet = '';
$tskStatusDataSet = '';
$lastlogin = '';

if ($loadFlag){
    $totalPrds = $userOrgDao->getTotalPrds();
    $totalTsks = $userOrgDao->getTotalTsks();
    $totalWrks = $userOrgDao->getTotalWrks();
    $totalCstmrs = $userOrgDao->getTotalCstmrs();
    $totalRpts = $userOrgDao->getTotalRpts();
    $rptMonthDataSet = $userOrgDao->getGraphRptMonthDataSet();
    $tskStatusDataSet = $userOrgDao->getGraphTskStatusDataSet();
    $lastlogin = $userOrgDao->getLastLogin();
    
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
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i><?php echo getLocaleText('HOME_MSG_2', TXT_U); ?>  </h1>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
                <ul id="sparks" class="">
                    <li class="sparks-info">
                        <h5><?php echo getLocaleText('HOME_MSG_3', TXT_U); ?> <span class="txt-color-blue" style="text-align: center;font-size: 16px"><?php echo $lastlogin; ?></span></h5>
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
                        
                        <!-- Graph - Tasks Status -->
                        <div class="jarviswidget jarviswidget-color-tealLight" id="wid-id-0" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_4', TXT_U); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php
                                    if (empty($tskStatusDataSet) || empty($tskStatusDataSet['lables'])){ ?>
                                    <span class="text-danger" style="font-weight: 500;font-size: 14px"><?php echo getLocaleText('HOME_MSG_5', TXT_U); ?> </span>
                                        <br/><br/><br/>
                                    <?php
                                    }
                                    else { ?>
                                        <canvas id="tsksChart" height="120"></canvas>
                                        <br/><br/>
                                    <?php
                                    }
                                    ?>
                                        
                                    <div style="text-align: center"><a href="tasks.php" class="btn btn-primary"><?php echo getLocaleText('HOME_MSG_17', TXT_U); ?></a></div>
                                    <br/>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                        <!-- Products -->
                        <div class="jarviswidget jarviswidget-color-darkblue" id="wid-id-1" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-tags"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_6', TXT_U); ?></h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php echo getLocaleText('HOME_MSG_7', TXT_U); ?> <br/><br/>
                                    
                                    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_8', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_9', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_10', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_11', TXT_U); ?></li>
                                            </ul>
                                            <br/>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12" style="text-align: right;padding-left: 0px; padding-right: 0px;margin-bottom: 30px">
                                            <span class="label label-warning" style="font-size: 13px;padding: 10px;font-weight: normal;"><?php echo getLocaleText('HOME_MSG_12', TXT_U); ?> &nbsp; <span class="badge bg-color-white-txt-color-warning" style="font-size: 13px;">&nbsp;<?php echo $totalPrds; ?>&nbsp;</span></span>
                                        </div>
                                    </div>
                                    
                                    <?php echo getLocaleText('HOME_MSG_13', TXT_U); ?>
                                    <?php echo getLocaleText('HOME_MSG_14', TXT_U); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_15', TXT_U); ?>
                                    
                                    <br/><br/><br/>
                                    
                                    <div style="text-align: center"><a href="products.php" class="btn btn-primary"><?php echo getLocaleText('HOME_MSG_16', TXT_U); ?></a></div>
                                    <br/>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                        <!-- Workers -->
                        <div class="jarviswidget jarviswidget-color-darkblue" id="wid-id-2" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-group"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_18', TXT_U); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php echo getLocaleText('HOME_MSG_19', TXT_U); ?> <br/><br/>
                                    
                                    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_20', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_21', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_22', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_23', TXT_U); ?></li>
                                            </ul>
                                            <br/>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12" style="text-align: right;padding-left: 0px; padding-right: 0px;margin-bottom: 30px">
                                            <span class="label label-warning" style="font-size: 13px;padding: 10px;font-weight: normal;"><?php echo getLocaleText('HOME_MSG_24', TXT_U); ?> &nbsp; <span class="badge bg-color-white-txt-color-warning" style="font-size: 13px;">&nbsp;<?php echo $totalWrks; ?>&nbsp;</span></span>
                                        </div>
                                    </div>
                                    
                                    <?php echo getLocaleText('HOME_MSG_25', TXT_U); ?>
                                    <?php echo getLocaleText('HOME_MSG_26', TXT_U); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_27', TXT_U); ?>
                                    
                                    <br/><br/><br/>
                                    
                                    <div style="text-align: center"><a href="workers.php" class="btn btn-primary"><?php echo getLocaleText('HOME_MSG_28', TXT_U); ?></a></div>
                                    <br/>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                        <!-- Reports -->
                        <div class="jarviswidget jarviswidget-color-darkblue" id="wid-id-3" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_30', TXT_U); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php echo getLocaleText('HOME_MSG_31', TXT_U); ?> <br/><br/>
                                    
                                    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                                        <div class="col-lg-8 col-md-8 col-sm-12" style="margin-right: -5px;">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_32', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_33', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_34', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_35', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_36', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_37', TXT_U); ?></li>
                                            </ul>
                                            <br/>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12" style="text-align: right;padding-left: 0px; padding-right: 0px;margin-bottom: 30px">
                                            <span class="label label-warning" style="font-size: 13px;padding: 10px;font-weight: normal;"><?php echo getLocaleText('HOME_MSG_53', TXT_U); ?> &nbsp; <span class="badge bg-color-white-txt-color-warning" style="font-size: 13px;">&nbsp;<?php echo $totalRpts; ?>&nbsp;</span></span>
                                        </div>
                                    </div>
                                    
                                    <?php echo getLocaleText('HOME_MSG_38', TXT_U); ?>
                                    <?php echo getLocaleText('HOME_MSG_39', TXT_U); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_40', TXT_U); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_41', TXT_U); ?>
                                    
                                    <br/><br/><br/>
                                    
                                    <div style="text-align: center"><a href="dispReports.php" class="btn btn-primary"><?php echo getLocaleText('HOME_MSG_42', TXT_U); ?></a></div>
                                    <br/>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                    </article>
                    
                    
                    <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        
                        <!-- Graph - Reports Submitted -->
                        <div class="jarviswidget jarviswidget-color-tealLight" id="wid-id-4" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_43', TXT_U); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <canvas id="rptsChart" height="120"></canvas>

                                    <br/><br/>
                                    <div style="text-align: center"><a href="dispReports.php" class="btn btn-primary"><?php echo getLocaleText('HOME_MSG_44', TXT_U); ?></a></div>
                                    <br/>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                        <!-- Tasks -->
                        <div class="jarviswidget jarviswidget-color-darkblue" id="wid-id-5" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-list-alt"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_45', TXT_U); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php echo getLocaleText('HOME_MSG_46', TXT_U); ?> <br/><br/>
                                    
                                    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_47', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_48', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_49', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_50', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_51', TXT_U); ?></li>
                                            </ul>
                                            <br/>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12" style="text-align: right;padding-left: 0px; padding-right: 0px;margin-bottom: 30px">
                                            <span class="label label-warning" style="font-size: 13px;padding: 10px;font-weight: normal;"><?php echo getLocaleText('HOME_MSG_52', TXT_U); ?> &nbsp; <span class="badge bg-color-white-txt-color-warning" style="font-size: 13px;">&nbsp;<?php echo $totalTsks; ?>&nbsp;</span></span>
                                        </div>
                                    </div>
                                    
                                    <?php echo getLocaleText('HOME_MSG_54', TXT_U); ?>
                                    <?php echo getLocaleText('HOME_MSG_55', TXT_U); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_56', TXT_U); ?>
                                    
                                    <br/><br/><br/>
                                    
                                    <div style="text-align: center"><a href="tasks.php" class="btn btn-primary"><?php echo getLocaleText('HOME_MSG_57', TXT_U); ?></a></div>
                                    <br/>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                        <!-- Clients -->
                        <div class="jarviswidget jarviswidget-color-darkblue" id="wid-id-6" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-building"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_58', TXT_U); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php echo getLocaleText('HOME_MSG_59', TXT_U); ?> <br/><br/>
                                    
                                    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_60', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_61', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_62', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_63', TXT_U); ?></li>
                                            </ul>
                                            <br/>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12" style="text-align: right;padding-left: 0px; padding-right: 0px;margin-bottom: 30px">
                                            <span class="label label-warning" style="font-size: 13px;padding: 10px;font-weight: normal;"><?php echo getLocaleText('HOME_MSG_64', TXT_U); ?> &nbsp; <span class="badge bg-color-white-txt-color-warning" style="font-size: 13px;">&nbsp;<?php echo $totalCstmrs; ?>&nbsp;</span></span>
                                        </div>
                                    </div>
                                    
                                    <?php echo getLocaleText('HOME_MSG_65', TXT_U); ?>
                                    <?php echo getLocaleText('HOME_MSG_66', TXT_U); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_67', TXT_U); ?>
                                    
                                    <br/><br/><br/>
                                    
                                    <div style="text-align: center"><a href="customers.php" class="btn btn-primary"><?php echo getLocaleText('HOME_MSG_68', TXT_U); ?></a></div>
                                    <br/>
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                        <!-- Query Filters -->
                        <div class="jarviswidget jarviswidget-color-darkblue" id="wid-id-7" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-sortable="false" data-widget-fullscreenbutton="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-filter"></i> </span>
                                <h2><?php echo getLocaleText('HOME_MSG_69', TXT_U); ?> </h2>
                            </header>
                            
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body">
                                    
                                    <?php echo getLocaleText('HOME_MSG_70', TXT_U); ?> <br/><br/>
                                    
                                    <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_71', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_72', TXT_U); ?></li>
                                                <li><i class="fa fa-check text-info"></i> <?php echo getLocaleText('HOME_MSG_73', TXT_U); ?></li>
                                            </ul>
                                            <br/>
                                        </div>
                                    </div>
                                    
                                    <?php echo getLocaleText('HOME_MSG_74', TXT_U); ?>
                                    
                                    <?php echo getLocaleText('HOME_MSG_75', TXT_U); ?>
                                    <?php echo getLocaleText('HOME_MSG_76', TXT_U); ?>
                                    <br/><br/>
                                    <?php echo getLocaleText('HOME_MSG_77', TXT_U); ?>
                                    <br/><br/>
                                    
                                    
                                    <?php
                                    if (isset($_SESSION['allQueryFilter']) && isset($_SESSION['queryFilter']) && !empty($_SESSION['queryFilter'])){
                                        $menuQueryFilters = $_SESSION['allQueryFilter'];
                                        $configuredQueryFilters = $_SESSION['queryFilter'];
                                        $configuredQueryFilters = explode(MENU_QUERY_FILTERS_SEPERATOR, $configuredQueryFilters);
                                        $qFilters = array();
                                        foreach ($menuQueryFilters as $key => $value) {
                                            if (in_array($key, $configuredQueryFilters)){
                                                $title = $value['title'];
                                                $url = $value['url'];
                                                array_push($qFilters, array(
                                                                        "title" => $title,
                                                                        "url" => $url
                                                                    ));
                                            }
                                        }
                                        ?>
                                        
                                        <span class="text-primary" style="font-weight: 600;"><?php echo getLocaleText('HOME_MSG_78', TXT_U); ?> </span>
                                        <br/><br/>
                                        <div class="col-md-6 col-md-offset-3" style="padding-left: 0px;padding-right: 0px;">
                                            <?php
                                            foreach ($qFilters as $qFilter) {
                                                ?>
                                            <div style="text-align: center"><a href="<?php echo $qFilter["url"]; ?>" class="btn bg-color-yellow txt-color-white btn-block" style=""><?php echo $qFilter["title"]; ?></a></div>
                                                <br/>
                                            <?php    
                                            }
                                            ?>
                                        </div>
                                    <?php
                                    }
                                    else{
                                    ?>
                                        <span class="text-danger" style="font-weight: 600;font-size: 14px"><?php echo getLocaleText('HOME_MSG_79', TXT_U); ?> </span>
                                        <br/>
                                    <?php
                                    }
                                    ?>
                                        
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

            var rptsBarData = {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets:   [
                                {
                                    label: "Reports dataset",
                                    fillColor: "rgba(151,187,205,0.5)",
                                    strokeColor: "rgba(151,187,205,0.8)",
                                    highlightFill: "rgba(151,187,205,0.75)",
                                    highlightStroke: "rgba(151,187,205,1)",

                                    data: [<?php echo $rptMonthDataSet; ?>]
                                }
                            ]
            };
            
            var tsksBarData = {
                labels: [<?php echo $tskStatusDataSet['lables']; ?>],
                datasets:   [
                                {
                                    label: "Task Status dataset",
                                    fillColor: "rgba(225,203,153,0.5)",
                                    strokeColor: "rgba(225,203,153,0.8)",
                                    highlightFill: "rgba(225,203,153,0.75)",
                                    highlightStroke: "rgba(225,203,153,1)",

                                    data: [<?php echo $tskStatusDataSet['dataset']; ?>]
                                }
                            ]
            };
            
            if (document.getElementById("rptsChart") != null){
                var ctxRptsChart = document.getElementById("rptsChart").getContext("2d");
                var rptBarChart = new Chart(ctxRptsChart).Bar(rptsBarData, barOptions);
            }
            
            if (document.getElementById("tsksChart") != null){
                var ctxTsksChart = document.getElementById("tsksChart").getContext("2d");
                var tsksBarChart = new Chart(ctxTsksChart).Bar(tsksBarData, barOptions);
            }

	})

</script>
