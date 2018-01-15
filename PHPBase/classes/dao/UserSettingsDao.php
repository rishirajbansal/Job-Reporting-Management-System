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
require_once(dirname(__FILE__) . "/../inputs/UserOrgInputs.php");
require_once(dirname(__FILE__) . "/../vo/Organization.php");


/**
 * Description of UserSettingsDao
 *
 * @author Rishi Raj
 */
class UserSettingsDao extends Dao{
    
    private $logger;
    
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
        parent::__destruct();
    }
    
    
    
    function loadProfile(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            $sql = SQL_SELECT_ORGS_DETAILS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Loading User profile : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $orgDetails = new Organization();
                $orgDetails->setIdorgs($row['idorgs']);
                $orgDetails->setName($row['name']);
                $orgDetails->setPhone($row['phone']);
                $orgDetails->setEmail($row['email']);
                $orgDetails->setUsername($row['username']);
                
                $this->setOrgDetails($orgDetails);
            }
            else{
                array_push($this->errors, getLocaleText('UserSettingsDao_loadProfile_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserSettingsDao_loadProfile_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function saveNewPassword(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            $sql = SQL_UPDATE_USER_PWD;
            $sql = str_replace('P1', '\''.$userOrgInputs->getIn_password().'\'', $sql);
            $sql = str_replace('P2', 'NOW()', $sql);
            $sql = str_replace('P3', $orgId, $sql);
            
            $this->logger->debug('Updating user password : ' . $sql);

            $update = $qMan->update($sql);
            
            if (!$update){
                $this->logger->error('[[' . $orgId . ']' .  ' Failed to update the password for the user, some internal error occured.');
                array_push($this->errors, getLocaleText('UserSettingsDao_saveNewPassword_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('[' . $orgId . ']' .  ' New password details are updatd successfully.');
            $flag = TRUE;
            $this->setMsgType(UserSettingsDao_saveNewPassword_MSG_2);
            $this->setCompleteMsg(getLocaleText('OrgDao_loadDynaField', TXT_U));
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserSettingsDao_saveNewPassword_MSG_EX', TXT_U));
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
    
    public function getOrgDetails() {
        return $this->orgDetails;
    }

    public function setOrgDetails($orgDetails) {
        $this->orgDetails = $orgDetails;
    }


    
}
