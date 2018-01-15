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
require_once(dirname(__FILE__) . "/../inputs/UserOrgInputs.php");
require_once(dirname(__FILE__) . "/../vo/OrgRptQFilter.php");
include_once(dirname(__FILE__) . "/../base/Constants.php");


/**
 * Description of LoginDao
 *
 * @author Rishi Raj
 */
class LoginDao extends Dao{
    
    private $logger;
    
    private $loggedInOrgid;
    private $loggedInOrgname;
    
    private $queryFilters;
    private $loggedInQueryFilters;
     
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
    
    
    function loginAdmin(OrgInputs $orgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_SELECT_SUPERADMIN;
            $sql = str_replace('P1', '\''.$orgInputs->getIn_username().'\'', $sql);
            $sql = str_replace('P2', '\''.$orgInputs->getIn_password().'\'', $sql);
            
            $this->logger->debug('Verifying superadmin credentials : ' . $sql);

            $login = $qMan->query($sql);

            if (!$login){
                $this->logger->error('Failed to find the record for superadmin, some internal error occured.');
                array_push($this->errors, getLocaleText('LoginDao_loginAdmin_MSG_1', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            if (isset($login) && mysql_num_rows($login) > 0){
                $this->logger->debug('Credentials of superadmin are authenticatd successfully.');
                
                $sql = SQL_UPDATE_SUPERADMIN_LOGINTIME;
                $sql = str_replace('P1', 'NOW()', $sql);
                
                $this->logger->debug('Updating login time : ' . $sql);

                $update = $qMan->update($sql);
            }
            else{
                $this->logger->error('Credentials provided do not match with the records, access cannot be allowed.');
                array_push($this->errors, getLocaleText('LoginDao_loginAdmin_MSG_2', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;

        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('LoginDao_loginAdmin_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function login(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_SELECT_USER_LOGIN;
            $sql = str_replace('P1', '\''.$userOrgInputs->getIn_orgname().'\'', $sql);
            $sql = str_replace('P2', '\''.$userOrgInputs->getIn_username().'\'', $sql);
            $sql = str_replace('P3', '\''.$userOrgInputs->getIn_password().'\'', $sql);
            
            $this->logger->debug('Verifying credentials : ' . $sql);

            $login = $qMan->query($sql);

            if (!$login){
                $this->logger->error('Failed to find the record for the organization, some internal error occured.');
                array_push($this->errors, getLocaleText('LoginDao_login_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            if (isset($login) && mysql_num_rows($login) > 0){
                $this->logger->debug('Credentials of organization "' . $userOrgInputs->getIn_orgname() . '" are authenticatd successfully.');
                
                $row = $qMan->fetchSingleRow($login);
                $this->setLoggedInOrgid($row['idorgs']);
                $this->setLoggedInOrgname($row['name']);
                
                //Veify if user is activated or not
                $activStatus = $row['activated'];
                if ($activStatus == ORG_ACTIVATED){
                    $this->logger->debug('User is activated and allowed to acess the application.');
                }
                else{
                    $this->logger->error('User account is deactivated, access cannot be allowed.');
                    array_push($this->messages, getLocaleText('LoginDao_login_MSG_2', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_MESSAGE);
                    return $flag;
                }
                
                $sql = SQL_UPDATE_USER_LOGINTIME;
                $sql = str_replace('P1', 'NOW()', $sql);
                $sql = str_replace('P2', '\''.$userOrgInputs->getIn_orgname().'\'', $sql);
                
                $this->logger->debug('Updating user login time : ' . $sql);

                $update = $qMan->update($sql);
                
                $this->loadOrgQueryFilters($row['idorgs']);
                $this->loadAllQueryFilters();
            }
            else{
                $this->logger->error('Credentials provided do not match with the records, access cannot be allowed.');
                array_push($this->errors, getLocaleText('LoginDao_login_MSG_3', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;

        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('LoginDao_login_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadOrgQueryFilters($orgid){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_SELECT_ORGS_RPT_QFILTERS;
            $sql = str_replace('P1', $orgid, $sql);

            $this->logger->debug('Retreiving the records for configured report query filters - Query : ' . $sql);

            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                $this->setLoggedInQueryFilters($row['qfilters']);
            }
            else{
                $this->logger->debug('No records found for exists report query filters.');
                $this->setLoggedInQueryFilters('');
            }
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('LoginDao_loadOrgQueryFilters_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadAllQueryFilters(){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_SELECT_RPT_QFILTERS;

            $this->logger->debug('Retreiving the records for all report query filters - Query : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $rptQFilters = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    
                    $orgRptQFilter = new OrgRptQFilter();
                    $orgRptQFilter->setIdRptsFilters($row['idrpts_qfilters']);
                    $orgRptQFilter->setQfilterId($row['qfilter_id']);
                    $orgRptQFilter->setQfilterNameEn($row['qfilter_name_en']);
                    $orgRptQFilter->setQfilterNameEs($row['qfilter_name_es']);
                    $orgRptQFilter->setQfilterDescEn($row['qfilter_desc_en']);
                    $orgRptQFilter->setQfilterDescEs($row['qfilter_desc_es']);
                    $orgRptQFilter->setPfile($row['pfile']);
                    $orgRptQFilter->setCreatedOn($row['created_on']);
                    $orgRptQFilter->setLastUpdated($row['last_updated']);
                    
                    $orgRptQFilter->setQfilterName(setLocaleDynaText($row['qfilter_name_en'], $row['qfilter_name_es']));
                    $orgRptQFilter->setQfilterDesc(setLocaleDynaText($row['qfilter_desc_en'], $row['qfilter_desc_es']));
                    
                    array_push($rptQFilters, $orgRptQFilter);
                }
                
                $record = array();
                
                //This record array is used in menu and dashboard Query Filter widget
                foreach ($rptQFilters as $orgRptQFilter){
                    $record[$orgRptQFilter->getQfilterId()] = array(
                                    "title" => $orgRptQFilter->getQfilterName(),
                                    "icon" => "fa-filter",
                                    "url" => $orgRptQFilter->getPfile()
                                );
                }
                
                $this->setQueryFilters($record);
                
            }
            else{
                array_push($this->errors, getLocaleText('LoginDao_loadAllQueryFilters_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('LoginDao_loadAllQueryFilters_MSG_EX', TXT_U));
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
    
    public function getLoggedInOrgid() {
        return $this->loggedInOrgid;
    }

    public function setLoggedInOrgid($loggedInOrgid) {
        $this->loggedInOrgid = $loggedInOrgid;
    }

    public function getLoggedInOrgname() {
        return $this->loggedInOrgname;
    }

    public function setLoggedInOrgname($loggedInOrgname) {
        $this->loggedInOrgname = $loggedInOrgname;
    }

    public function getQueryFilters() {
        return $this->queryFilters;
    }

    public function setQueryFilters($queryFilters) {
        $this->queryFilters = $queryFilters;
    }

    public function getLoggedInQueryFilters() {
        return $this->loggedInQueryFilters;
    }

    public function setLoggedInQueryFilters($loggedInQueryFilters) {
        $this->loggedInQueryFilters = $loggedInQueryFilters;
    }


}
