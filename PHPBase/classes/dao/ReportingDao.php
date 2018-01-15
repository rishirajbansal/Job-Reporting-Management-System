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
require_once(dirname(__FILE__) . "/Dao.php");
require_once(dirname(__FILE__) . "/../dao/UserOrgDao.php");
require_once(dirname(__FILE__) . "/../inputs/OrgInputs.php");
require_once(dirname(__FILE__) . "/../vo/DynaFields.php");
require_once(dirname(__FILE__) . "/../vo/OrgRptTemplate.php");
require_once(dirname(__FILE__) . "/../vo/OrgDynaReportStruct.php");
require_once(dirname(__FILE__) . "/../vo/Organization.php");


/**
 * Description of ReportingDao
 *
 * @author Rishi Raj
 */
class ReportingDao extends Dao{
    
    private $logger;
    
    private $userEntities;
    private $orgTemplateDetails;
    private $reportDetails;
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
        parent::__construct();
        
        $this->logger = Logger::getRootLogger();
        
        $this->errors = array();
        $this->messages = array();
        
    }
    
    function __destruct() {
        /*Commenting it as it was preventing to get the mysql link in UserOrgDao for publishing from Ui*/
        //parent::__destruct();
    }
    
    
    
    function loadReportData($orgId, $rptId, UserOrgDao $userOrgDao){
        
        $flag = FALSE;
        
        $userOrgInputs = new UserOrgInputs();
        
        try{
            $qMan = parent::getQueryManager();

            $userOrgInputs->setIdorgs($orgId);
            $userOrgInputs->setIdrpts($rptId);

            $loadDynaDetailsFlag = $userOrgDao->fetchReportDynaDetails($userOrgInputs);
            if ($loadDynaDetailsFlag){
                $userEntities = $userOrgDao->getAllUserEntities();
                
                $fetchFlag = $userOrgDao->fetchReportDetails($userOrgInputs, ORG_USERRPT_LIST_MODE_CALL_FROM_PUBLISH_ENGINE);
            
                if ($fetchFlag){
                    $this->userEntities = $userOrgDao->getUserEntities();
                    $this->reportDetails = $userOrgDao->getReportDetails();
                    $isDetailsFetched = TRUE;
                }
                else{
                    $error = '';
                    if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_MESSAGE){
                        foreach($userOrgDao->getMessages() as $value){
                            $error .= $value;
                        }
                        array_push($this->messages,  $error);
                        $this->setMsgType(SUBMISSION_MSG_TYPE_MESSAGE);
                    }
                    else if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
                        foreach($userOrgDao->getErrors() as $value){
                            $error .= $value;
                        }
                        array_push($this->errors,  $error);
                        $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    }
                    else if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
                        $error = $userOrgDao->getCriticalError();
                        $this->setCriticalError( $error);
                        $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
                    }
                    $this->logger->error( '[Report] ' . $error);

                    return $flag;
                }
                
            }
            else{
                $error = '';
                if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
                    foreach($userOrgDao->getErrors() as $value){
                        $error .= $value;
                    }
                    array_push($this->errors,  $error);
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                }
                else if ($userOrgDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
                    $error = $userOrgDao->getCriticalError();
                    $this->setCriticalError( $error);
                    $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
                }
                $this->logger->error( '[Report] ' . $error);

                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Report] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('ReportingDao_loadReportData_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadTemplateDetails($orgId){
        
        $flag = FALSE;
        
        $orgRptTemplate = new OrgRptTemplate();
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_SELECT_ORGS_TEMPLATE_DETAILS;
            $sql = str_replace('P1', $orgId, $sql);

            $this->logger->debug('[Report] Loading Org Template Details - Query : ' . $sql);

            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $this->logger->debug('[Report] Loading details for Org Template details : '. $orgId);

                $row = $qMan->fetchSingleRow($result);

                $orgRptTemplate->setIdorgs($row['idorgs']);
                $orgRptTemplate->setTemplateName($row['template_name']);
                $orgRptTemplate->setRawTemplateId($row['rawtemplate_id']);

                $this->setOrgTemplateDetails($orgRptTemplate);
            }
            else{
                array_push($this->errors, getLocaleText('ReportingDao_loadTemplateDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Report] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('ReportingDao_loadTemplateDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadOrgDetails($orgId){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_SELECT_ORGS_DETAILS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[Report] Loading Org Details - Query : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $orgDetails = new Organization();
                $orgDetails->setName($row['name']);
                $orgDetails->setPhone($row['phone']);
                $orgDetails->setEmail($row['email']);
                $orgDetails->setUsername($row['username']);
                $orgDetails->setPassword($row['password']);
                $orgDetails->setActivated($row['activated']);
                
                $this->setOrgDetails($orgDetails);
            }
            else{
                array_push($this->errors, getLocaleText('ReportingDao_loadOrgDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Report] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('ReportingDao_loadOrgDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
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
    
    public function getUserEntities() {
        return $this->userEntities;
    }

    public function setUserEntities($userEntities) {
        $this->userEntities = $userEntities;
    }

    public function getOrgTemplateDetails() {
        return $this->orgTemplateDetails;
    }

    public function setOrgTemplateDetails($orgTemplateDetails) {
        $this->orgTemplateDetails = $orgTemplateDetails;
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
