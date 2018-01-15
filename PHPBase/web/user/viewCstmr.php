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
include_once(dirname(__FILE__) . "/../../classes/vo/UserEntity.php");

include_once("sessionMgmt.php");

require_once(dirname(__FILE__) . "/../commonInc/init.php");
require_once(dirname(__FILE__) . "/inc/config.ui.php");


/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('VIEW_CSTMR_MSG_1', TXT_U);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["clients"]["active"] = true;
include("inc/nav.php");

/*------------- Form Submissions ---------*/

$userOrgInputs = new UserOrgInputs();
$userOrgDao = new UserOrgDao();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$orgid = $_SESSION['orgid'];

$isDetailsFetched = FALSE;
$cstmrid = '';
$mode = '';

//Load prerequisties
$userOrgInputs->setIdorgs($orgid);
$loadFlag = $userOrgDao->fetchCustomerDynaDetails($userOrgInputs);
if ($loadFlag){
    $userEntities = $userOrgDao->getUserEntities();
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

if (isset($_POST['cstmrid'])){
    $cstmrid = $_POST['cstmrid'];
}
$mode = $_POST['mode'];

$userOrgInputs->setIdcstmrs($cstmrid);

$fetchFlag = $userOrgDao->fetchCustomerDetails($userOrgInputs, $mode);

if ($fetchFlag){
    $userEntities = $userOrgDao->getUserEntities();
    $isDetailsFetched = TRUE;
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
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-building"></i> 
                    <?php echo getLocaleText('VIEW_CSTMR_MSG_2', TXT_U); ?>
                </h1>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <a href="customers.php" class="btn bg-color-blueLight txt-color-white btn-lg pull-right header-btn hidden-mobile"><i class="fa fa-arrow-circle-left "></i> &nbsp;<?php echo getLocaleText('VIEW_CSTMR_MSG_3', TXT_U); ?></a>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <?php
        
        if ($loadFlag && $isDetailsFetched){ ?>
        
            <section id="widget-grid" class="">

                <div class="row">

                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">

                            <header>
                                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                                <h2><?php echo getLocaleText('VIEW_CSTMR_MSG_4', TXT_U); ?> </h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body" style="padding-left: 20px;padding-right: 20px;">

                                    <form class="form-horizontal" id="viewCstmrDetailsForm" name="viewCstmrDetailsForm" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                        <input type="hidden" name="cstmrid" id="cstmrid" value="<?php echo $cstmrid; ?>"/>
                                        <input type="hidden" name="mode" id="mode" value="<?php echo $_POST['mode']; ?>"/>
                                        
                                        <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                            <div class="col-md-9" style="padding-left: 0px; padding-right: 0px;">
                                                <h3><strong><?php echo getLocaleText('VIEW_CSTMR_MSG_5', TXT_U); ?></strong></h3>
                                                <p><?php echo getLocaleText('VIEW_CSTMR_MSG_6', TXT_U); ?>  </p>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-actions" style="border: none;background: none;">
                                                    <div class="row">
                                                        <div class="col-sm-12" >
                                                            <button type="button" class="btn btn-default btn-primary" id="edit" style="font-size: 14px;">
                                                                &nbsp;&nbsp;<i class="fa fa-edit"></i> &nbsp;<?php echo getLocaleText('VIEW_CSTMR_MSG_BTN_1', TXT_U); ?>&nbsp;&nbsp;
                                                            </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-default btn-danger" id="delete" style="font-size: 14px;">
                                                                &nbsp;&nbsp;<i class="fa fa-trash-o"></i>&nbsp;<?php echo getLocaleText('VIEW_CSTMR_MSG_BTN_2', TXT_U); ?> &nbsp;&nbsp;
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <legend></legend>
                                        <br/>

                                        <div style="min-height: 240px">
                                            <?php
                                            $userEntities = $userOrgDao->getUserEntities();
                                            foreach ($userEntities as $userEntity){ ?>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label"><strong><?php echo $userEntity->getName(); ?> : </strong></label>
                                                    <label class="col-md-5 control-label" style="text-align: left"><?php echo $userEntity->getSavedValue(); ?> </label>
                                                </div>

                                            <?php
                                            }
                                            ?>
                                        </div>

                                        <br/>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </article>

                </div>

            </section>
        
            <div id="deleteConf" title="">
                <br/>
                <h5><?php echo getLocaleText('VIEW_CSTMR_MSG_7', TXT_U); ?></h5>
                <br/>
            </div>
        
        <?php
        }
        else if (empty ($userOrgDao->getMsgType())) { ?>
            <div class="alert alert-danger fade in">
                <i class="fa-fw fa fa-times"></i>
                <?php echo getLocaleText('VIEW_CSTMR_MSG_8', TXT_U); ?>
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


<script>

    $(document).ready(function() {
        
        pageSetUp();
        
        
        $('#deleteConf').dialog({
                autoOpen : false,
                width : 600,
                resizable : false,
                modal : true,
                //title : "<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> On Delete Confirmation!</h4></div>",
                buttons : [{
                        html : "<i class='fa fa-trash-o'></i>&nbsp; <?php echo getLocaleText('VIEW_CSTMR_MSG_9', TXT_U); ?>",
                        "class" : "btn btn-danger",
                        click : function() {
                                document.getElementById('mode').value = '<?php echo USERDYNAFIELDS_LIST_MODE_DELETE; ?>';
                                document.getElementById('cstmrid').value = <?php echo $cstmrid; ?>;
                                document.getElementById('formsubmit').value = 'viewCstmrDetailsForm';
                                document.forms['viewCstmrDetailsForm'].action = 'customers.php';
                                $("#viewCstmrDetailsForm").submit();
                        }
                }, {
                        html : "<i class='fa fa-times'></i>&nbsp; <?php echo getLocaleText('VIEW_CSTMR_MSG_10', TXT_U); ?>",
                        "class" : "btn btn-default",
                        click : function() {
                                $(this).dialog("close");
                        }
                }],
                open: function(event, ui){
                        $(this).parent().find('.ui-dialog-title').append("<div class='widget-header' style='color: #FFF;'><h4><i class='fa fa-warning'></i> <?php echo getLocaleText("VIEW_CSTMR_MSG_11", TXT_U); ?></h4></div>");
                    },
                close: function(event, ui){
                        $(this).parent().find('.widget-header').remove();
                    }
        });

        
        $('#btnCancel').on('click', function () {
            location.href = 'customers.php';
        });
        
        $('#edit').on('click', function () {
            document.getElementById('mode').value = '<?php echo USERDYNAFIELDS_LIST_MODE_EDIT; ?>';
            document.getElementById('cstmrid').value = <?php echo $cstmrid; ?>;
            document.getElementById('formsubmit').value = 'viewCstmrDetailsForm';
            document.forms['viewCstmrDetailsForm'].action = 'newCstmr.php';
            $("#viewCstmrDetailsForm").submit();
        });
        
        $('#delete').on('click', function () {
            
            $('#deleteConf').dialog('open');
            
//            $.SmartMessageBox({
//                    title : "On Delete Confirmation!",
//                    content : "Are you sure want to remove this Client from the records.",
//                    buttons : '[No][Yes]'
//            }, function(ButtonPressed) {
//                    if (ButtonPressed === "Yes") {
//                        document.getElementById('mode').value = '<?php echo USERDYNAFIELDS_LIST_MODE_DELETE; ?>';
//                        document.getElementById('cstmrid').value = <?php echo $cstmrid; ?>;
//                        document.getElementById('formsubmit').value = 'viewCstmrDetailsForm';
//                        document.forms['viewCstmrDetailsForm'].action = 'customers.php';
//                        $("#viewCstmrDetailsForm").submit();
//                    }
//                    if (ButtonPressed === "No") {
//
//                    }
//            });
            
        });
        
    })

</script>