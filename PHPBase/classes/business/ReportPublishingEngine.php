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


require_once(dirname(__FILE__) . "/../common.php");
require_once(dirname(__FILE__) . "/../../config/publisherConfig.php");
require_once(dirname(__FILE__) . "/../inputs/UserOrgInputs.php");
require_once(dirname(__FILE__) . "/../dao/ReportingDao.php");
require_once(dirname(__FILE__) . "/../vo/OrgRptTemplate.php");
require_once(dirname(__FILE__) . "/../vo/UserEntity.php");
require_once(dirname(__FILE__) . "/../../lib/mpdf60/mpdf.php");
require_once(dirname(__FILE__) . "/../business/EmailNotifications.php");
require_once(dirname(__FILE__) . "/../vo/Organization.php");



/**
 * Description of ReportPublishingEngine
 *
 * @author Rishi Raj
 */
class ReportPublishingEngine {
    
    private $logger;
    
    private $userEntities;
    private $orgRptTemplate;
    private $returnCallerMsg;
    private $reportDetails;
    
    private $templatesMap;
    private $orgDetails;
    
    private $errors;
    private $messages;
    private $successMessage;
    private $completeMsg;
    private $criticalError;
    
    /*
     * 1 for simple success message
     * 2 for Complete success Final
     * 3 for array based messages
     * 4 for array based errors
     * 5 for Critical errors
     */
    private $msgType;
    
    
    function __construct() {
        
        $this->logger = Logger::getRootLogger();
        
        $this->errors = array();
        $this->messages = array();
        
        $this->templatesMap = array(
                       'tmpl1' => RPT_TEMPLATE_MODEL_1,
                       'tmpl2' => RPT_TEMPLATE_MODEL_2
                       );
        
    }

    
    function handlePublishing($orgId, $rptId, $userOrgDao = null){
        
        $flag = FALSE;
        
        $reportingDao = new ReportingDao();
        
        try{
            //1. Get the template for the org
            $loadTemplateFlag = $this->loadTemplateData($orgId, $reportingDao);
            if (!$loadTemplateFlag){
                return $flag;
            }
            
            //2. Get the org details
            $loadOrgFlag = $this->loadOrgData($orgId, $reportingDao);
            if (!$loadOrgFlag){
                return $flag;
            }
            
            //3. Get the report details from the data store
            if (null == $userOrgDao){
                $userOrgDao = new UserOrgDao();
                $this->logger->debug('[Report] Instantiated UserOrgdao.');
                
                $loadFlag = $this->loadReportData($orgId, $rptId, $reportingDao, $userOrgDao);
                if (!$loadFlag){
                    return $flag;
                }
            }
            else{
                $this->userEntities = $userOrgDao->getUserEntities();
                $this->reportDetails = $userOrgDao->getReportDetails();
            }
            
            //4. Generate Publishing document filename
            $orgDir = dirname(__FILE__) . '/../../web/admin/orgdata/' . 'org_' . $orgId;
            $reportsDir = $orgDir . '/reports';
            $reportsDir = $reportsDir . '/' . REPORT_NAMING_FOLDERNAME_PREFIX . $rptId . '/';
            if (!file_exists($reportsDir)) {
                mkdir($reportsDir, 0777, true);
            }
            $documentFile = $reportsDir . REPORT_NAMING_PREFIX . $rptId . '.pdf';
            if (file_exists($documentFile)){
                $this->logger->debug('[Report] Report with the name already exist, it will be deleted :' . REPORT_NAMING_PREFIX . $rptId . '.pdf');
                unlink($documentFile);
            }
            
            //5. Set Org Logo path
            $logoDir = $orgDir . '/logo/';
            $logo = '';
            if ($handle = opendir($logoDir)){
                while (false !== ($entry = readdir($handle))) {
                    if (strpos($entry, ORG_FOLDER_LOGO) !== FALSE) {
                        $logo = $entry;
                        break;
                    }
                }
                closedir($handle);
            }
            if (!empty($logo)){
                $logo = $logoDir . $logo;
            }
            else{
                $this->logger->debug('[Report] No logo defined for this org');
            }
            
            //6. Publish Report based on the matched template
            $templateId = $this->getOrgRptTemplate()->getRawTemplateId();
            
            $this->publishJobReportDocument($templateId, $documentFile, $reportsDir, $logo);
            $this->logger->debug('[Report] Report Published successfully.');
            
            //7. Dispatch Report
            $dispatchFlag = $this->dispatchReport($documentFile);
            if ($dispatchFlag){
                $this->logger->debug('[Report] Report Dispatched successfully.');
            }
            else{
                $this->returnCallerMsg = getLocaleText('ReportPublishingEngine_handlePublishing_MSG_1', TXT_U) . $this->returnCallerMsg;
                return $flag;
            }
            
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Report] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('ReportPublishingEngine_handlePublishing_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
            $this->returnCallerMsg = getLocaleText('ReportPublishingEngine_handlePublishing_MSG_2', TXT_U) . $ex->getMessage();
        }
        
        return $flag;
        
        
    }
    
    function loadReportData($orgId, $rptId, ReportingDao $reportingDao, UserOrgDao $userOrgDao){
        
        $loadFlag = $reportingDao->loadReportData($orgId, $rptId, $userOrgDao);
            
        if ($loadFlag){
            $this->logger->debug('[Report] Report data loaded successfully.');
            $this->userEntities = $reportingDao->getUserEntities();
            $this->reportDetails = $reportingDao->getReportDetails();
        }
        else{
            $this->logger->error('[Report] Failed to load the report data details');

            $error = '';
            if ($reportingDao->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
                foreach($reportingDao->getMessages() as $value){
                    $error .= $value;
                }
            }
            else if ($reportingDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
                foreach($reportingDao->getErrors() as $value){
                    $error .= $value;
                }
            }
            else if ($reportingDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
                $error = $reportingDao->getCriticalError();
            }

            $this->returnCallerMsg = getLocaleText('ReportPublishingEngine_loadReportData_MSG_1', TXT_U) . $error;
        }
        
        return $loadFlag;
                
    }
    
    function loadTemplateData($orgId, ReportingDao $reportingDao){
        
        $loadTemplateFlag = $reportingDao->loadTemplateDetails($orgId);
        
        if ($loadTemplateFlag){
            $this->logger->debug('[Report] Report template loaded successfully.');
            $this->orgRptTemplate = $reportingDao->getOrgTemplateDetails();
        }
        else{
            $this->logger->error('[Report] Failed to load the report template details');

            $error = '';
            if ($reportingDao->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
                foreach($reportingDao->getMessages() as $value){
                    $error .= $value;
                }
            }
            else if ($reportingDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
                foreach($reportingDao->getErrors() as $value){
                    $error .= $value;
                }
            }
            else if ($reportingDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
                $error = $reportingDao->getCriticalError();
            }

            $this->returnCallerMsg = getLocaleText('ReportPublishingEngine_loadTemplateData_MSG_1', TXT_U) . $error;
        }
        
        return $loadTemplateFlag;
        
    }
    
    function loadOrgData($orgId, ReportingDao $reportingDao){
        
        $loadOrgFlag = $reportingDao->loadOrgDetails($orgId);
        
        if ($loadOrgFlag){
            $this->logger->debug('[Report] Org Details loaded successfully.');
            $this->orgDetails = $reportingDao->getOrgDetails();
        }
        else{
            $this->logger->error('[Report] Failed to load the Org details');

            $error = '';
            if ($reportingDao->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
                foreach($reportingDao->getMessages() as $value){
                    $error .= $value;
                }
            }
            else if ($reportingDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
                foreach($reportingDao->getErrors() as $value){
                    $error .= $value;
                }
            }
            else if ($reportingDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
                $error = $reportingDao->getCriticalError();
            }

            $this->returnCallerMsg = getLocaleText('ReportPublishingEngine_loadOrgData_MSG_1', TXT_U) . $error;
        }
        
        return $loadOrgFlag;
        
    }
        
    
    function publishJobReportDocument($templateId, $file, $reportPath, $logo){
        
        try{
            $templateModel = $this->templatesMap[$templateId];
            
            $layoutContents = $this->getTemplateLayoutContent($templateId);
            
            switch ($templateModel){
                
                case RPT_TEMPLATE_MODEL_1:
                    
                    $layoutContents = $this->populateLayoutModel1($layoutContents, $reportPath);
                    break;
                
                case RPT_TEMPLATE_MODEL_2:
                    
                    $layoutContents = $this->populateLayoutModel2($layoutContents, $reportPath);
                    break;
                
                default :
                    
                    $this->logger->error('Invalid Template model type');
                    break;
                
            }
            
            $layoutContents = $this->replaceEmptyPlaceHolders($layoutContents);
            
            
            $document = $this->createDocumentInstance();
            
            $this->configureDocumentMetadata($document);
            $this->setDocumentBasicProperties($document);
            $this->setDocumentHeaderFooter($document, $logo);
            
            $this->writeDocument($document, $layoutContents);
            
            //$document->AddPage();
            //$this->addPhoto($document, $reportPath);
            
            $this->renderOutput($document, $file);
            
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Report] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('ReportPublishingEngine_publishJobReportDocument_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
            $this->returnCallerMsg = 'publishJobReportDocument: ' . getLocaleText('ReportPublishingEngine_publishJobReportDocument_MSG_1', TXT_U) . $ex->getMessage();
        }
        
    }
    
    function getTemplateLayoutContent($tmplId){
        
        $locale = getLocale();
        
        $layoutPath = dirname(__FILE__) . "/../../web/pTemplates/" . $tmplId . TEMPLATE_LAYOUT_FILENAME_SUFFIX . $locale . TEMPLATE_EXT;
        $layoutContents = file_get_contents($layoutPath);
        
        return $layoutContents;
        
    }
    
    
    function createDocumentInstance(){
        
        $document = new mPDF('', PDF_PAGE_FORMAT, '', '', PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT,PDF_MARGIN_TOP, PDF_MARGIN_BOTTOM, PDF_MARGIN_HEADER, PDF_MARGIN_FOOTER, PDF_PAGE_ORIENTATION);
        
        return $document;
        
    }
    
    function configureDocumentMetadata(mPDF $document){
        
        $document->SetTitle(PDF_TITLE);
        $document->SetAuthor(PDF_AUTHOR);
        $document->SetCreator(PDF_CREATOR);
        $document->SetSubject(PDF_SUBJECT);
        
    }
    
    function setDocumentBasicProperties(mPDF $document){
        
        $document->autoPageBreak = TRUE;
        
    }
    
    function setDocumentHeaderFooter(mPDF $document, $logo){
        
        $locale = getLocale();
        
        $headerLayout = dirname(__FILE__) . "/../../web/pTemplates/" . HEADER_LAYOUT_FILENAME . $locale . TEMPLATE_EXT;
        $headerLayoutContents = file_get_contents($headerLayout);
        
        if (!empty($logo)){
            if ($locale == LOCALE_ESP_SP){
                $logoContent = "<img src=\"" . $logo . "\" style=\"width: 110px;height: 36px;margin-left: 210px\">";
                $headerLayoutContents = str_replace('{LOGO}', $logoContent, $headerLayoutContents);
            }
            else{
                $logoContent = "<img src=\"" . $logo . "\" style=\"width: 110px;height: 36px;margin-left: 260px\">";
                $headerLayoutContents = str_replace('{LOGO}', $logoContent, $headerLayoutContents);
            }
        }
        else{
            $headerLayoutContents = str_replace('{LOGO}', '', $headerLayoutContents);
        }
        
        $footerLayout = dirname(__FILE__) . "/../../web/pTemplates/" . FOOTER_LAYOUT_FILENAME . $locale . TEMPLATE_EXT;
        $footerLayoutContents = file_get_contents($footerLayout);
        
        $document->SetHTMLHeader($headerLayoutContents);
        $document->SetHTMLFooter($footerLayoutContents);
        
    }
    
    function writeDocument(mPDF $document, $output){
        
        $stylesheet = file_get_contents(dirname(__FILE__) . "/../../web/css/publish.css");
        $document->WriteHTML($stylesheet, 1);
        
        $document->WriteHTML($output);
        
    }
    
    function renderOutput(mPDF $document, $file){
        
        $document->Output($file, 'F');
        
    }
    
    function addPhoto($layoutContents, $reportPath){
        
        $photo = '';
        
        if ($handle = opendir($reportPath)){
            while (false !== ($entry = readdir($handle))) {
                if (strpos($entry, REPORT_DATA_IMAGE_TYPE_PHOTO_PREFIX) !== FALSE) {
                    $photo = $entry;
                    break;
                }
            }
            closedir($handle);
        }
        
        if (!empty($photo)){
            $photo = $reportPath . $photo;
            
            $photoContent = "<img src=\"" . $photo . "\" style=\"width=auto;\">";

            $layoutContents = str_replace('{PHOTO}', $photoContent, $layoutContents);
        }
        
        return $layoutContents;
        
    }
    
    function populateLayoutModel1($layoutContents, $reportPath){
        
        $layoutContents = $this->populateSign($layoutContents, $reportPath);
        $layoutContents = $this->addPhoto($layoutContents, $reportPath);
        
        $reportEntities = $this->getUserEntities();
        $reportEntity = new UserEntity();
        $orgReport = new OrgReport();
        $orgReport = $this->getReportDetails();
        
        foreach ($reportEntities as $reportEntity){
            
            $htmlname = $reportEntity->getHtmlName();
            $dataValue = $reportEntity->getSavedValue();
            if (empty($dataValue)){
                $dataValue = '{}';
            }
            
            switch ($htmlname){
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_STARTTIME;
                    
                    $dataValue = str_replace('.', ':', $dataValue);
                    $layoutContents = str_replace('{START_TIME}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_ENDTIME;
                    
                    $dataValue = str_replace('.', ':', $dataValue);
                    $layoutContents = str_replace('{FINISH_TIME}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_SIGNER;
                    
                    $layoutContents = str_replace('{SIGNER}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_OBSERVATIONS;
                    
                    $layoutContents = str_replace('{OBSERVATIONS}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_INCIDENCES;
                    
                    $layoutContents = str_replace('{INCIDENCES}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_TASKSLIST;
                    
                    $firstRow = TRUE;
                    $taskTRContents = "<tr style=\"background: #fff;\">
                                        <td style=\"padding-top: 5px;text-align: center\">{TASK}</td>
                                        <td style=\"padding-top: 5px;text-align: center\">{TASK_VALUE}</td>
                                    </tr>";
                    $consTaskTRContents = '';
                    
                    if ($dataValue != '{}'){
                        $values = explode(FIELD_2TB_VALUES_DATASET_SEPERATOR, $dataValue);
                        
                        foreach ($values as $value){
                            $valueArr = explode(FIELD_2TB_VALUES_SEPERATOR, $value);
                            
                            if ($firstRow){
                                $firstRow = FALSE;
                                
                                $layoutContents = str_replace('{TASK}', $valueArr[0], $layoutContents);
                                $layoutContents = str_replace('{TASK_VALUE}', $valueArr[1], $layoutContents);
                                
                                continue;
                            }
                            
                            $consTaskTRContents = $consTaskTRContents . $taskTRContents;
                            $consTaskTRContents = str_replace('{TASK}', $valueArr[0], $consTaskTRContents);
                            $consTaskTRContents = str_replace('{TASK_VALUE}', $valueArr[1], $consTaskTRContents);
                            
                        }
                        
                        $layoutContents = str_replace('{TASKS_LOOP}', $consTaskTRContents, $layoutContents);
                        
                    }
                    
                    break;
                    
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_MEASUREMENTSLIST;
                    
                    $firstRow = TRUE;
                    $measurementTRContents = "<tr style=\"background: #fff;\">
                                                <td style=\"padding-top: 5px;text-align: center\">{MEASURING}</td>
                                                <td style=\"padding-top: 5px;text-align: center\">{MEASURING_VALUE}</td>
                                            </tr>";
                    $consMeasurementTRContents = '';
                    
                    if ($dataValue != '{}'){
                        $values = explode(FIELD_2TB_VALUES_DATASET_SEPERATOR, $dataValue);
                        
                        foreach ($values as $value){
                            $valueArr = explode(FIELD_2TB_VALUES_SEPERATOR, $value);
                            
                            if ($firstRow){
                                $firstRow = FALSE;
                                
                                $layoutContents = str_replace('{MEASURING}', $valueArr[0], $layoutContents);
                                $layoutContents = str_replace('{MEASURING_VALUE}', $valueArr[1], $layoutContents);
                                
                                continue;
                            }
                            
                            $consMeasurementTRContents = $consMeasurementTRContents . $measurementTRContents;
                            $consMeasurementTRContents = str_replace('{MEASURING}', $valueArr[0], $consMeasurementTRContents);
                            $consMeasurementTRContents = str_replace('{MEASURING_VALUE}', $valueArr[1], $consMeasurementTRContents);
                            
                        }
                        
                        $layoutContents = str_replace('{MEASUREMENTS_LOOP}', $consMeasurementTRContents, $layoutContents);
                        
                    }
                    
                    break;
                
                default :
                                        
                    break;
                
            }
            
        }
        
        //Replace with data store details
        $layoutContents = str_replace('{REPORT_NO}', $orgReport->getRptNo(), $layoutContents);
        $layoutContents = str_replace('{WORKER}', $orgReport->getSubBy(), $layoutContents);
        $layoutContents = str_replace('{SUB_DATE}', $orgReport->getSubDatetime(), $layoutContents);
        $layoutContents = str_replace('{CLIENT}', $orgReport->getClientname(), $layoutContents);
        $layoutContents = str_replace('{LOCATION}', $orgReport->getLocation(), $layoutContents);
        
        return $layoutContents;
        
    }
    
    function populateLayoutModel2($layoutContents, $reportPath){
        
        $layoutContents = $this->populateSign($layoutContents, $reportPath);
        $layoutContents = $this->addPhoto($layoutContents, $reportPath);
        
        $reportEntities = $this->getUserEntities();
        $reportEntity = new UserEntity();
        $orgReport = new OrgReport();
        $orgReport = $this->getReportDetails();
        
        foreach ($reportEntities as $reportEntity){
            
            $htmlname = $reportEntity->getHtmlName();
            $dataValue = $reportEntity->getSavedValue();
            if (empty($dataValue)){
                $dataValue = "{}";
            }
            
            switch ($htmlname){
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_INSURANCE:
                    
                    $layoutContents = str_replace('{INSURANCE}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_STARTTIME;
                    
                    $dataValue = str_replace('.', ':', $dataValue);
                    $layoutContents = str_replace('{START_TIME}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_ENDTIME;
                    
                    $dataValue = str_replace('.', ':', $dataValue);
                    $layoutContents = str_replace('{END_TIME}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_EXTRAS;
                    
                    $layoutContents = str_replace('{EXTRAS}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_EUROS;
                    
                    $layoutContents = str_replace('{EUROS}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_DURATION;
                    
                    $layoutContents = str_replace('{DURATION}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_DESCRIPTION;
                    
                    $layoutContents = str_replace('{DESCRIPTION}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_DATAREPORT;
                    
                    $layoutContents = str_replace('{DATA REPORT}', $dataValue, $layoutContents);
                    break;
                
                case DYNAFIELDS_FIELDID_HTMLNAME_RPT_SIGNER;
                    
                    $layoutContents = str_replace('{SIGNER}', $dataValue, $layoutContents);
                    break;
                
                default :
                                        
                    break;
                
            }
            
        }
        
        //Replace with data store details
        $layoutContents = str_replace('{REPORT_NO}', $orgReport->getRptNo(), $layoutContents);
        $layoutContents = str_replace('{WORKER}', $orgReport->getSubBy(), $layoutContents);
        $layoutContents = str_replace('{SUB_DATE}', $orgReport->getSubDatetime(), $layoutContents);
        $layoutContents = str_replace('{CLIENT}', $orgReport->getClientname(), $layoutContents);
        
        return $layoutContents;
        
    }
    
    function replaceEmptyPlaceHolders($layoutContents){
        
        $naContent = "<span style=\"color: #a84b4b;\"><i>" . getLocaleText(RPT_PUBLISHING_DOCUMENT_PLACEHOLDER_NA, TXT_A) ."</i></span>";
        $layoutContents = preg_replace('({[\w|\s]*})', $naContent, $layoutContents);
        
        return $layoutContents;
        
    }
    
    function populateSign($layoutContents, $reportPath){
        
        $sign = '';
        
        if ($handle = opendir($reportPath)){
            while (false !== ($entry = readdir($handle))) {
                if (strpos($entry, REPORT_DATA_IMAGE_TYPE_SIGN_PREFIX) !== FALSE) {
                    $sign = $entry;
                    break;
                }
            }
            closedir($handle);
        }
        
        if (!empty($sign)){
            $sign = $reportPath . $sign;
            $signContent = "<img src=\"" . $sign . "\" style=\"height: 170px;\">";
            
            $layoutContents = str_replace('{SIGN}', $signContent, $layoutContents);

        }
        
        return $layoutContents;
        
    }
    
    function dispatchReport($documentFile){
        
        $emailNotifications = new EmailNotifications();
        
        $orgReport = new OrgReport();
        $orgReport = $this->getReportDetails();
        $orgDetails = new Organization();
        $orgDetails = $this->getOrgDetails();
        
        $mailInfo = array(
                        "email"         => $orgDetails->getEmail(),
                        "orgname"       => $orgDetails->getName(),
                        "reportNo"      => $orgReport->getRptNo(),
                        "worker"        => $orgReport->getSubBy(),
                        "document"      => $documentFile
                    );
        
        $dispatchFlag = $emailNotifications->dispatchReportMail($mailInfo);
        
        if (!$dispatchFlag){
            $this->logger->error('[Report] Failed to dispatch the report');

            $error = '';
            if ($emailNotifications->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
                foreach($emailNotifications->getMessages() as $value){
                    $error .= $value;
                }
            }
            else if ($emailNotifications->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
                foreach($emailNotifications->getErrors() as $value){
                    $error .= $value;
                }
            }
            else if ($emailNotifications->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
                $error = $emailNotifications->getCriticalError();
            }

            $this->returnCallerMsg = $error;
        }
        
        return $dispatchFlag;
        
    }
    
    
    public function getErrors() {
        return $this->errors;
    }

    public function getMessages() {
        return $this->messages;
    }

    public function getSuccessMessage() {
        return $this->successMessage;
    }

    public function getCompleteMsg() {
        return $this->completeMsg;
    }

    public function getCriticalError() {
        return $this->criticalError;
    }

    public function getMsgType() {
        return $this->msgType;
    }

    public function setErrors($errors) {
        $this->errors = $errors;
    }

    public function setMessages($messages) {
        $this->messages = $messages;
    }

    public function setSuccessMessage($successMessage) {
        $this->successMessage = $successMessage;
    }

    public function setCompleteMsg($completeMsg) {
        $this->completeMsg = $completeMsg;
    }

    public function setCriticalError($criticalError) {
        $this->criticalError = $criticalError;
    }

    public function setMsgType($msgType) {
        $this->msgType = $msgType;
    }
    
    public function getReturnCallerMsg() {
        return $this->returnCallerMsg;
    }

    public function setReturnCallerMsg($returnCallerMsg) {
        $this->returnCallerMsg = $returnCallerMsg;
    }

    public function getUserEntities() {
        return $this->userEntities;
    }

    public function getOrgRptTemplate() {
        return $this->orgRptTemplate;
    }

    public function setUserEntities($userEntities) {
        $this->userEntities = $userEntities;
    }

    public function setOrgRptTemplate($orgRptTemplate) {
        $this->orgRptTemplate = $orgRptTemplate;
    }

    public function getReportDetails() {
        return $this->reportDetails;
    }

    public function setReportDetails($reportDetails) {
        $this->reportDetails = $reportDetails;
    }
    
    public function getOrgDetails() {
        return $this->orgDetails;
    }

    public function setOrgDetails($orgDetails) {
        $this->orgDetails = $orgDetails;
    }



    
}
