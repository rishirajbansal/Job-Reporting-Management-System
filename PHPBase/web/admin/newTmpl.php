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
include_once(dirname(__FILE__) . "/../../classes/vo/OrgRptTemplate.php");

include_once("sessionMgmt.php");

require_once(dirname(__FILE__) . "/../commonInc/init.php");
require_once(dirname(__FILE__) . "/inc/config.ui.php");


/*------------- CONFIGURATION VARIABLES ---------*/
$page_title = getLocaleText('NEW_TMPL_MSG_1', TXT_A);
/* ------------- END CONFIGURATION VARIABLES ------ */

$page_css[] = "";

include("inc/header.php");

$page_nav["reporting"]["active"] = true;
$page_nav["reporting"]["sub"]["rptTmplConfig"]["active"] = true;
include("inc/nav.php");

/*------------- Form Submissions ---------*/

$orgInputs = new OrgInputs();
$orgDao = new OrgDao();
$templates = array();
$orgRptTemplate = new OrgRptTemplate();

$errMsgObject['msg'] = null;
$errMsgObject['text'] = null;

$selectedTempalteId = '';
$isDetailsFetched = FALSE;

//Load prerequisties for Template Creation
$loadFlag = $orgDao->loadRptTemplates();
if ($loadFlag){
    $templates = $orgDao->getTemplates();
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


if (isset($_POST['formsubmit']) && $_POST['formsubmit'] != 'rptTmplsListForm'){
    
    preInputsInspection($orgInputs);
    
    $mode = $_POST['mode'];
    
    $daoFlag = $orgDao->saveOrgTemplate($orgInputs, $mode);
    
    /*$daoFlag = '';
    if ($mode == ORG_TMPL_LIST_MODE_NEW){
        $daoFlag = $orgDao->saveOrgTemplate($orgInputs, ORG_TMPL_LIST_MODE_NEW);
    }
    else if ($mode == ORG_TMPL_LIST_MODE_EDIT){
        $daoFlag = $orgDao->saveOrgTemplate($orgInputs, ORG_TMPL_LIST_MODE_EDIT);
    }*/
    
    
    if ($daoFlag){
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
    
    $fetchFlag = $orgDao->fetchOrgTemplate($orgInputs, $mode);
            
    if ($fetchFlag){
        $orgRptTemplate = $orgDao->getOrgTemplateDetails();
        $isDetailsFetched = TRUE;
    }
    
}
else if (!empty($loadFlag) && isset($_POST['formsubmit']) && $_POST['formsubmit'] == 'rptTmplsListForm' && isset($_POST['mode'])){
    
    $orgId = $_POST['orgid'];
    $mode = $_POST['mode'];
    
    $orgInputs->setIdorgs($orgId);
    
    $fetchFlag = $orgDao->fetchOrgTemplate($orgInputs, $mode);
            
    if ($fetchFlag){
        $orgRptTemplate = $orgDao->getOrgTemplateDetails();
        $isDetailsFetched = TRUE;
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



/*------------- End Form Submissions ---------*/

?>

<!-- MAIN PANEL -->
<div id="main" role="main">
    
    <?php
        $breadcrumbs[getLocaleText('NEW_TMPL_MSG_2', TXT_A)] = "";
        $breadcrumbs[getLocaleText('NEW_TMPL_MSG_3', TXT_A)] = "rptTemplates.php";
        include("inc/ribbon.php");
    ?>
    
    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-file-pdf-o"></i> 
                    <?php
                    if ($mode == ORG_TMPL_LIST_MODE_NEW){ ?>
                         <?php echo getLocaleText('NEW_TMPL_MSG_4', TXT_A); ?>
                    <?php
                    }
                    else if ($mode == ORG_TMPL_LIST_MODE_EDIT){ ?>
                         <?php echo getLocaleText('NEW_TMPL_MSG_5', TXT_A); ?>
                    <?php
                    }
                    ?>
                </h1>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <a href="rptTemplates.php" class="btn bg-color-blueLight txt-color-white btn-lg pull-right header-btn hidden-mobile"><i class="fa fa-circle-arrow-up fa-lg"></i> <?php echo getLocaleText('NEW_TMPL_MSG_6', TXT_A); ?></a>
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
                                <span class="widget-icon"> <i class="fa fa-file-text"></i> </span>
                                <?php
                                if ($mode == ORG_TMPL_LIST_MODE_NEW){ ?>
                                    <h2><?php echo getLocaleText('NEW_TMPL_MSG_7', TXT_A); ?> </h2>
                                <?php
                                }
                                else if ($mode == ORG_TMPL_LIST_MODE_EDIT){ ?>
                                    <h2><?php echo getLocaleText('NEW_TMPL_MSG_8', TXT_A); ?> </h2>
                                <?php
                                }
                                ?>
                            </header>

                            <!-- widget div-->
                            <div>

                                <div class="jarviswidget-editbox">

                                </div>

                                <div class="widget-body" style="padding-left: 20px;padding-right: 20px;">

                                    <form class="form-horizontal" id="newRptTmpl" name="newRptTmpl" method="post" action="">
                                        <input type="hidden" name="formsubmit" id="formsubmit" value=""/>
                                        <input type="hidden" name="orgid" id="orgid" value="<?php echo $orgRptTemplate->getIdorgs(); ?>"/>
                                        <input type="hidden" name="templateId" id="templateId" value=""/>
                                        <input type="hidden" name="mode" id="mode" value="<?php echo $_POST['mode'] ?>"/>

                                        <h3><strong><?php echo getLocaleText('NEW_TMPL_MSG_9', TXT_A); ?></strong></h3>
                                        <p><?php echo getLocaleText('NEW_TMPL_MSG_10', TXT_A); ?> </p>

                                        <fieldset style="min-height: 380px">

                                            <legend></legend>
                                            <br/>
                                            
                                            <div class="alert alert-block alert-form-wizard-error" id="divTmplError" style="display: none">
                                                <?php echo getLocaleText('NEW_TMPL_MSG_11', TXT_A); ?>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-3 control-label"><?php echo getLocaleText('NEW_TMPL_MSG_12', TXT_A); ?> </label>
                                                <div class="col-md-5">
                                                    <div class="input-group input-group-md">
                                                        <span class="input-group-addon "><i class="fa fa-file-text fa-fw"></i></span>
                                                        <input class="form-control" placeholder="Template Name" type="text" name="tmplname" id="tmplname" value="<?php echo $orgRptTemplate->getTemplateName(); ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <br/>
                                            
                                            <div id="templateContainer">
                                                <?php
                                                $tmpFlag = TRUE;
                                                foreach ($templates as $record){
                                                    $templateId = $record['templateId'];
                                                    $templateName = $record['templateName'];
                                                    $path = $record['path'];
                                                    if ($templateId == $orgRptTemplate->getRawTemplateId()){
                                                        $selectedTempalteId = $templateId;
                                                    }

                                                    if ($tmpFlag){ ?>
                                                        <div class="form-group" style="margin-bottom: 0px;">
                                                            <label class="col-md-3 control-label"><?php echo getLocaleText('NEW_TMPL_MSG_13', TXT_A); ?> 
                                                                <br/><br/><span class="label label-default" style="font-weight: normal;font-size: 100%;"><?php echo getLocaleText('NEW_TMPL_MSG_14', TXT_A); ?></span>
                                                            </label>
                                                    <?php 
                                                    $tmpFlag = FALSE;
                                                    } 
                                                    else { ?>
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label"></label>
                                                    <?php } ?>
                                                            <div class="input-group input-group-md col-md-5" id="<?php echo $templateId; ?>" >
                                                                <img class="col-md-3" src="../img/icons/File-pdf.png" style="max-width: 100px;" rel="popover-hover" data-placement="top" data-content="<?php echo getLocaleText('NEW_TMPL_MSG_15', TXT_A); ?>" data-original-title="" onclick="javascript:toggleTmplImage('<?php echo $templateId; ?>')">
                                                                <div style="padding-top: 30px;">
                                                                    <label class="col-md-7"> <?php echo getLocaleText('NEW_TMPL_MSG_16-1', TXT_A); ?> <strong><?php echo $templateName; ?></strong> <?php echo getLocaleText('NEW_TMPL_MSG_16-2', TXT_A); ?> </label>
                                                                    <button type="button" class="btn btn-sm btn-primary" id="preview" style="margin-left: 25px;margin-top: -4px;" onclick="javascript:checkPreview('<?php echo $templateId; ?>', '<?php echo $templateName; ?>');">&nbsp;<i class="fa fa-eye"></i> &nbsp;<?php echo getLocaleText('NEW_TMPL_MSG_BTN_4', TXT_A); ?>&nbsp;&nbsp;</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                }
                                                ?>
                                            </div>

                                            <!--<div class="form-group" style="margin-bottom: 0px;">
                                                <label class="col-md-3 control-label">Chose the <strong>template design</strong> that is best compatible with this organization's format, the chosen template will be used in reports generation for this organization 
                                                    <br/><br/><span class="label label-default" style="font-weight: normal;font-size: 100%;"><strong>Click on the image</strong> to select the desired template</span>
                                                </label>
                                                <div class="input-group input-group-md col-md-5" id="pdficon1" >
                                                    <img class="col-md-3" src="../img/icons/File-pdf.png" style="max-width: 100px;" rel="popover-hover" data-placement="top" data-content="Click to select/deselect the template" data-original-title="" onclick="javascript:toggleTmplImage('pdficon1')">
                                                    <div style="padding-top: 35px;">
                                                        <label class="col-md-7"> Template designed in <strong>CODEPSA</strong> format </label>
                                                        <button type="button" class="btn btn-sm btn-primary" id="preview" style="margin-left: 25px;font-size: 14px;margin-top: -8px;">&nbsp;<i class="fa fa-search"></i> &nbsp;Preview&nbsp;&nbsp;</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group" >
                                                <label class="col-md-3 control-label"></label>
                                                <div class="input-group input-group-md col-md-5" id="pdficon2" >
                                                    <img class="col-md-3" src="../img/icons/File-pdf.png" style="max-width: 100px;" rel="popover-hover" data-placement="top" data-content="Click to select/deselect the template" data-original-title="" onclick="javascript:toggleTmplImage('pdficon2')">
                                                    <div style="padding-top: 35px;">
                                                        <label class="col-md-7"> Template designed in <strong>CODEPSA</strong> format </label>
                                                        <button type="button" class="btn btn-sm btn-primary" id="preview" style="margin-left: 25px;font-size: 14px;margin-top: -8px;">&nbsp;<i class="fa fa-search"></i> &nbsp;Preview&nbsp;&nbsp;</button>
                                                    </div>
                                                </div>
                                            </div> -->

                                            <br/>

                                            <div class="form-actions" style="border: none;background: none;border-top: 1px solid rgba(0,0,0,.1);">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-default bg-color-blueLight txt-color-white" id="btnCancel">
                                                            <i class="glyphicon glyphicon-remove"></i> <?php echo getLocaleText('NEW_TMPL_MSG_BTN_1', TXT_A); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-default" id="btnReset">
                                                            <i class="fa fa-refresh"></i> <?php echo getLocaleText('NEW_TMPL_MSG_BTN_2', TXT_A); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-primary" id="btnSave">
                                                            <i class="fa fa-save"></i> <?php echo getLocaleText('NEW_TMPL_MSG_BTN_3', TXT_A); ?>
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

                </div>

            </section>
        
        <?php
        }
        else if (empty ($orgDao->getMsgType())) { ?>
            <div class="alert alert-danger fade in">
                <i class="fa-fw fa fa-times"></i>
                <?php echo getLocaleText('NEW_TMPL_MSG_17', TXT_A); ?>
            </div>
        <?php
        }
        ?>
        
        
    </div>
    
</div>
<!-- END MAIN PANEL -->

<?php
    include("inc/footer.php");
    include("../commonInc/scripts.php"); 
?>


<script>

    $(document).ready(function() {

        pageSetUp();
        
        var $validator = $("#newRptTmpl").validate({

                rules: {
                    tmplname: {
                        required: true,
                        alphanumeric: true
                    }
                },

                messages: {
                    tmplname: {
                        required: "<?php echo getLocaleText('NEW_TMPL_MSG_VALID_1', TXT_A); ?>",
                        alphanumeric: "<?php echo getLocaleText('NEW_TMPL_MSG_VALID_2', TXT_A); ?>"
                    }
                },

                highlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                },

                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } 
                    else {
                        error.insertAfter(element);
                    }
                }

            });
            
        jQuery.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[A-Za-z0-9\s`~!@#$%^&*()+={}'".\/?\\-]+$/i.test(value);
        }, "");

    
        $('#btnCancel').on('click', function () {
            location.href = 'rptTemplates.php';
        });
        
        $('#btnSave').on('click', function () {
            var $valid = $("#newRptTmpl").valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            else{
                if (validateTmplSelected()){
                    document.forms['newRptTmpl']['formsubmit'].value = 'formsubmit';
                    $("#newRptTmpl").submit();
                }
                else{
                    document.getElementById('divTmplError').style.display = 'block';
                    return false;
                }
            }
        });
        
        $('#btnReset').on('click', function () {
            $validator.resetForm();
            document.getElementById('divTmplError').style.display = 'none';
            document.getElementById('newRptTmpl').reset();
            <?php 
            if ($_POST['mode'] == ORG_TMPL_LIST_MODE_EDIT){ ?>
                toggleTmplImage('<?php echo $selectedTempalteId; ?>');
            <?php
            }
            else{?>
                toggleTmplImage('');
            <?php
            }
            ?>
        });
        
        
        <?php 
        if (!empty($selectedTempalteId)){ ?>
            document.getElementById('<?php echo $selectedTempalteId; ?>').style.border = '2px solid #4ecc13';
            document.getElementById('<?php echo $selectedTempalteId; ?>').style.borderRadius = '4px';
            document.getElementById('<?php echo $selectedTempalteId; ?>').style.paddingTop = '5px';
            document.getElementById('<?php echo $selectedTempalteId; ?>').style.paddingBottom = '5px';
        <?php 
        }
        ?>
        
    })
    
    function toggleTmplImage(divid){
        
        $('#templateContainer > div > div').map(function() {
            if (this.id != divid){
                document.getElementById(this.id).style.border = '';
                document.getElementById(this.id).style.borderRadius = '0';
                document.getElementById(this.id).style.paddingTop = '0';
                document.getElementById(this.id).style.paddingBottom = '0';
            }
        });
        
        document.getElementById('divTmplError').style.display = 'none';
        
        if (divid != ''){
        
            if (document.getElementById(divid).style.border == ''){

                document.getElementById(divid).style.border = '2px solid #4ecc13';
                document.getElementById(divid).style.borderRadius = '4px';
                document.getElementById(divid).style.paddingTop = '5px';
                document.getElementById(divid).style.paddingBottom = '5px';

                document.getElementById('templateId').value = divid;
            }
            else{
                document.getElementById(divid).style.border = '';
                document.getElementById(divid).style.borderRadius = '0';
                document.getElementById(divid).style.paddingTop = '0';
                document.getElementById(divid).style.paddingBottom = '0';

                document.getElementById('templateId').value = '';
            }
        }
        
    }
    
    function validateTmplSelected(){
        var flag = false;
        
        $('#templateContainer > div > div').map(function() {
            if (document.getElementById(this.id).style.border != ''){
                flag = true;
            }
        });
        
        return flag;
        
    }
    
    function checkPreview(path, name){
        window.open('preview.php?tmplFile='+path+'&name='+name,'name','top=60,left=250,width=1250,height=850,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1');
        return false;
    }
    
</script>


<?php

    function preInputsInspection(OrgInputs $orgInputs){
        
        $orgInputs->setIdorgs(mysql_real_escape_string($_POST['orgid']));
        $orgInputs->setIn_templateName(mysql_real_escape_string($_POST['tmplname']));
        $orgInputs->setIn_templateId(mysql_real_escape_string($_POST['templateId']));
        
    }
    
?>