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
require_once(dirname(__FILE__) . "/../inputs/OrgInputs.php");

/**
 * Description of AdminSettingsDao
 *
 * @author Rishi Raj
 */
class AdminSettingsDao extends Dao{
    
    private $logger;
     
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
    
    
    function saveNewPassword(OrgInputs $orgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_UPDATE_SUPERADMIN_PWD;
            $sql = str_replace('P1', '\''.$orgInputs->getIn_password().'\'', $sql);
            $sql = str_replace('P2', 'NOW()', $sql);
            
            $this->logger->debug('Updating superadmin password : ' . $sql);

            $update = $qMan->update($sql);

            if (!$update){
                $this->logger->error('Failed to update the password for the superadmin, some internal error occured.');
                array_push($this->errors, getLocaleText('AdminSettingsDao_saveNewPassword_MSG_1', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('New password details of superadmin are updatd successfully.');
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            $this->setCompleteMsg(getLocaleText('AdminSettingsDao_saveNewPassword_MSG_2', TXT_A));
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('AdminSettingsDao_saveNewPassword_MSG_EX', TXT_A));
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


}
