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
include_once(dirname(__FILE__) . "/../../classes/vo/DynaFields.php");

include_once("sessionMgmt.php");

require_once(dirname(__FILE__) . "/../commonInc/init.php");
require_once(dirname(__FILE__) . "/inc/config.ui.php");


/*------------- CONFIGURATION VARIABLES ---------*/
$page_title =getLocaleText('HELP_MSG_1', TXT_A);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

//$page_nav["dashboard"]["active"] = true;
include("inc/nav.php");


/*------------- Form Submissions ---------*/

$orgInputs = new OrgInputs();
$orgDao = new OrgDao();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$productDynaFields = array();
$tasksDynaFields = array();
$workerDynaFields = array();
$customerDynaFields = array();
$reportingDynaFields = array();

$controlsDetails = array();


//Load All Dyna Fields Details
$loadControlsFlag = $orgDao->loadDynaFields();
if ($loadControlsFlag){
    $productDynaFields = $orgDao->getProductDynaFields();
    $tasksDynaFields = $orgDao->getTaskDynaFields();
    $workerDynaFields = $orgDao->getWorkerDynaFields();
    $customerDynaFields = $orgDao->getCustomerDynaFields();
    $reportingDynaFields = $orgDao->getReportingDynaFields();
    
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
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-question-circle"></i></i> <?php echo getLocaleText('HELP_MSG_2', TXT_A); ?> </h1>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <div class="row">
		
            <div class="col-sm-12">
                
                <ul id="tab1" class="nav nav-tabs bordered">
                    <li class="active">
                        <a href="#help1" data-toggle="tab"><?php echo getLocaleText('HELP_MSG_3', TXT_A); ?></a>
                    </li>
                </ul>
                
                <div id="tabContent1" class="tab-content bg-color-white padding-10">
                    
                    <div class="tab-pane fade in active" id="help1">
                        <h1><?php echo getLocaleText('HELP_MSG_4', TXT_A); ?> </h1>
                        <p><?php echo getLocaleText('HELP_MSG_5', TXT_A); ?></p>
                        <br>
                        
                        <table class="table table-bordered">
                                            
                            <thead>
                                <tr>
                                    <th style="width:2%">#</th>
                                    <th style="width:20%"><?php echo getLocaleText('HELP_MSG_6', TXT_A); ?></th>
                                    <th><?php echo getLocaleText('HELP_MSG_7', TXT_A); ?></th>
                                    <th style="width:40%"><?php echo getLocaleText('HELP_MSG_8', TXT_A); ?></th>
                                    <th style="width:20%"><?php echo getLocaleText('HELP_MSG_9', TXT_A); ?></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php 
                                if ($loadControlsFlag === TRUE){
                                    $dynaFields = new DynaFields();
                                    $ctr = 0;
                                    
                                    if (!empty($productDynaFields)){ ?>
                                        <tr class="warning" style="font-weight: bold">
                                            <td colspan="5"><?php echo getLocaleText('HELP_MSG_10', TXT_A); ?></td>
                                        </tr>
                                        <?php
                                        foreach ($productDynaFields as $dynaFields){
                                            ++$ctr;
                                            $types = $dynaFields->getHtmlType();
                                            $types = explode(':', $types);
                                            $validations = $dynaFields->getHtmlValidations();
                                            $validations = str_replace(':true', '', $validations);
                                            ?>

                                            <tr>
                                                <td style="padding-top: 14px;"><?php echo $ctr; ?></td>
                                                <td style="padding-top: 14px;"><?php echo $dynaFields->getName(); ?></td>
                                                <td style="padding-top: 14px;"><?php echo $types[1]; ?></td>
                                                <td style="padding-top: 14px;"><?php echo $dynaFields->getDescription(); ?></td>
                                                <td style="padding-top: 14px;"><?php echo $validations; ?></td>
                                            </tr>

                                        <?php
                                        }
                                        
                                    }
                                    
                                    if (!empty($tasksDynaFields)){ ?>
                                        <tr class="warning" style="font-weight: bold">
                                            <td colspan="5"><?php echo getLocaleText('HELP_MSG_11', TXT_A); ?></td>
                                        </tr>
                                        <?php
                                        foreach ($tasksDynaFields as $dynaFields){
                                            ++$ctr;
                                            $types = $dynaFields->getHtmlType();
                                            $types = explode(':', $types);
                                            $validations = $dynaFields->getHtmlValidations();
                                            $validations = str_replace(':true', '', $validations);
                                            ?>

                                            <tr>
                                                <td style="padding-top: 14px;"><?php echo $ctr; ?></td>
                                                <td style="padding-top: 14px;"><?php echo $dynaFields->getName(); ?></td>
                                                <td style="padding-top: 14px;"><?php echo $types[1]; ?></td>
                                                <td style="padding-top: 14px;"><?php echo $dynaFields->getDescription(); ?></td>
                                                <td style="padding-top: 14px;"><?php echo $validations; ?></td>
                                            </tr>

                                        <?php
                                        }
                                        
                                    }
                                    
                                    if (!empty($workerDynaFields)){ ?>
                                        <tr class="warning" style="font-weight: bold">
                                            <td colspan="5"><?php echo getLocaleText('HELP_MSG_12', TXT_A); ?></td>
                                        </tr>
                                        <?php
                                        foreach ($workerDynaFields as $dynaFields){
                                            ++$ctr;
                                            $types = $dynaFields->getHtmlType();
                                            $types = explode(':', $types);
                                            $validations = $dynaFields->getHtmlValidations();
                                            $validations = str_replace(':true', '', $validations);
                                            ?>

                                            <tr>
                                                <td style="padding-top: 14px;"><?php echo $ctr; ?></td>
                                                <td style="padding-top: 14px;"><?php echo $dynaFields->getName(); ?></td>
                                                <td style="padding-top: 14px;"><?php echo $types[1]; ?></td>
                                                <td style="padding-top: 14px;"><?php echo $dynaFields->getDescription(); ?></td>
                                                <td style="padding-top: 14px;"><?php echo $validations; ?></td>
                                            </tr>

                                        <?php
                                        }
                                        
                                    }
                                    
                                    if (!empty($customerDynaFields)){ ?>
                                        <tr class="warning" style="font-weight: bold">
                                            <td colspan="5"><?php echo getLocaleText('HELP_MSG_13', TXT_A); ?></td>
                                        </tr>
                                        <?php
                                        foreach ($customerDynaFields as $dynaFields){
                                            ++$ctr;
                                            $types = $dynaFields->getHtmlType();
                                            $types = explode(':', $types);
                                            $validations = $dynaFields->getHtmlValidations();
                                            $validations = str_replace(':true', '', $validations);
                                            ?>

                                            <tr>
                                                <td style="padding-top: 14px;"><?php echo $ctr; ?></td>
                                                <td style="padding-top: 14px;"><?php echo $dynaFields->getName(); ?></td>
                                                <td style="padding-top: 14px;"><?php echo $types[1]; ?></td>
                                                <td style="padding-top: 14px;"><?php echo $dynaFields->getDescription(); ?></td>
                                                <td style="padding-top: 14px;"><?php echo $validations; ?></td>
                                            </tr>

                                        <?php
                                        }
                                        
                                    }
                                    
                                    if (!empty($reportingDynaFields)){ ?>
                                        <tr class="warning" style="font-weight: bold">
                                            <td colspan="5"><?php echo getLocaleText('HELP_MSG_14', TXT_A); ?></td>
                                        </tr>
                                        <?php
                                        foreach ($reportingDynaFields as $dynaFields){
                                            ++$ctr;
                                            $types = $dynaFields->getHtmlType();
                                            $types = explode(':', $types);
                                            $validations = $dynaFields->getHtmlValidations();
                                            $validations = str_replace(':true', '', $validations);
                                            ?>

                                            <tr>
                                                <td style="padding-top: 14px;"><?php echo $ctr; ?></td>
                                                <td style="padding-top: 14px;"><?php echo $dynaFields->getName(); ?></td>
                                                <td style="padding-top: 14px;"><?php echo $types[1]; ?></td>
                                                <td style="padding-top: 14px;"><?php echo $dynaFields->getDescription(); ?></td>
                                                <td style="padding-top: 14px;"><?php echo $validations; ?></td>
                                            </tr>

                                        <?php
                                        }
                                        
                                    }
                                    
                                }
                                ?>
                            </tbody>

                        </table>
                        <br>

 
                    </div>

                </div>
                <br>
                
            </div>
            
        </div>
        
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
        
    });
    
</script>