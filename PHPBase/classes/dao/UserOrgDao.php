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
include_once(dirname(__FILE__) . "/../vo/OrgDynaProduct.php");
include_once(dirname(__FILE__) . "/../vo/OrgDynaTask.php");
include_once(dirname(__FILE__) . "/../vo/OrgDynaWorker.php");
include_once(dirname(__FILE__) . "/../vo/OrgDynaCustomer.php");
include_once(dirname(__FILE__) . "/../vo/UserEntity.php");
include_once(dirname(__FILE__) . "/../vo/OrgReport.php");
include_once(dirname(__FILE__) . "/../vo/ReportQFilterRes.php");
include_once(dirname(__FILE__) . "/../business/ReportPublishingEngine.php");



/**
 * Description of UserOrgDao
 *
 * @author Rishi Raj
 */
class UserOrgDao extends Dao{
    
    private $logger;
    
    private $userEntities;
    private $allUserEntities;
    
    private $graphRptMonthDataSet;
    private $graphTskStatusDataSet;
    private $lastLogin;
    
    private $tableHeadPrdDetailsEn;
    private $tableHeadPrdDetailsEs;
    private $tableHeadPrdDetails;
    private $tableBodyPrdDetailsEn;
    private $totalPrds;
    
    private $tableHeadTskDetailsEn;
    private $tableHeadTskDetailsEs;
    private $tableHeadTskDetails;
    private $tableBodyTskDetailsEn;
    private $totalTsks;
    
    private $tableHeadWrkrDetailsEn;
    private $tableHeadWrkrDetailsEs;
    private $tableHeadWrkrDetails;
    private $tableBodyWrkrDetailsEn;
    private $totalWrks;
    
    private $tableHeadCstmrDetailsEn;
    private $tableHeadCstmrDetailsEs;
    private $tableHeadCstmrDetails;
    private $tableBodyCstmrDetailsEn;
    private $totalCstmrs;
    
    private $isListLoaded;
    
    private $listRpts;
    private $totalRpts;
    
    private $publishBtn;
    private $reportDetails;
    
    private $clientNamesList;
    private $workerNamesList;
    
    private $reportQFilterResList;
    private $totalServiceHrs;
    
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
    
    
    
    function fetchProductDynaDetails(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $orgDynaProduct = new OrgDynaProduct();
        $consDynaFieldIds = '';
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
             
            $sql = SQL_SELECT_ORGS_PRODUCTS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' Loading org records for Products Details - Query 1 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode(DYNAFIELD_IDS_SEPERATOR, $dynaFieldsIds);
                
                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode(FIELDID_VALUE_DATASET_SEPERATOR, $dynaFieldsListValuesEn);
                
                $dynaFieldsListValuesEs = $row['dyna_fields_list_values_es'];
                $dynaFieldsListValuesEsArr = explode('|', $dynaFieldsListValuesEs);
                
                $dynaFieldsListValuesArr = setLocaleDynaText($dynaFieldsListValuesEnArr, $dynaFieldsListValuesEsArr);
                
                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    foreach ($dynaFieldsListValuesArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValue = substr($value, strlen($dynaFieldsId.':'));
                            break;
                        }
                    }
                    $dynaFieldIdDetail = array(
                                        'fieldId' => $dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                    
                    $consDynaFieldIds = $consDynaFieldIds . $dynaFieldsId . ',';
                }
                $orgDynaProduct->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $consDynaFieldIds = substr($consDynaFieldIds, 0, strlen($consDynaFieldIds)-1);
                
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchProductDynaDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //Get the details of Dyna fields
            $sql = SQL_SELECT_DYNAFIELDS_PRODUCTS_MAPPING;
            $sql = str_replace('P1', $consDynaFieldIds, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' Loading dyna records for Products Details - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $userEntities = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    
                    $userEntity = new UserEntity();
                    
                    $this->populateDynaFieldsObj($row, DYNAFIELDS_TABLE_ID_PRODUCT, $userEntity);
                    
                    $dynaFieldsProcessedDetails = $orgDynaProduct->getDynaFieldsProcessedDetails();
                    foreach ($dynaFieldsProcessedDetails as $record){
                        if ($record['fieldId'] == $row['idprdts_fields']){
                            $listValuesArr = explode(LIST_VALUES_SEPERATOR, $record['fieldValues']);
                            $userEntity->setSelectedListValuesEn($listValuesArr);
                            break;
                        }
                    }
                    
                    //Parse the validations
                    $userEntity->setHtmlValidationsArr(explode(MULTIPLE_VALUES_SEPERATOR, $userEntity->getHtmlValidations()));
                    $userEntity->setHtmlValidationsMessagesEnArr(explode(MULTIPLE_VALUES_SEPERATOR, $userEntity->getHtmlValidationsMessagesEn()));
                    $userEntity->setHtmlValidationsMessagesEsArr(explode(MULTIPLE_VALUES_SEPERATOR, $userEntity->getHtmlValidationsMessagesEs()));
                    
                    $userEntity->setHtmlValidationsMessagesArr(setLocaleDynaText($userEntity->getHtmlValidationsMessagesEnArr(), $userEntity->getHtmlValidationsMessagesEsArr()));
                    
                    array_push($userEntities, $userEntity);
                }
                
                $this->setUserEntities($userEntities);
                
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchProductDynaDetails_MSG_2', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchProductDynaDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function fetchTaskDynaDetails(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $orgDynaTask = new OrgDynaTask();
        $consDynaFieldIds = '';
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
             
            $sql = SQL_SELECT_ORGS_TASKS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' Loading org records for Tasks Details - Query 1 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);
                
                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);
                
                $dynaFieldsListValuesEs = $row['dyna_fields_list_values_es'];
                $dynaFieldsListValuesEsArr = explode('|', $dynaFieldsListValuesEs);
                
                $dynaFieldsListValuesArr = setLocaleDynaText($dynaFieldsListValuesEnArr, $dynaFieldsListValuesEsArr);
                
                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    foreach ($dynaFieldsListValuesArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValue = substr($value, strlen($dynaFieldsId.':'));
                            break;
                        }
                    }
                    $dynaFieldIdDetail = array(
                                        'fieldId' => $dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                    
                    $consDynaFieldIds = $consDynaFieldIds . $dynaFieldsId . ',';
                }
                $orgDynaTask->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $consDynaFieldIds = substr($consDynaFieldIds, 0, strlen($consDynaFieldIds)-1);
                
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchTaskDynaDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //Get the details of Dyna fields
            $sql = SQL_SELECT_DYNAFIELDS_TASKS_MAPPING;
            $sql = str_replace('P1', $consDynaFieldIds, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' Loading dyna records for Tasks Details - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $userEntities = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    
                    $userEntity = new UserEntity();
                    
                    $this->populateDynaFieldsObj($row, DYNAFIELDS_TABLE_ID_TASK, $userEntity);
                    
                    $dynaFieldsProcessedDetails = $orgDynaTask->getDynaFieldsProcessedDetails();
                    foreach ($dynaFieldsProcessedDetails as $record){
                        if ($record['fieldId'] == $row['idtsks_fields']){
                            $listValuesArr = explode(',', $record['fieldValues']);
                            $userEntity->setSelectedListValuesEn($listValuesArr);
                            break;
                        }
                    }
                    
                    //Parse the validations
                    $userEntity->setHtmlValidationsArr(explode('|', $userEntity->getHtmlValidations()));
                    $userEntity->setHtmlValidationsMessagesEnArr(explode('|', $userEntity->getHtmlValidationsMessagesEn()));
                    $userEntity->setHtmlValidationsMessagesEsArr(explode('|', $userEntity->getHtmlValidationsMessagesEs()));
                    
                    $userEntity->setHtmlValidationsMessagesArr(setLocaleDynaText($userEntity->getHtmlValidationsMessagesEnArr(), $userEntity->getHtmlValidationsMessagesEsArr()));
                    
                    array_push($userEntities, $userEntity);
                }
                
                $this->setUserEntities($userEntities);
                
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchTaskDynaDetails_MSG_2', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchTaskDynaDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function fetchWorkerDynaDetails(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $orgDynaWorker = new OrgDynaWorker();
        $consDynaFieldIds = '';
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
             
            $sql = SQL_SELECT_ORGS_WORKERS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' Loading org records for Worker Details - Query 1 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);
                
                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);
                
                $dynaFieldsListValuesEs = $row['dyna_fields_list_values_es'];
                $dynaFieldsListValuesEsArr = explode('|', $dynaFieldsListValuesEs);
                
                $dynaFieldsListValuesArr = setLocaleDynaText($dynaFieldsListValuesEnArr, $dynaFieldsListValuesEsArr);
                
                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    foreach ($dynaFieldsListValuesArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValue = substr($value, strlen($dynaFieldsId.':'));
                            break;
                        }
                    }
                    $dynaFieldIdDetail = array(
                                        'fieldId' => $dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                    
                    $consDynaFieldIds = $consDynaFieldIds . $dynaFieldsId . ',';
                }
                $orgDynaWorker->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $consDynaFieldIds = substr($consDynaFieldIds, 0, strlen($consDynaFieldIds)-1);
                
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchWorkerDynaDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //Get the details of Dyna fields
            $sql = SQL_SELECT_DYNAFIELDS_WORKERS_MAPPING;
            $sql = str_replace('P1', $consDynaFieldIds, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' Loading dyna records for Workers Details - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $userEntities = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    
                    $userEntity = new UserEntity();
                    
                    $this->populateDynaFieldsObj($row, 'idwrks_fields', $userEntity);
                    
                    $dynaFieldsProcessedDetails = $orgDynaWorker->getDynaFieldsProcessedDetails();
                    foreach ($dynaFieldsProcessedDetails as $record){
                        if ($record['fieldId'] == $row['idwrks_fields']){
                            $listValuesArr = explode(',', $record['fieldValues']);
                            $userEntity->setSelectedListValuesEn($listValuesArr);
                            break;
                        }
                    }
                    
                    //Parse the validations
                    $userEntity->setHtmlValidationsArr(explode('|', $userEntity->getHtmlValidations()));
                    $userEntity->setHtmlValidationsMessagesEnArr(explode('|', $userEntity->getHtmlValidationsMessagesEn()));
                    $userEntity->setHtmlValidationsMessagesEsArr(explode('|', $userEntity->getHtmlValidationsMessagesEs()));
                    
                    $userEntity->setHtmlValidationsMessagesArr(setLocaleDynaText($userEntity->getHtmlValidationsMessagesEnArr(), $userEntity->getHtmlValidationsMessagesEsArr()));
                    
                    array_push($userEntities, $userEntity);
                }
                
                $this->setUserEntities($userEntities);
                
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchWorkerDynaDetails_MSG_2', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchWorkerDynaDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function fetchCustomerDynaDetails(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $orgDynaCustomer = new OrgDynaCustomer();
        $consDynaFieldIds = '';
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
             
            $sql = SQL_SELECT_ORGS_CUSTOMERS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' Loading org records for Customer Details - Query 1 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);
                
                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);
                
                $dynaFieldsListValuesEs = $row['dyna_fields_list_values_es'];
                $dynaFieldsListValuesEsArr = explode('|', $dynaFieldsListValuesEs);
                
                $dynaFieldsListValuesArr = setLocaleDynaText($dynaFieldsListValuesEnArr, $dynaFieldsListValuesEsArr);
                
                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    foreach ($dynaFieldsListValuesArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValue = substr($value, strlen($dynaFieldsId.':'));
                            break;
                        }
                    }
                    $dynaFieldIdDetail = array(
                                        'fieldId' => $dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                    
                    $consDynaFieldIds = $consDynaFieldIds . $dynaFieldsId . ',';
                }
                $orgDynaCustomer->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $consDynaFieldIds = substr($consDynaFieldIds, 0, strlen($consDynaFieldIds)-1);
                
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchCustomerDynaDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //Get the details of Dyna fields
            $sql = SQL_SELECT_DYNAFIELDS_CUSTOMERS_MAPPING;
            $sql = str_replace('P1', $consDynaFieldIds, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' Loading dyna records for Customers Details - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $userEntities = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    
                    $userEntity = new UserEntity();
                    
                    $this->populateDynaFieldsObj($row, 'idcstmrs_fields', $userEntity);
                    
                    $dynaFieldsProcessedDetails = $orgDynaCustomer->getDynaFieldsProcessedDetails();
                    foreach ($dynaFieldsProcessedDetails as $record){
                        if ($record['fieldId'] == $row['idcstmrs_fields']){
                            $listValuesArr = explode(',', $record['fieldValues']);
                            $userEntity->setSelectedListValuesEn($listValuesArr);
                            break;
                        }
                    }
                    
                    //Parse the validations
                    $userEntity->setHtmlValidationsArr(explode('|', $userEntity->getHtmlValidations()));
                    $userEntity->setHtmlValidationsMessagesEnArr(explode('|', $userEntity->getHtmlValidationsMessagesEn()));
                    $userEntity->setHtmlValidationsMessagesEsArr(explode('|', $userEntity->getHtmlValidationsMessagesEs()));
                    
                    $userEntity->setHtmlValidationsMessagesArr(setLocaleDynaText($userEntity->getHtmlValidationsMessagesEnArr(), $userEntity->getHtmlValidationsMessagesEsArr()));
                    
                    array_push($userEntities, $userEntity);
                }
                
                $this->setUserEntities($userEntities);
                
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchCustomerDynaDetails_MSG_2', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchCustomerDynaDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    /*
     * References: ReportingDao -> loadReportData()
     */
    function fetchReportDynaDetails(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        $prdUserEntities = array();
        $tskUserEntities = array();
        $wrkUserEntities = array();
        $cstmrUserEntities = array();
        $rptUserEntities = array();
        
        $allUserEntities = array();
        
        try{
            $loadFlag = $this->fetchProductDynaDetails($userOrgInputs);
            if (!$loadFlag){
                return $flag;
            }
            else{
                $prdUserEntities = $this->getUserEntities();
            }
            
            $loadFlag = $this->fetchTaskDynaDetails($userOrgInputs);
            if (!$loadFlag){
                return $flag;
            }
            else{
                $tskUserEntities = $this->getUserEntities();
            }
            
            $loadFlag = $this->fetchWorkerDynaDetails($userOrgInputs);
            if (!$loadFlag){
                return $flag;
            }
            else{
                $wrkUserEntities = $this->getUserEntities();
            }
            
            $loadFlag = $this->fetchCustomerDynaDetails($userOrgInputs);
            if (!$loadFlag){
                return $flag;
            }
            else{
                $cstmrUserEntities = $this->getUserEntities();
            }
            
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
             
            $sql = SQL_SELECT_ORGS_RPT_STRUCT;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (fetchReportDynaDetails) Loading org records for Report Details - Query : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $prdDynaFieldsIds = $row['prds_dyna_fields'];
                $prdDynaFieldsIdsArr = explode(DYNAFIELD_IDS_SEPERATOR, $prdDynaFieldsIds);
                
                foreach ($prdUserEntities as $userEntity){
                    $dynaId = $userEntity->getIdDynaFields();
                    if (in_array($dynaId, $prdDynaFieldsIdsArr)){
                        $userEntity->setIdDynaFields(DYNAFIELDS_PRODUCT_CBID_PREFIX.$dynaId);
                        array_push($allUserEntities, $userEntity);
                    }
                }
                
                $tskDynaFieldsIds = $row['tsks_dyna_fields'];
                $tskDynaFieldsIdsArr = explode(DYNAFIELD_IDS_SEPERATOR, $tskDynaFieldsIds);
                
                foreach ($tskUserEntities as $userEntity){
                    $dynaId = $userEntity->getIdDynaFields();
                    if (in_array($dynaId, $tskDynaFieldsIdsArr)){
                        $userEntity->setIdDynaFields(DYNAFIELDS_TASK_CBID_PREFIX.$dynaId);
                        array_push($allUserEntities, $userEntity);
                    }
                }
                
                $wrkDynaFieldsIds = $row['wrks_dyna_fields'];
                $wrkDynaFieldsIdsArr = explode(DYNAFIELD_IDS_SEPERATOR, $wrkDynaFieldsIds);
                
                foreach ($wrkUserEntities as $userEntity){
                    $dynaId = $userEntity->getIdDynaFields();
                    if (in_array($dynaId, $wrkDynaFieldsIdsArr)){
                        $userEntity->setIdDynaFields(DYNAFIELDS_WORKER_CBID_PREFIX.$dynaId);
                        array_push($allUserEntities, $userEntity);
                    }
                }
                
                $cstDynaFieldsIds = $row['cstmrs_dyna_fields'];
                $cstDynaFieldsIdsArr = explode(DYNAFIELD_IDS_SEPERATOR, $cstDynaFieldsIds);
                
                foreach ($cstmrUserEntities as $userEntity){
                    $dynaId = $userEntity->getIdDynaFields();
                    if (in_array($dynaId, $cstDynaFieldsIdsArr)){
                        $userEntity->setIdDynaFields(DYNAFIELDS_CUSTOMER_CBID_PREFIX.$dynaId);
                        array_push($allUserEntities, $userEntity);
                    }
                }
                
                //Load Report fields
                $rptDynaFieldsIds = $row['rpts_dyna_fields'];
                $rptDynaFieldsIdsArr = explode(DYNAFIELD_IDS_SEPERATOR, $rptDynaFieldsIds);
                $consDynaFieldIds = '';
                foreach ($rptDynaFieldsIdsArr as $dynaFieldsId) {
                    $consDynaFieldIds = $consDynaFieldIds . $dynaFieldsId . ',';
                }
                $consDynaFieldIds = substr($consDynaFieldIds, 0, strlen($consDynaFieldIds)-1);
                
                $sql = SQL_SELECT_DYNAFIELDS_RPT_MAPPING;
                $sql = str_replace('P1', $consDynaFieldIds, $sql);

                $this->logger->debug('[' . $orgId . ']' .  ' (fetchReportDynaDetails) Loading dyna records for Report Details - Query : ' . $sql);

                $result = $qMan->query($sql);
                if (isset($result) && mysql_num_rows($result) > 0){

                    while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

                        $userEntity = new UserEntity();

                        $this->populateDynaFieldsObj($row, DYNAFIELDS_TABLE_ID_REPORT, $userEntity);
                        
                        if (!empty($userEntity->getHtmlListValues())){
                            $userEntity->setSelectedListValuesEn(explode('|', $userEntity->getHtmlListValues()));
                        }
                        

                        //Parse the validations
                        $userEntity->setHtmlValidationsArr(explode('|', $userEntity->getHtmlValidations()));
                        $userEntity->setHtmlValidationsMessagesEnArr(explode('|', $userEntity->getHtmlValidationsMessagesEn()));
                        $userEntity->setHtmlValidationsMessagesEsArr(explode('|', $userEntity->getHtmlValidationsMessagesEs()));
                        
                        $userEntity->setHtmlValidationsMessagesArr(setLocaleDynaText($userEntity->getHtmlValidationsMessagesEnArr(), $userEntity->getHtmlValidationsMessagesEsArr()));

                        array_push($rptUserEntities, $userEntity);
                    }

                }
                else{
                    array_push($this->errors, getLocaleText('UserOrgDao_fetchReportDynaDetails_MSG_1', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
                
                foreach ($rptUserEntities as $userEntity){
                    $dynaId = $userEntity->getIdDynaFields();
                    $userEntity->setIdDynaFields(DYNAFIELDS_REPORTING_CBID_PREFIX.$dynaId);
                    array_push($allUserEntities, $userEntity);
                }
                
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchReportDynaDetails_MSG_2', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->setAllUserEntities($allUserEntities);
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchReportDynaDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function fetchAllDynaFields(UserOrgInputs $userOrgInput){
        
        $flag = FALSE;
        $allUserEntities = array();
        
        try{
            $loadFlag = $userOrgDao->fetchProductDynaDetails($userOrgInputs);
            if (!$loadFlag){
                return $flag;
            }
            else{
                $userEntities = $userOrgDao->getUserEntities();
                $allUserEntities = array_merge($allUserEntities, $userEntities);
            }
            
            $loadFlag = $userOrgDao->fetchTaskDynaDetails($userOrgInputs);
            if (!$loadFlag){
                return $flag;
            }
            else{
                $userEntities = $userOrgDao->getUserEntities();
                $allUserEntities = array_merge($allUserEntities, $userEntities);
            }
            
            $loadFlag = $userOrgDao->fetchWorkerDynaDetails($userOrgInputs);
            if (!$loadFlag){
                return $flag;
            }
            else{
                $userEntities = $userOrgDao->getUserEntities();
                $allUserEntities = array_merge($allUserEntities, $userEntities);
            }
            
            $loadFlag = $userOrgDao->fetchCustomerDynaDetails($userOrgInputs);
            if (!$loadFlag){
                return $flag;
            }
            else{
                $userEntities = $userOrgDao->getUserEntities();
                $allUserEntities = array_merge($allUserEntities, $userEntities);
            }
            
            $this->setAllUserEntities($allUserEntities);
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchAllDynaFields_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
        
    
    function saveNewProduct(UserOrgInputs $userOrgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            if ($mode == USERDYNAFIELDS_LIST_MODE_NEW){
                $sql = SQL_INSERT_USER_ORGS_PRDS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', '\''.$userOrgInputs->getPostedIdValues().'\'', $sql);
                $sql = str_replace('P3', 'NOW()', $sql);

                $this->logger->debug('[' . $orgId . ']' . ' (saveNewProduct) Saving new Prodcut Details - Query : ' . $sql);

                $newPrdId = $qMan->queryInsertAndGetId($sql);

                if (!$newPrdId){
                    $this->logger->error('[' . $orgId . ']' . ' (saveNewProduct) Failed to insert the new product details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('UserOrgDao_saveNewProduct_MSG_1', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }

                $this->logger->debug('[' . $orgId . ']' . ' New Product Id generated for new record : ' . $newPrdId);

                $this->logger->debug('[' . $orgId . ']' . ' Details of new Product are saved successfully. New Product is added in records successfully. ');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('UserOrgDao_saveNewProduct_MSG_2', TXT_U));
            }
            else if ($mode == USERDYNAFIELDS_LIST_MODE_EDIT){
                $sql = SQL_UPDATE_USER_ORGS_PRDS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', '\''.$userOrgInputs->getPostedIdValues().'\'', $sql);
                $sql = str_replace('P3', 'NOW()', $sql);
                $sql = str_replace('P4', $userOrgInputs->getIdprds(), $sql);
                
                $this->logger->debug('[' . $orgId . ']' . ' (saveNewProduct) Updating Prodcut Details - Query : ' . $sql);
                
                $update = $qMan->update($sql);
                
                if (!$update){
                    $this->logger->error('[' . $orgId . ']' . ' (saveNewProduct) Failed to update the product details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('UserOrgDao_saveNewProduct_MSG_3', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
                
                $this->logger->debug('[' . $orgId . ']' . ' New details of product are updated successfully.');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('UserOrgDao_saveNewProduct_MSG_4', TXT_U));
                
            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, getLocaleText('UserOrgDao_saveNewProduct_MSG_5', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_saveNewProduct_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function saveNewTask(UserOrgInputs $userOrgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            if ($mode == USERDYNAFIELDS_LIST_MODE_NEW){
                $sql = SQL_INSERT_USER_ORGS_TSKS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', '\''.$userOrgInputs->getPostedIdValues().'\'', $sql);
                $sql = str_replace('P3', 'NOW()', $sql);

                $this->logger->debug('[' . $orgId . ']' . ' (saveNewTask) Saving new Task Details - Query : ' . $sql);

                $newTskId = $qMan->queryInsertAndGetId($sql);

                if (!$newTskId){
                    $this->logger->error('[' . $orgId . ']' . ' (saveNewTask) Failed to insert the new task details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('UserOrgDao_saveNewTask_MSG_1', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }

                $this->logger->debug('[' . $orgId . ']' . ' New Task Id generated for new record : ' . $newTskId);

                $this->logger->debug('[' . $orgId . ']' . ' Details of new Task are saved successfully. New Task is added in records successfully. ');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('UserOrgDao_saveNewTask_MSG_2', TXT_U));
            }
            else if ($mode == USERDYNAFIELDS_LIST_MODE_EDIT){
                $sql = SQL_UPDATE_USER_ORGS_TSKS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', '\''.$userOrgInputs->getPostedIdValues().'\'', $sql);
                $sql = str_replace('P3', 'NOW()', $sql);
                $sql = str_replace('P4', $userOrgInputs->getIdtsks(), $sql);
                
                $this->logger->debug('[' . $orgId . ']' . ' (saveNewTask) Updating Task Details - Query : ' . $sql);
                
                $update = $qMan->update($sql);
                
                if (!$update){
                    $this->logger->error('[' . $orgId . ']' . ' (saveNewTask) Failed to update the task details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('UserOrgDao_saveNewTask_MSG_3', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
                
                $this->logger->debug('[' . $orgId . ']' . ' New details of task are updated successfully.');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('UserOrgDao_saveNewTask_MSG_4', TXT_U));
                
            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, getLocaleText('UserOrgDao_saveNewTask_MSG_5', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_saveNewTask_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function saveNewWorker(UserOrgInputs $userOrgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            if ($mode == USERDYNAFIELDS_LIST_MODE_NEW){
                $sql = SQL_INSERT_USER_ORGS_WRKS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', '\''.$userOrgInputs->getPostedIdValues().'\'', $sql);
                $sql = str_replace('P3', 'NOW()', $sql);

                $this->logger->debug('[' . $orgId . ']' . ' (saveNewWorker) Saving new Worker Details - Query : ' . $sql);

                $newWrkrId = $qMan->queryInsertAndGetId($sql);

                if (!$newWrkrId){
                    $this->logger->error('[' . $orgId . ']' . ' (saveNewWorker) Failed to insert the new worker details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('UserOrgDao_saveNewWorker_MSG_1', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }

                $this->logger->debug('[' . $orgId . ']' . ' New Worker Id generated for new record : ' . $newWrkrId);

                $this->logger->debug('[' . $orgId . ']' . ' Details of new Worker are saved successfully. New Worker is added in records successfully. ');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('UserOrgDao_saveNewWorker_MSG_2', TXT_U));
            }
            else if ($mode == USERDYNAFIELDS_LIST_MODE_EDIT){
                $sql = SQL_UPDATE_USER_ORGS_WRKS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', '\''.$userOrgInputs->getPostedIdValues().'\'', $sql);
                $sql = str_replace('P3', 'NOW()', $sql);
                $sql = str_replace('P4', $userOrgInputs->getIdwrks(), $sql);
                
                $this->logger->debug('[' . $orgId . ']' . ' (saveNewWorker) Updating Worker Details - Query : ' . $sql);
                
                $update = $qMan->update($sql);
                
                if (!$update){
                    $this->logger->error('[' . $orgId . ']' . ' (saveNewWorker) Failed to update the worker details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('UserOrgDao_saveNewWorker_MSG_3', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
                
                $this->logger->debug('[' . $orgId . ']' . ' New details of worker are updated successfully.');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('UserOrgDao_saveNewWorker_MSG_4', TXT_U));
                
            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, getLocaleText('UserOrgDao_saveNewWorker_MSG_5', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_saveNewWorker_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function saveNewCustomer(UserOrgInputs $userOrgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            if ($mode == USERDYNAFIELDS_LIST_MODE_NEW){
                $sql = SQL_INSERT_USER_ORGS_CSTMRS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', '\''.$userOrgInputs->getPostedIdValues().'\'', $sql);
                $sql = str_replace('P3', 'NOW()', $sql);

                $this->logger->debug('[' . $orgId . ']' . ' (saveNewCustomer) Saving new Client Details - Query : ' . $sql);

                $newCstmrId = $qMan->queryInsertAndGetId($sql);

                if (!$newCstmrId){
                    $this->logger->error('[' . $orgId . ']' . ' (saveNewCustomer) Failed to insert the new client details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('UserOrgDao_saveNewCustomer_MSG_1', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }

                $this->logger->debug('[' . $orgId . ']' . ' New Client Id generated for new record : ' . $newCstmrId);

                $this->logger->debug('[' . $orgId . ']' . ' Details of new Client are saved successfully. New Client is added in records successfully. ');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('UserOrgDao_saveNewCustomer_MSG_2', TXT_U));
            }
            else if ($mode == USERDYNAFIELDS_LIST_MODE_EDIT){
                $sql = SQL_UPDATE_USER_ORGS_CSTMRS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', '\''.$userOrgInputs->getPostedIdValues().'\'', $sql);
                $sql = str_replace('P3', 'NOW()', $sql);
                $sql = str_replace('P4', $userOrgInputs->getIdcstmrs(), $sql);
                
                $this->logger->debug('[' . $orgId . ']' . ' (saveNewCustomer) Updating Client Details - Query : ' . $sql);
                
                $update = $qMan->update($sql);
                
                if (!$update){
                    $this->logger->error('[' . $orgId . ']' . ' (saveNewCustomer) Failed to update the client details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('UserOrgDao_saveNewCustomer_MSG_3', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
                
                $this->logger->debug('[' . $orgId . ']' . ' New details of client are updated successfully.');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('UserOrgDao_saveNewCustomer_MSG_4', TXT_U));
                
            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, getLocaleText('UserOrgDao_saveNewCustomer_MSG_5', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_saveNewCustomer_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function saveReport(UserOrgInputs $userOrgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            $rptId = $userOrgInputs->getIdrpts();
            
            $sql = SQL_UPDATE_USER_ORGS_RPTS;
            $sql = str_replace('P1', $orgId, $sql);
            if (!empty($userOrgInputs->getWorker())){
                $sql = str_replace('P2', '\''.$userOrgInputs->getWorker().'\'', $sql);
            }
            else{
                $sql = str_replace('P2', 'sub_by', $sql);
            }
            if (!empty($userOrgInputs->getClient())){
                $sql = str_replace('P3', '\''.$userOrgInputs->getClient().'\'', $sql);
            }
            else{
                $sql = str_replace('P3', 'clientname', $sql);
            }
            $sql = str_replace('P4', '\''.$userOrgInputs->getPostedIdValues().'\'', $sql);
            $sql = str_replace('P5', '\'|'.  date(DATEFORMAT_UPDATE_HIS).':Coordinator\'', $sql);
            $sql = str_replace('P6', 'NOW()', $sql);
            $sql = str_replace('P7', $rptId, $sql);

            $this->logger->debug('[' . $orgId . ']' . ' (saveReport) Updating Report Details - Query : ' . $sql);

            $update = $qMan->update($sql);
            
            if (!$update){
                $this->logger->error('[' . $orgId . ']' . ' (saveReport) Failed to update the Report details in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('UserOrgDao_saveReport_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }

            $this->logger->debug('[' . $orgId . ']' . ' New details of Report are updated successfully.');
            
            if ($mode == ORG_USERRPT_LIST_MODE_EDIT_PUBLISH){
                
            }
            else {
                $this->setCompleteMsg(getLocaleText('UserOrgDao_saveReport_MSG_2', TXT_U));
            }
            
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_saveReport_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function publishReport(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        try{
            $orgId = $userOrgInputs->getIdorgs();
            $rptId = $userOrgInputs->getIdrpts();
            
            $this->logger->debug('[' . $orgId . ']' . ' (publishReport) Report is being published...');
                
            $reportPublishing = new ReportPublishingEngine();

            $publishFlag = $reportPublishing->handlePublishing($orgId, $rptId, $this);

            if ($publishFlag){
                $this->logger->debug('[' . $orgId . ']' . ' (publishReport) Report Published and dispatched successfully.');
                $this->setCompleteMsg(getLocaleText('UserOrgDao_publishReport_MSG_1', TXT_U));
            }
            else{
                array_push($this->errors, $reportPublishing->getReturnCallerMsg());
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_publishReport_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadProductsList(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $consDynaFieldIds = '';
        $tableHeadPrdDetailsEn = array();        
        $tableHeadPrdDetailsEs = array();
        $tableHeadPrdDetails = array();
        $tableBodyPrdDetailsEn = array();
        $totalPrds = 0;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            //1. Get the configured products field details for this org
            $sql = SQL_SELECT_ORGS_PRODUCTS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadProductsList) Loading org records for Products Details - Query 1 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);
                
                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $consDynaFieldIds = $consDynaFieldIds . $dynaFieldsId . ',';
                }
                $consDynaFieldIds = substr($consDynaFieldIds, 0, strlen($consDynaFieldIds)-1);
            }
            
            //2. Get the dyna fields details for the corresponding fields
            $sql = SQL_SELECT_DYNAFIELDS_PRODUCTS_MAPPING;
            $sql = str_replace('P1', $consDynaFieldIds, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadProductsList) Loading dyna records for Products Details - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $tableHeadPrdDetailsEn[$row['idprdts_fields']] = $row['name_en'];
                    $tableHeadPrdDetailsEs[$row['idprdts_fields']] = $row['name_es'];
                    
                    $tableHeadPrdDetails[$row['idprdts_fields']] = setLocaleDynaText($row['name_en'], $row['name_es']);
                }
                
                $this->setTableHeadPrdDetailsEn($tableHeadPrdDetailsEn);
                $this->setTableHeadPrdDetailsEs($tableHeadPrdDetailsEs);
                $this->setTableHeadPrdDetails($tableHeadPrdDetails);
                
            }
            
            //3. Get the Product details
            $sql = SQL_SELECT_USER_ORGS_PRDS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadProductsList) Loading records for Products Details - Query  3 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $fieldIdValues = $row['field_id_values'];
                    $fieldIdValuesArr = explode(FIELDID_VALUE_DATASET_SEPERATOR, $fieldIdValues);
                    $columnValues = array();
                    foreach ($fieldIdValuesArr as $fieldIdValue){
                        $value = explode(FIELDID_VALUE_SEPERATOR, $fieldIdValue);
                        $columnValues[$value[0]] = $value[1];
                    }
                    
                    $tableBodyPrdDetailsEn[$row['idorg_prds']] = $columnValues;
                    ++$totalPrds;
                }
                $this->setTableBodyPrdDetailsEn($tableBodyPrdDetailsEn);
                
                $this->logger->debug('List of Products are loaded successfully.');
                $this->setTotalPrds($totalPrds);
                $this->setIsListLoaded(TRUE);
                $flag = TRUE;
            }
            else{               
                $this->setIsListLoaded(FALSE);
                $this->setTotalPrds(0);
                $this->logger->debug('No product exists in the system, list cannot be generated.');
                $flag = TRUE;
                return $flag;
            }
            
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_loadProductsList_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadTasksList(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $consDynaFieldIds = '';
        $tableHeadTskDetailsEn = array();
        $tableHeadTskDetailsEs = array();
        $tableHeadTskDetails = array();
        $tableBodyTskDetailsEn = array();
        $totalTsks = 0;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            //1. Get the configured tasks field details for this org
            $sql = SQL_SELECT_ORGS_TASKS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadTasksList) Loading org records for Tasks Details - Query 1 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);
                
                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $consDynaFieldIds = $consDynaFieldIds . $dynaFieldsId . ',';
                }
                $consDynaFieldIds = substr($consDynaFieldIds, 0, strlen($consDynaFieldIds)-1);
            }
            
            //2. Get the dyna fiels details for the corresponding fields
            $sql = SQL_SELECT_DYNAFIELDS_TASKS_MAPPING;
            $sql = str_replace('P1', $consDynaFieldIds, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadTasksList) Loading dyna records for Tasks Details - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $tableHeadTskDetailsEn[$row['idtsks_fields']] = $row['name_en'];
                    $tableHeadTskDetailsEs[$row['idtsks_fields']] = $row['name_es'];
                    
                    $tableHeadTskDetails[$row['idtsks_fields']] = setLocaleDynaText($row['name_en'], $row['name_es']);
                }
                
                $this->setTableHeadTskDetailsEn($tableHeadTskDetailsEn);
                $this->setTableHeadTskDetailsEs($tableHeadTskDetailsEs);
                $this->setTableHeadTskDetails($tableHeadTskDetails);
                
            }
            
            //3. Get the Task details
            $sql = SQL_SELECT_USER_ORGS_TSKS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadTasksList) Loading records for Tasks Details - Query  3 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $fieldIdValues = $row['field_id_values'];
                    $fieldIdValuesArr = explode('|', $fieldIdValues);
                    $columnValues = array();
                    foreach ($fieldIdValuesArr as $fieldIdValue){
                        $value = explode(':', $fieldIdValue);
                        $columnValues[$value[0]] = $value[1];
                    }
                    
                    $tableBodyTskDetailsEn[$row['idorg_tsks']] = $columnValues;
                    ++$totalTsks;
                }
                $this->setTableBodyTskDetailsEn($tableBodyTskDetailsEn);
                
                $this->logger->debug('List of Tasks are loaded successfully.');
                $this->setTotalTsks($totalTsks);
                $this->setIsListLoaded(TRUE);
                $flag = TRUE;
            }
            else{               
                $this->setIsListLoaded(FALSE);
                $this->setTotalTsks(0);
                $this->logger->debug('No task exists in the system, list cannot be generated.');
                $flag = TRUE;
                return $flag;
            }
            
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_loadTasksList_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadWorkersList(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $consDynaFieldIds = '';
        $tableHeadWrkrDetailsEn = array();
        $tableHeadWrkrDetailsEs = array();
        $tableHeadWrkrDetails = array();
        $tableBodyWrkrDetailsEn = array();
        $totalWrks = 0;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            //1. Get the configured worker field details for this org
            $sql = SQL_SELECT_ORGS_WORKERS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadWorkersList) Loading org records for Workers Details - Query 1 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);
                
                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $consDynaFieldIds = $consDynaFieldIds . $dynaFieldsId . ',';
                }
                $consDynaFieldIds = substr($consDynaFieldIds, 0, strlen($consDynaFieldIds)-1);
            }
            
            //2. Get the dyna fiels details for the corresponding fields
            $sql = SQL_SELECT_DYNAFIELDS_WORKERS_MAPPING;
            $sql = str_replace('P1', $consDynaFieldIds, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadWorkersList) Loading dyna records for Workers Details - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $tableHeadWrkrDetailsEn[$row['idwrks_fields']] = $row['name_en'];
                    $tableHeadWrkrDetailsEs[$row['idwrks_fields']] = $row['name_es'];
                    
                    $tableHeadWrkrDetails[$row['idwrks_fields']] = setLocaleDynaText($row['name_en'], $row['name_es']);
                }
                
                $this->setTableHeadWrkrDetailsEn($tableHeadWrkrDetailsEn);
                $this->setTableHeadWrkrDetailsEs($tableHeadWrkrDetailsEs);
                $this->setTableHeadWrkrDetails($tableHeadWrkrDetails);
                
            }
            
            //3. Get the Worker details
            $sql = SQL_SELECT_USER_ORGS_WRKS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadWorkersList) Loading records for Workers Details - Query  3 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $fieldIdValues = $row['field_id_values'];
                    $fieldIdValuesArr = explode('|', $fieldIdValues);
                    $columnValues = array();
                    foreach ($fieldIdValuesArr as $fieldIdValue){
                        $value = explode(':', $fieldIdValue);
                        $columnValues[$value[0]] = $value[1];
                    }
                    
                    $tableBodyWrkrDetailsEn[$row['idorg_wrks']] = $columnValues;
                    ++$totalWrks;
                }
                $this->setTableBodyWrkrDetailsEn($tableBodyWrkrDetailsEn);
                
                $this->logger->debug('List of Workers are loaded successfully.');
                $this->setTotalWrks($totalWrks);
                $this->setIsListLoaded(TRUE);
                $flag = TRUE;
            }
            else{               
                $this->setIsListLoaded(FALSE);
                $this->setTotalWrks(0);
                $this->logger->debug('No worker exists in the system, list cannot be generated.');
                $flag = TRUE;
                return $flag;
            }
            
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_loadWorkersList_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadCustomersList(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $consDynaFieldIds = '';
        $tableHeadCstmrDetailsEn = array();
        $tableHeadCstmrDetailsEs = array();
        $tableHeadCstmrDetails = array();
        $tableBodyCstmrDetailsEn = array();
        $totalCstmrs = 0;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            //1. Get the configured customer field details for this org
            $sql = SQL_SELECT_ORGS_CUSTOMERS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadCustomersList) Loading org records for Customers Details - Query 1 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);
                
                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $consDynaFieldIds = $consDynaFieldIds . $dynaFieldsId . ',';
                }
                $consDynaFieldIds = substr($consDynaFieldIds, 0, strlen($consDynaFieldIds)-1);
            }
            
            //2. Get the dyna fiels details for the corresponding fields
            $sql = SQL_SELECT_DYNAFIELDS_CUSTOMERS_MAPPING;
            $sql = str_replace('P1', $consDynaFieldIds, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadCustomersList) Loading dyna records for Customers Details - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $tableHeadCstmrDetailsEn[$row['idcstmrs_fields']] = $row['name_en'];
                    $tableHeadCstmrDetailsEs[$row['idcstmrs_fields']] = $row['name_es'];
                    
                    $tableHeadCstmrDetails[$row['idcstmrs_fields']] = setLocaleDynaText($row['name_en'], $row['name_es']);
                }
                
                $this->setTableHeadCstmrDetailsEn($tableHeadCstmrDetailsEn);
                $this->setTableHeadCstmrDetailsEs($tableHeadCstmrDetailsEs);
                $this->setTableHeadCstmrDetails($tableHeadCstmrDetails);
                
            }
            
            //3. Get the Customer details
            $sql = SQL_SELECT_USER_ORGS_CSTMRS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadCustomersList) Loading records for Customers Details - Query  3 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $fieldIdValues = $row['field_id_values'];
                    $fieldIdValuesArr = explode('|', $fieldIdValues);
                    $columnValues = array();
                    foreach ($fieldIdValuesArr as $fieldIdValue){
                        $value = explode(':', $fieldIdValue);
                        $columnValues[$value[0]] = $value[1];
                    }
                    
                    $tableBodyCstmrDetailsEn[$row['idorg_cstmrs']] = $columnValues;
                    ++$totalCstmrs;
                }
                $this->setTableBodyCstmrDetailsEn($tableBodyCstmrDetailsEn);
                
                $this->logger->debug('List of Customers are loaded successfully.');
                $this->setTotalCstmrs($totalCstmrs);
                $this->setIsListLoaded(TRUE);
                $flag = TRUE;
            }
            else{               
                $this->setIsListLoaded(FALSE);
                $this->setTotalCstmrs(0);
                $this->logger->debug('No customer exists in the system, list cannot be generated.');
                $flag = TRUE;
                return $flag;
            }
            
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_loadCustomersList_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadReportsList(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            $sql = SQL_SELECT_USER_ORGS_RPTS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadReportsList) Loading org records for Report Details - Query : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $this->logger->debug('List of Reports is being loaded.');
                
                $listRpts = array();
                $totalRpts = 0;
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    
                    $orgReport = new OrgReport();
                    $orgReport->setIdOrgRpts($row['idorg_rpts']);
                    $orgReport->setIdorgs($orgId);
                    $orgReport->setSubDatetime($row['sub_datetime']);
                    $orgReport->setSubBy($row['sub_by']);
                    $orgReport->setData($row['data']);
                    $orgReport->setClientname($row['clientname']);
                    $orgReport->setCoordinates($row['coordinates']);
                    $orgReport->setUpdateHistory($row['update_his']);
                    $orgReport->setRptNo(REPORT_NAMING_PREFIX.$row['idorg_rpts']);
                    
                    $location = $row['location'];
                    if (LOCATION_GPS_NOT_ENABLED == $location){
                        $orgReport->setLocation(getLocaleText(LOCATION_MSG_GPS_NOT_ENABLED, TXT_A));
                    }
                    else if (LOCATION_GPS_ENABLED == $location){
                        $orgReport->setLocation(getLocaleText(LOCATION_MSG_GPS_ENABLED, TXT_A));
                    }
                    else if (LOCATION_GEOCODE_TIMEDOUT == $location){
                        $orgReport->setLocation(getLocaleText(LOCATION_MSG_GEOCODE_TIMEDOUT, TXT_A));
                    }
                    else if (LOCATION_NO_LOCATION_FOUND == $location){
                        $orgReport->setLocation(getLocaleText(LOCATION_MSG_NO_LOCATION_FOUND, TXT_A));
                    }
                    else{
                        $orgReport->setLocation($location);
                    }
                    
                    
                    array_push($listRpts, $orgReport);
                    ++$totalRpts;
                    
                }
                
                $this->logger->debug('List of Reports are loaded successfully.');
                $this->setListRpts($listRpts);
                $this->setTotalRpts($totalRpts);
                $this->setIsListLoaded(TRUE);
                $flag = TRUE;
                
            }
            else{
                $this->setIsListLoaded(FALSE);
                $this->setTotalRpts(0);
                $this->logger->debug('No reports exists in the system, list cannot be generated.');
                $flag = TRUE;
                return $flag;
            }
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_loadReportsList_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadDashBoardDetails(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            //1. Get the Products count
            $sql = SQL_SELECT_USER_ORGS_PRDS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadDashBoardDetails) Loading records for Products - Query 1 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $totalPrds = mysql_num_rows($result);
                $this->setTotalPrds($totalPrds);
            }
            else{
                $this->setTotalPrds(0);
                $this->logger->debug('No product exists in the system.');
            }
            
            //2. Get the Tasks count
            $sql = SQL_SELECT_USER_ORGS_TSKS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadDashBoardDetails) Loading records for Tasks - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $totalTsks = mysql_num_rows($result);
                $this->setTotalTsks($totalTsks);
            }
            else{
                $this->setTotalTsks(0);
                $this->logger->debug('No task exists in the system.');
            }
            
            //3. Get the Workers count
            $sql = SQL_SELECT_USER_ORGS_WRKS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadDashBoardDetails) Loading records for Workers - Query 3 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $totalWrks = mysql_num_rows($result);
                $this->setTotalWrks($totalWrks);
            }
            else{
                $this->setTotalWrks(0);
                $this->logger->debug('No worker exists in the system.');
            }
            
            //4. Get the Customers count
            $sql = SQL_SELECT_USER_ORGS_CSTMRS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadDashBoardDetails) Loading records for Customers - Query 4 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $totalCstmrs = mysql_num_rows($result);
                $this->setTotalCstmrs($totalCstmrs);
            }
            else{
                $this->setTotalCstmrs(0);
                $this->logger->debug('No customer exists in the system.');
            }
            
            //5. Get the Reports count
            $sql = SQL_SELECT_USER_ORGS_RPTS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadDashBoardDetails) Loading records for Report - Query 5 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $totalRpts = mysql_num_rows($result);
                $this->setTotalRpts($totalRpts);
            }
            else{
                $this->setTotalRpts(0);
                $this->logger->debug('No reports exists in the system.');
            }
            
            //6. Count the reports for the current year group by month
            $monthDataSetArr = $this->createMonthDataSetForGraph();
            
            $sql = SQL_SELECT_USER_RPT_GRAPH_MONTHLY;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadDashBoardDetails) Loading records for reports for the current year group by month - Query 6 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $count = $row['count'];
                    $month = $row['month'];
                    
                    $monthDataSetArr[$month] = $count;
                }
            }
            else{
                $this->logger->debug('No reports exists in the system for which graph can be created.');
            }
            $dataSet = '';
            foreach ($monthDataSetArr as $key => $value){
                $dataSet = $dataSet . $value . ', ' ;
            }
            if (!empty($dataSet)){
                $dataSet = substr($dataSet, 0, strlen($dataSet)-2);
            }
            $this->setGraphRptMonthDataSet($dataSet);
            
            //7. Get the task statuses
            $dynaId = $this->getDynaId(DYNAFIELDS_TABLE_TASK, DYNAFIELDS_FIELDID_HTMLNAME_TASK_STATUS, DYNAFIELDS_TABLE_ID_TASK);
            $dynaFieldsValue = STRING_NONE;
            
            //Retrieve list values
            $sql = SQL_SELECT_ORGS_TASKS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  '(loadDashBoardDetails) Loading org records for Tasks Details - Query 7 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                
                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);
                
                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);
                
                $dynaFieldsListValuesEs = $row['dyna_fields_list_values_es'];
                $dynaFieldsListValuesEsArr = explode('|', $dynaFieldsListValuesEs);
                
                $dynaFieldsListValuesArr = setLocaleDynaText($dynaFieldsListValuesEnArr, $dynaFieldsListValuesEsArr);
                
                $dynaFieldsProcessedDetails = array();
                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    if ($dynaFieldsId === $dynaId){
                        foreach ($dynaFieldsListValuesArr as $value) {
                            if (strpos($value, $dynaFieldsId.':') !== FALSE){
                                $dynaFieldsValue = substr($value, strlen($dynaFieldsId.':'));
                                break;
                            }
                        }
                        break;
                    }
                }
                
                if ($dynaFieldsValue !== STRING_NONE){
                    $tskDatasetArr = array();
                    $dynaFieldsValueArr = explode(LIST_VALUES_SEPERATOR, $dynaFieldsValue);
                    for ($i = 0; $i < sizeof($dynaFieldsValueArr); ++$i){
                        $tskDatasetArr[$dynaFieldsValueArr[$i]] = 0;
                    }
                    
                    $dynaFieldsValue = str_replace(LIST_VALUES_SEPERATOR, '"' . LIST_VALUES_SEPERATOR . ' ' . '"', $dynaFieldsValue);
                    $dynaFieldsValue = '"' . $dynaFieldsValue. '"';

                    //Fetch task data
                    $sql = SQL_SELECT_USER_ORGS_TSKS_ALL;
                    $sql = str_replace('P1', $orgId, $sql);

                    $this->logger->debug('[' . $orgId . ']' .  ' (loadDashBoardDetails) Loading records for Tasks Details - Query 8 : ' . $sql);

                    $result = $qMan->query($sql);
                    if (isset($result) && mysql_num_rows($result) > 0){
                        
                        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $fieldIdValues = $row['field_id_values'];
                            $fieldIdValuesArr = explode('|', $fieldIdValues);
                            $columnValues = array();
                            foreach ($fieldIdValuesArr as $fieldIdValue){
                                $value = explode(':', $fieldIdValue);
                                if ($value[0] === $dynaId){
                                    $existingValue = $tskDatasetArr[$value[1]];
                                    $newValue = $existingValue + 1;
                                    $tskDatasetArr[$value[1]] = $newValue . '';
                                    break;
                                }
                            }
                        }
                    }
                    $tskDataset = '';
                    foreach ($tskDatasetArr as $key => $value){
                        $tskDataset = $tskDataset . $value . ', ' ;
                    }
                    if (!empty($tskDataset)){
                        $tskDataset = substr($tskDataset, 0, strlen($tskDataset)-2);
                    }
                    $graphTskStatusDataSet = array(
                                            'lables' => $dynaFieldsValue,
                                            'dataset' => $tskDataset
                                        );
                }
                else{
                    $graphTskStatusDataSet = array(
                                                'lables' => '',
                                                'dataset' => 0
                                            );
                }
                
                $this->setGraphTskStatusDataSet($graphTskStatusDataSet);
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_loadDashBoardDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //8. Load Login time
            $sql = SQL_SELECT_ORGS_LOGIN_TIME;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (loadDashBoardDetails) Loading records for logintime - Query 9 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                
                $logintime = $row['lastlogin'];
                $logintime = $this->reformDateFromDB($logintime, DATEFORMAT_LOGIN_TIME);
                $this->setLastLogin($logintime);
            }
            else{
                $this->setLastLogin('NA');
                $this->logger->debug('Not able to retrieve login time.');
            }
            
            
            $flag = TRUE;
            
        } 
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_loadDashBoardDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function fetchProductDetails(UserOrgInputs $userOrgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $prdid = $userOrgInputs->getIdprds();
            $orgId = $userOrgInputs->getIdorgs();
            
            if ($mode == USERDYNAFIELDS_LIST_MODE_NEW){
                
            }
            else if ($mode == USERDYNAFIELDS_LIST_MODE_EDIT || $mode == USERDYNAFIELDS_LIST_MODE_VIEW){
                $userEntities = $this->getUserEntities();
                
                $sql = SQL_SELECT_USER_ORGS_PRDS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', $prdid, $sql);

                $this->logger->debug('[' . $orgId . ']' .  ' (fetchProductDetails) Loading records for Products Details - Query : ' . $sql);

                $result = $qMan->query($sql);
                if (isset($result) && mysql_num_rows($result) > 0){
                    
                    $row = $qMan->fetchSingleRow($result);
                    
                    $fieldIdValues = $row['field_id_values'];
                    $fieldIdValuesArr = explode('|', $fieldIdValues);
                    $columnValues = array();
                    foreach ($fieldIdValuesArr as $fieldIdValue){
                        $value = explode(':', $fieldIdValue);
                        $columnValues[$value[0]] = $value[1];
                    }
                    
                    foreach ($userEntities as $userEntity){
                        $dynaId = $userEntity->getIdDynaFields();
                        if (array_key_exists($dynaId, $columnValues)){
                            $userEntity->setSavedValue($columnValues[$dynaId]);
                        }
                    }
                    $this->setUserEntities($userEntities);
                }
                
            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, getLocaleText('UserOrgDao_fetchProductDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchProductDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function fetchTaskDetails(UserOrgInputs $userOrgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $tskid = $userOrgInputs->getIdtsks();
            $orgId = $userOrgInputs->getIdorgs();
            
            if ($mode == USERDYNAFIELDS_LIST_MODE_NEW){
                
            }
            else if ($mode == USERDYNAFIELDS_LIST_MODE_EDIT || $mode == USERDYNAFIELDS_LIST_MODE_VIEW){
                $userEntities = $this->getUserEntities();
                
                $sql = SQL_SELECT_USER_ORGS_TSKS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', $tskid, $sql);

                $this->logger->debug('[' . $orgId . ']' .  ' (fetchTaskDetails) Loading records for Task Details - Query : ' . $sql);

                $result = $qMan->query($sql);
                if (isset($result) && mysql_num_rows($result) > 0){
                    
                    $row = $qMan->fetchSingleRow($result);
                    
                    $fieldIdValues = $row['field_id_values'];
                    $fieldIdValuesArr = explode('|', $fieldIdValues);
                    $columnValues = array();
                    foreach ($fieldIdValuesArr as $fieldIdValue){
                        $value = explode(':', $fieldIdValue);
                        $columnValues[$value[0]] = $value[1];
                    }
                    
                    foreach ($userEntities as $userEntity){
                        $dynaId = $userEntity->getIdDynaFields();
                        if (array_key_exists($dynaId, $columnValues)){
                            $userEntity->setSavedValue($columnValues[$dynaId]);
                        }
                    }
                    $this->setUserEntities($userEntities);
                }
                
            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, getLocaleText('UserOrgDao_fetchTaskDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchTaskDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function fetchWorkerDetails(UserOrgInputs $userOrgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $wrkrid = $userOrgInputs->getIdwrks();
            $orgId = $userOrgInputs->getIdorgs();
            
            if ($mode == USERDYNAFIELDS_LIST_MODE_NEW){
                
            }
            else if ($mode == USERDYNAFIELDS_LIST_MODE_EDIT || $mode == USERDYNAFIELDS_LIST_MODE_VIEW){
                $userEntities = $this->getUserEntities();
                
                $sql = SQL_SELECT_USER_ORGS_WRKS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', $wrkrid, $sql);

                $this->logger->debug('[' . $orgId . ']' .  ' (fetchWorkerDetails) Loading records for Worker Details - Query : ' . $sql);

                $result = $qMan->query($sql);
                if (isset($result) && mysql_num_rows($result) > 0){
                    
                    $row = $qMan->fetchSingleRow($result);
                    
                    $fieldIdValues = $row['field_id_values'];
                    $fieldIdValuesArr = explode('|', $fieldIdValues);
                    $columnValues = array();
                    foreach ($fieldIdValuesArr as $fieldIdValue){
                        $value = explode(':', $fieldIdValue);
                        $columnValues[$value[0]] = $value[1];
                    }
                    
                    foreach ($userEntities as $userEntity){
                        $dynaId = $userEntity->getIdDynaFields();
                        if (array_key_exists($dynaId, $columnValues)){
                            $userEntity->setSavedValue($columnValues[$dynaId]);
                        }
                    }
                    $this->setUserEntities($userEntities);
                }
                
            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, getLocaleText('UserOrgDao_fetchWorkerDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchWorkerDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function fetchCustomerDetails(UserOrgInputs $userOrgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $cstmrid = $userOrgInputs->getIdcstmrs();
            $orgId = $userOrgInputs->getIdorgs();
            
            if ($mode == USERDYNAFIELDS_LIST_MODE_NEW){
                
            }
            else if ($mode == USERDYNAFIELDS_LIST_MODE_EDIT || $mode == USERDYNAFIELDS_LIST_MODE_VIEW){
                $userEntities = $this->getUserEntities();
                
                $sql = SQL_SELECT_USER_ORGS_CSTMRS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', $cstmrid, $sql);

                $this->logger->debug('[' . $orgId . ']' .  ' (fetchCustomerDetails) Loading records for Customer Details - Query : ' . $sql);

                $result = $qMan->query($sql);
                if (isset($result) && mysql_num_rows($result) > 0){
                    
                    $row = $qMan->fetchSingleRow($result);
                    
                    $fieldIdValues = $row['field_id_values'];
                    $fieldIdValuesArr = explode('|', $fieldIdValues);
                    $columnValues = array();
                    foreach ($fieldIdValuesArr as $fieldIdValue){
                        $value = explode(':', $fieldIdValue);
                        $columnValues[$value[0]] = $value[1];
                    }
                    
                    foreach ($userEntities as $userEntity){
                        $dynaId = $userEntity->getIdDynaFields();
                        if (array_key_exists($dynaId, $columnValues)){
                            $userEntity->setSavedValue($columnValues[$dynaId]);
                        }
                    }
                    $this->setUserEntities($userEntities);
                }
                
            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, getLocaleText('UserOrgDao_fetchCustomerDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchCustomerDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    /*
     * References: ReportingDao -> loadReportData()
     */
    function fetchReportDetails(UserOrgInputs $userOrgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $rptid = $userOrgInputs->getIdrpts();
            $orgId = $userOrgInputs->getIdorgs();
            
            $allUserEntities = $this->getAllUserEntities();
            foreach ($allUserEntities as $userEntity){
                $dynaId = $userEntity->getIdDynaFields();
                $userEntity->setSavedValue(null);
            }
                
            $sql = SQL_SELECT_USER_ORGS_RPTS;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P2', $rptid, $sql);
            
            $this->logger->debug('[' . $orgId . ']' .  ' (fetchReportDetails) Loading records for Report Details - Query : ' . $sql);

            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $fieldIdValues = $row['data'];
                $fieldIdValuesArr = explode('|', $fieldIdValues);
                $columnValues = array();
                
                if (!empty($fieldIdValues)){
                    foreach ($fieldIdValuesArr as $fieldIdValue){
                        $value = explode(':', $fieldIdValue);
                        $columnValues[$value[0]] = $value[1];
                    }

                    foreach ($allUserEntities as $userEntity){
                        $dynaId = $userEntity->getIdDynaFields();
                        if (array_key_exists($dynaId, $columnValues)){
                            $userEntity->setSavedValue($columnValues[$dynaId]);
                        }
                    }
                }
                
                /* Following details are used in Report Publishing, not used by UI*/
                $orgReport = new OrgReport();
                $orgReport->setIdOrgRpts($row['idorg_rpts']);
                $orgReport->setIdorgs($orgId);
                $orgReport->setSubDatetime($row['sub_datetime']);
                $orgReport->setSubBy($row['sub_by']);
                $orgReport->setData($row['data']);
                $orgReport->setClientname($row['clientname']);
                $orgReport->setCoordinates($row['coordinates']);
                //$orgReport->setLocation($row['location']);
                $orgReport->setUpdateHistory($row['update_his']);
                $orgReport->setRptNo(REPORT_NAMING_PREFIX.$row['idorg_rpts']);
                
                $location = $row['location'];
                if (LOCATION_GPS_NOT_ENABLED == $location){
                    $orgReport->setLocation(getLocaleText(LOCATION_MSG_GPS_NOT_ENABLED, TXT_A));
                }
                else if (LOCATION_GPS_ENABLED == $location){
                    $orgReport->setLocation(getLocaleText(LOCATION_MSG_GPS_ENABLED, TXT_A));
                }
                else if (LOCATION_GEOCODE_TIMEDOUT == $location){
                    $orgReport->setLocation(getLocaleText(LOCATION_MSG_GEOCODE_TIMEDOUT, TXT_A));
                }
                else if (LOCATION_NO_LOCATION_FOUND == $location){
                    $orgReport->setLocation(getLocaleText(LOCATION_MSG_NO_LOCATION_FOUND, TXT_A));
                }
                else{
                    $orgReport->setLocation($location);
                }
                
                $this->setReportDetails($orgReport);
                /**/
                
                $this->setUserEntities($allUserEntities);
                $this->setPublishBtn(TRUE);
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchReportDetails_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchReportDetails_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function deleteProduct(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $prdid = $userOrgInputs->getIdprds();
            $orgId = $userOrgInputs->getIdorgs();
            
            $sql = SQL_DELETE_USER_ORGS_PRDS;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P2', $prdid, $sql);

            $this->logger->debug('[' . $orgId . ']' .  ' (deleteProduct) Deleting record for Product - Query : ' . $sql);

            $delete = $qMan->query($sql);
            
            if (!$delete){
               $this->logger->error('[' . $orgId . ']' .  ' (deleteProduct) Failed to delete the product record from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('UserOrgDao_deleteProduct_MSG_1', TXT_U));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            $this->logger->debug('[' . $orgId . ']' .  ' (deleteProduct) Product record is deleted from the system successfully.');
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            $this->setCompleteMsg(getLocaleText('UserOrgDao_deleteProduct_MSG_2', TXT_U));
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_deleteProduct_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
    }
    
    function deleteTask(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $tskid = $userOrgInputs->getIdtsks();
            $orgId = $userOrgInputs->getIdorgs();
            
            $sql = SQL_DELETE_USER_ORGS_TSKS;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P2', $tskid, $sql);

            $this->logger->debug('[' . $orgId . ']' .  ' (deleteTask) Deleting record for Task - Query : ' . $sql);

            $delete = $qMan->query($sql);
            
            if (!$delete){
               $this->logger->error('[' . $orgId . ']' .  ' (deleteTask) Failed to delete the task record from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('UserOrgDao_deleteTask_MSG_1', TXT_U));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            $this->logger->debug('[' . $orgId . ']' .  ' (deleteTask) Task record is deleted from the system successfully.');
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            $this->setCompleteMsg(getLocaleText('UserOrgDao_deleteTask_MSG_2', TXT_U));
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_deleteTask_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
    }
    
    function deleteWorker(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $wrkrid = $userOrgInputs->getIdwrks();
            $orgId = $userOrgInputs->getIdorgs();
            
            $sql = SQL_DELETE_USER_ORGS_WRKS;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P2', $wrkrid, $sql);

            $this->logger->debug('[' . $orgId . ']' .  ' (deleteWorker) Deleting record for Worker - Query : ' . $sql);

            $delete = $qMan->query($sql);
            
            if (!$delete){
               $this->logger->error('[' . $orgId . ']' .  ' (deleteWorker) Failed to delete the worker record from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('UserOrgDao_deleteWorker_MSG_1', TXT_U));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            $this->logger->debug('[' . $orgId . ']' .  ' (deleteWorker) Worker record is deleted from the system successfully.');
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            $this->setCompleteMsg(getLocaleText('UserOrgDao_deleteWorker_MSG_2', TXT_U));
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_deleteWorker_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
    }
    
    function deleteCustomer(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $cstmrid = $userOrgInputs->getIdcstmrs();
            $orgId = $userOrgInputs->getIdorgs();
            
            $sql = SQL_DELETE_USER_ORGS_CSTMRS;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P2', $cstmrid, $sql);

            $this->logger->debug('[' . $orgId . ']' .  ' (deleteCustomer) Deleting record for Customer - Query : ' . $sql);

            $delete = $qMan->query($sql);
            
            if (!$delete){
               $this->logger->error('[' . $orgId . ']' .  ' (deleteCustomer) Failed to delete the customer record from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('UserOrgDao_deleteCustomer_MSG_1', TXT_U));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            $this->logger->debug('[' . $orgId . ']' .  ' (deleteCustomer) Customer record is deleted from the system successfully.');
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            $this->setCompleteMsg(getLocaleText('UserOrgDao_deleteCustomer_MSG_2', TXT_U));
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_deleteCustomer_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
    }
    
    function deleteReport(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $rptid = $userOrgInputs->getIdrpts();
            $orgId = $userOrgInputs->getIdorgs();
            
            $sql = SQL_DELETE_USER_ORGS_RPTS;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P2', $rptid, $sql);

            $this->logger->debug('[' . $orgId . ']' .  ' (deleteReport) Deleting record for Report - Query : ' . $sql);

            $delete = $qMan->query($sql);
            
            if (!$delete){
               $this->logger->error('[' . $orgId . ']' .  ' (deleteReport) Failed to delete the Report from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('UserOrgDao_deleteReport_MSG_1', TXT_U));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            //Remove the report from the folder
            $orgDir = '../admin/orgdata/' . 'org_' . $orgId;
            $reportsDir = $orgDir . '/reports';
            $thisReportDir = $reportsDir . '/' . REPORT_NAMING_FOLDERNAME_PREFIX . $rptid;

            if (file_exists($thisReportDir)){
                $dirDeleted = $this->delTree($thisReportDir);
                if (!$dirDeleted){
                    $this->logger->error('Failed to remove the report from the folder structure, some internal error occured.');
                    array_push($this->errors, getLocaleText('UserOrgDao_deleteReport_MSG_2', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
                else{
                    $this->logger->error('Report is removed successfully from the folder.');
                }
            }
            else{
                $this->logger->debug('Folder for this report do not exist : .' . $rptid);
            }
            
            $this->logger->debug('[' . $orgId . ']' .  ' (deleteReport) Report is deleted from the system successfully.');
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            $this->setCompleteMsg(getLocaleText('UserOrgDao_deleteReport_MSG_3', TXT_U));
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_deleteReport_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function populateDynaFieldsObj($row, $id, UserEntity $dynaFields){
        
        $dynaFields->setIdDynaFields($row[$id]);
        $dynaFields->setNameEn($row['name_en']);
        $dynaFields->setNameEs($row['name_es']);
        $dynaFields->setDescriptionEn($row['description_en']);
        $dynaFields->setDescriptionEs($row['description_es']);
        $dynaFields->setHtmlName($row['html_name']);
        $dynaFields->setHtmlType($row['html_type']);
        $dynaFields->setHtmlListValuesEn($row['html_list_values_en']);
        $dynaFields->setHtmlListValuesEs($row['html_list_values_es']);
        $dynaFields->setHtmlValidations($row['html_validations']);
        $dynaFields->setHtmlValidationsMessagesEn($row['html_validations_messages_en']);
        $dynaFields->setHtmlValidationsMessagesEs($row['html_validations_messages_es']);
        $dynaFields->setIcon($row['icon']);
        $dynaFields->setRecom($row['recom']);
        $dynaFields->setCreatedOn($row['created_on']);
        $dynaFields->setLastUpdated($row['last_updated']);
        
        $dynaFields->setName(setLocaleDynaText($row['name_en'], $row['name_es']));
        $dynaFields->setDescription(setLocaleDynaText($row['description_en'], $row['description_es']));
        $dynaFields->setHtmlListValues(setLocaleDynaText($row['html_list_values_en'], $row['html_list_values_es']));
        $dynaFields->getHtmlValidationsMessages(setLocaleDynaText($row['html_validations_messages_en'], $row['html_validations_messages_es']));
        
    }
    
    
    function fetchClients(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $clientDynaId = '';
        $clientNames = array();
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            //1. Fetch the dyna id for the client name
            $sql = SQL_SELECT_USER_RPT_QF_CLIENT_NAME_FIELDID;
            $sql = str_replace('P1', '\''.DYNAFIELDS_FIELDID_HTMLNAME_CUSTOMER_NAME.'\'', $sql);
            $this->logger->debug('[' . $orgId . ']' .  ' (fetchClients) Getting Client name id - Query 1 : ' . $sql);
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                
                $clientDynaId = $row[DYNAFIELDS_TABLE_ID_CUSTOMER];
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchClients_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //2. Check if the client dyna id is configured for this organization
            $sql = SQL_SELECT_ORGS_CUSTOMERS;
            $sql = str_replace('P1', $orgId, $sql);
            $this->logger->debug('[' . $orgId . ']' .  ' (fetchClients) Checking if client dyna details are configured for this organization - Query 2 : ' . $sql);
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                
                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode("|", $dynaFieldsIds);
                if (!in_array($clientDynaId, $dynaFieldsIdsArr)){
                    array_push($this->errors, getLocaleText('UserOrgDao_fetchClients_MSG_2', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchClients_MSG_3', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //3. Retrieve all the clients
            $sql = SQL_SELECT_USER_ORGS_CSTMRS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            $this->logger->debug('[' . $orgId . ']' .  ' (fetchClients) Loading records for Customers Details - Query  3 : ' . $sql);
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $fieldIdValues = $row['field_id_values'];
                    $fieldIdValuesArr = explode('|', $fieldIdValues);
                    $columnValues = array();
                    foreach ($fieldIdValuesArr as $fieldIdValue){
                        $value = explode(':', $fieldIdValue);
                        $columnValues[$value[0]] = $value[1];
                    }
                    
                    array_push($clientNames, $columnValues[$clientDynaId]);
                }
                $this->setClientNamesList($clientNames);
                
                $this->logger->debug('List of Customer names are loaded successfully.');
                $flag = TRUE;
            }
            else{    
                $this->logger->debug('No Client records exist in the system, Client name query filter cannot be used in this report and due to this reprot cannot be executed.');
                array_push($this->errors, getLocaleText('UserOrgDao_fetchClients_MSG_4', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }

        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchClients_MSG_', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function fetchWorkers(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $workerDynaId = '';
        $workerNames = array();
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            //1. Fetch the dyna id for the worker name
            $sql = SQL_SELECT_USER_RPT_QF_WORKER_NAME_FIELDID;
            $sql = str_replace('P1', '\''.DYNAFIELDS_FIELDID_HTMLNAME_WORKER_NAME.'\'', $sql);
            $this->logger->debug('[' . $orgId . ']' .  ' (fetchWorkers) Getting Worker name id - Query 1 : ' . $sql);
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                
                $workerDynaId = $row[DYNAFIELDS_TABLE_ID_WORKER];
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchWorkers_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //2. Check if the worker dyna id is configured for this organization
            $sql = SQL_SELECT_ORGS_WORKERS;
            $sql = str_replace('P1', $orgId, $sql);
            $this->logger->debug('[' . $orgId . ']' .  ' (fetchWorkers) Checking if worker dyna details are configured for this organization - Query 2 : ' . $sql);
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                
                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode("|", $dynaFieldsIds);
                if (!in_array($workerDynaId, $dynaFieldsIdsArr)){
                    array_push($this->errors, getLocaleText('UserOrgDao_fetchWorkers_MSG_2', TXT_U));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_fetchWorkers_MSG_3', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //3. Retrieve all the workers
            $sql = SQL_SELECT_USER_ORGS_WRKS_ALL;
            $sql = str_replace('P1', $orgId, $sql);
            $this->logger->debug('[' . $orgId . ']' .  ' (fetchWorkers) Loading records for Worker Details - Query  3 : ' . $sql);
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $fieldIdValues = $row['field_id_values'];
                    $fieldIdValuesArr = explode('|', $fieldIdValues);
                    $columnValues = array();
                    foreach ($fieldIdValuesArr as $fieldIdValue){
                        $value = explode(':', $fieldIdValue);
                        $columnValues[$value[0]] = $value[1];
                    }
                    
                    array_push($workerNames, $columnValues[$workerDynaId]);
                }
                $this->setWorkerNamesList($workerNames);
                
                $this->logger->debug('List of Worker names are loaded successfully.');
                $flag = TRUE;
            }
            else{    
                $this->logger->debug('No Worker records exist in the system, Worker name query filter cannot be used in this report and due to this reprot cannot be executed.');
                array_push($this->errors, getLocaleText('UserOrgDao_fetchWorkers_MSG_4', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }

        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_fetchWorkers_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function findQFilterTCW(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $clientDynaId = '';
        $workerDynaId = '';
        $qFilterSql = '';
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            $sdate = $userOrgInputs->getQf_sdate();
            $edate = $userOrgInputs->getQf_edate();
            $client = $userOrgInputs->getQf_clientname();
            $worker = $userOrgInputs->getQf_workername();
            
            if (!empty($sdate) && !empty($edate)){
                $fmtSdate = $this->reformToDBDate($sdate) . ' ' . '00:00:00';
                $fmtEdate = $this->reformToDBDate($edate) . ' ' . '24:00:00';
                
                $qFilterSql = 'sub_datetime >= \'' . $fmtSdate . '\' AND sub_datetime <= \'' . $fmtEdate . '\'' ;
            }
            if (!empty($worker)){
                if (!empty($qFilterSql)){
                    $qFilterSql = $qFilterSql . ' AND sub_by = \'' . $worker . '\'';
                }
                else{
                    $qFilterSql = 'sub_by = \'' . $worker . '\'';
                }
            }
            if (!empty($client)){
                if (!empty($qFilterSql)){
                    $qFilterSql = $qFilterSql . ' AND clientname = \'' . $client . '\'';
                }
                else{
                    $qFilterSql = 'clientname = \'' . $client . '\'';
                }
            }
            
            
            //1. Find out the records matching the filter criteria
            $sql = SQL_SELECT_USER_RPT_QF_TCW;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P_SC', $qFilterSql, $sql);
            /*$sql = str_replace('P2', '\''.$fmtSdate.'\'', $sql);
            $sql = str_replace('P3', '\''.$fmtEdate.'\'', $sql);
            $sql = str_replace('P4', '\''.$worker.'\'', $sql);
            $sql = str_replace('P5', '\''.$client.'\'', $sql);*/
            
            $this->logger->debug('[' . $orgId . ']' .  ' (findQFilterTCW) Finding the query filter records for TCW - Query 3 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $reportQFilterResSet = array();
                
                $ctr = 1;
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    
                    $reportQFilterRes = new ReportQFilterRes();
                    
                    $reportQFilterRes->setIdorgRpts($row['idorg_rpts']);
                    $reportQFilterRes->setClientName($row['clientname']);
                    $reportQFilterRes->setWorkerName($row['sub_by']);
                    $reportQFilterRes->setRptNo($this->generateReportNo($row['idorg_rpts']));
                    $reportQFilterRes->setRptSubmitDate($row['sub_datetime']);
                    $reportQFilterRes->setIdorgs($orgId);
                    
                    $coords = $row['coordinates'];
                    if (null != $coords && !empty($coords)){
                        $coordsSplit = explode(COORDNIATES_SEPERATOR, $coords);
                        $reportQFilterRes->setLatitude($coordsSplit[0]);
                        $reportQFilterRes->setLongitude($coordsSplit[1]);
                    }
                    $location = $row['location'];
                    if (LOCATION_GPS_NOT_ENABLED == $location){
                        $reportQFilterRes->setLocation(getLocaleText(LOCATION_MSG_GPS_NOT_ENABLED, TXT_A));
                    }
                    else if (LOCATION_GPS_ENABLED == $location){
                        $reportQFilterRes->setLocation(getLocaleText(LOCATION_MSG_GPS_ENABLED, TXT_A));
                    }
                    else if (LOCATION_GEOCODE_TIMEDOUT == $location){
                        $reportQFilterRes->setLocation(getLocaleText(LOCATION_MSG_GEOCODE_TIMEDOUT, TXT_A));
                    }
                    else if (LOCATION_NO_LOCATION_FOUND == $location){
                        $reportQFilterRes->setLocation(getLocaleText(LOCATION_MSG_NO_LOCATION_FOUND, TXT_A));
                    }
                    else{
                        $reportQFilterRes->setLocation($location);
                    }
                    
                    
                    if (strcmp(getLocaleText(LOCATION_MSG_NO_LOCATION_FOUND, TXT_A), $location) === 0 || 
                        strcmp(getLocaleText(LOCATION_MSG_GPS_NOT_ENABLED, TXT_A), $location) === 0 ||
                        strcmp(getLocaleText(LOCATION_MSG_GPS_ENABLED, TXT_A), $location) === 0 || 
                        strcmp(getLocaleText(LOCATION_MSG_GEOCODE_TIMEDOUT, TXT_A), $location) === 0 ||
                        empty($location)){
                        
                        $this->logger->debug('[' . $orgId . ']' .  ' (findQFilterTCW) No location found, no Marker xml will be generated for report id : ' . $reportQFilterRes->getIdorgRpts());
                    }
                    else{
                        $markeFilename = MAP_MARKERS_XML_FILE_TCW . '-' . $orgId . '-' . $ctr . '.xml';
                        $markerFilePath = MAP_MARKERS_XML_FILE_LOCATION . '/' . $markeFilename;
                        $fileGen = $this->generateMarkersXMLForTCW($markeFilename, $reportQFilterRes);
                        if (!$fileGen){
                            $this->logger->error('[' . $orgId . ']' .  ' (findQFilterTCW) Failed to generate XML file for google map for TCW Query Filter for report id : ' . $reportQFilterRes->getIdorgRpts());
                            array_push($this->errors, getLocaleText('UserOrgDao_findQFilterTCW_MSG_1', TXT_U) . $reportQFilterRes->getRptNo());
                            $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                            return $flag;
                        }
                        else{
                            $this->logger->debug('[' . $orgId . ']' .  ' (findQFilterTCW) Marker xml generated for report id : ' . $reportQFilterRes->getIdorgRpts());
                            rename($markeFilename, $markerFilePath);
                            $reportQFilterRes->setMapMarkerFile($markerFilePath);
                        }
                    }
                    
                    array_push($reportQFilterResSet, $reportQFilterRes);
                    
                    ++$ctr;
                }
                
                $this->setReportQFilterResList($reportQFilterResSet);
            }
            else{
                array_push($this->messages, getLocaleText('UserOrgDao_findQFilterTCW_MSG_2', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_MESSAGE);
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_findQFilterTCW_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function findQFilterCalWHrs(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $serviceTimeDynaIds = array();
        $qFilterSql = '';
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            $sdate = $userOrgInputs->getQf_sdate();
            $edate = $userOrgInputs->getQf_edate();
            $client = $userOrgInputs->getQf_clientname();
            $worker = $userOrgInputs->getQf_workername();
            
            //1. Fetch the dyna id for the Service Start Time and Service End Time name
            $dynaIds = '\''.DYNAFIELDS_FIELDID_HTMLNAME_RPT_SERVICESTART_NAME.'\',\''.DYNAFIELDS_FIELDID_HTMLNAME_RPT_SERVICEEND_NAME.'\'';
            $sql = SQL_SELECT_USER_RPT_QF_FIELDID_MANY;
            $sql = str_replace('P1', $dynaIds, $sql);
            $this->logger->debug('[' . $orgId . ']' .  ' (findQFilterCalWHrs) Getting Service Start/End Time dyna id - Query 1 : ' . $sql);
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $serviceTimeDynaIds[$row['html_name']] = DYNAFIELDS_REPORTING_CBID_PREFIX.$row['idrpts_fields'];
                }
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_findQFilterCalWHrs_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //2. Find out the records matching the filter criteria
            if (!empty($sdate) && !empty($edate)){
                $fmtSdate = $this->reformToDBDate($sdate) . ' ' . '00:00:00';
                $fmtEdate = $this->reformToDBDate($edate) . ' ' . '24:00:00';
                
                $qFilterSql = 'sub_datetime >= \'' . $fmtSdate . '\' AND sub_datetime <= \'' . $fmtEdate . '\'' ;
            }
            if (!empty($worker)){
                if (!empty($qFilterSql)){
                    $qFilterSql = $qFilterSql . ' AND sub_by = \'' . $worker . '\'';
                }
                else{
                    $qFilterSql = 'sub_by = \'' . $worker . '\'';
                }
            }
            if (!empty($client)){
                if (!empty($qFilterSql)){
                    $qFilterSql = $qFilterSql . ' AND clientname = \'' . $client . '\'';
                }
                else{
                    $qFilterSql = 'clientname = \'' . $client . '\'';
                }
            }
            
            $sql = SQL_SELECT_USER_RPT_QF_CAL_W_HRS;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P_SC', $qFilterSql, $sql);
            /*$sql = str_replace('P2', '\''.$fmtSdate.'\'', $sql);
            $sql = str_replace('P3', '\''.$fmtEdate.'\'', $sql);
            $sql = str_replace('P4', '\''.$worker.'\'', $sql);
            $sql = str_replace('P5', '\''.$client.'\'', $sql);*/
            
            $this->logger->debug('[' . $orgId . ']' .  ' (findQFilterCalWHrs) Finding the query filter records for Calculate Work Hours - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $reportQFilterResTempSet = array();
                $reportQFilterResSet = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $reportQFilterRes = new ReportQFilterRes();
                    
                    $reportQFilterRes->setClientName($row['clientname']);
                    $reportQFilterRes->setWorkerName($row['sub_by']);
                    $reportQFilterRes->setRptNo($this->generateReportNo($row['idorg_rpts']));
                    $reportQFilterRes->setRptSubmitDate($row['sub_datetime']);
                    $reportQFilterRes->setData($row['data']);
                    
                    array_push($reportQFilterResTempSet, $reportQFilterRes);
                }
                
                $intervals = array();
                foreach ($reportQFilterResTempSet as $reportQFilterRes){
                    $data = $reportQFilterRes->getData();
                    
                    if (!empty($data)){
                        $fieldIdValues = explode(FIELDID_VALUE_DATASET_SEPERATOR, $data);
                        if (!empty($fieldIdValues)){
                            $columnValues = array();
                            foreach ($fieldIdValues as $fieldIdValue){
                                $value = explode(FIELDID_VALUE_SEPERATOR, $fieldIdValue);
                                $columnValues[$value[0]] = $value[1];
                            }
                            
                            if (array_key_exists($serviceTimeDynaIds[DYNAFIELDS_FIELDID_HTMLNAME_RPT_SERVICESTART_NAME], $columnValues) && 
                                array_key_exists($serviceTimeDynaIds[DYNAFIELDS_FIELDID_HTMLNAME_RPT_SERVICEEND_NAME], $columnValues )){
                                $ssTime = $columnValues[$serviceTimeDynaIds[DYNAFIELDS_FIELDID_HTMLNAME_RPT_SERVICESTART_NAME]];
                                $seTime = $columnValues[$serviceTimeDynaIds[DYNAFIELDS_FIELDID_HTMLNAME_RPT_SERVICEEND_NAME]];
                                
                                $ssTime = str_replace('.', ':', $ssTime);
                                $seTime = str_replace('.', ':', $seTime);
                                $timeDiff = $this->getTimeDiff($ssTime, $seTime);
                                $timeDiffStr = $timeDiff->format("%h hrs %i mins %s secs");
                                array_push($intervals, $timeDiff);
                                
                                $reportQFilterRes->setServiceHrs($timeDiffStr);
                                
                                array_push($reportQFilterResSet, $reportQFilterRes);
                            }
                        }
                    }
                    
                }
                
                if (!empty($intervals)){
                    $consH = 0;
                    $consM = 0;
                    $consS = 0;
                    foreach ($intervals as $interval){
                        $h = $interval->h;
                        $m = $interval->i;
                        $s = $interval->s;

                        $consH = $consH + $h;
                        $consM = $consM + $m;
                        $consS = $consS + $s;
                        
                        if ($consM >= 60){
                            $consH = $consH + 1;
                            $consM = $consM - 60;
                        }
                        if ($consS >= 60){
                            $consM = $consM + 1;
                            $consS = $consS - 60;
                        }

                    }
                    $totalHrs = $consH . ' hrs ' . $consM . ' mins ' . $consS . ' secs';
                    $this->setTotalServiceHrs($totalHrs);
                    
                    $this->setReportQFilterResList($reportQFilterResSet);
                }
                    
                
            }
            else{
                array_push($this->messages, getLocaleText('UserOrgDao_findQFilterCalWHrs_MSG_2', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_MESSAGE);
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_findQFilterCalWHrs_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function findQFilterTtlPrdQty(UserOrgInputs $userOrgInputs){
        
        $flag = FALSE;
        $prdListDynaId = '';
        $qFilterSql = '';
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $userOrgInputs->getIdorgs();
            
            $sdate = $userOrgInputs->getQf_sdate();
            $edate = $userOrgInputs->getQf_edate();
            $client = $userOrgInputs->getQf_clientname();
            $worker = $userOrgInputs->getQf_workername();
            
            //1. Fetch the dyna id for the Products List name
            $sql = SQL_SELECT_USER_RPT_QF_PRODUCTSUSED_FIELDID;
            $sql = str_replace('P1', '\''.DYNAFIELDS_FIELDID_HTMLNAME_RPT_PRODUCTSUSED_NAME.'\'', $sql);
            $this->logger->debug('[' . $orgId . ']' .  ' (findQFilterTtlPrdQty) Getting Products List id - Query 1 : ' . $sql);
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                
                $prdListDynaId = DYNAFIELDS_REPORTING_CBID_PREFIX.$row['idrpts_fields'];
            }
            else{
                array_push($this->errors, getLocaleText('UserOrgDao_findQFilterTtlPrdQty_MSG_1', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //2. Find out the records matching the filter criteria
            if (!empty($sdate) && !empty($edate)){
                $fmtSdate = $this->reformToDBDate($sdate) . ' ' . '00:00:00';
                $fmtEdate = $this->reformToDBDate($edate) . ' ' . '24:00:00';
                
                $qFilterSql = 'sub_datetime >= \'' . $fmtSdate . '\' AND sub_datetime <= \'' . $fmtEdate . '\'' ;
            }
            if (!empty($worker)){
                if (!empty($qFilterSql)){
                    $qFilterSql = $qFilterSql . ' AND sub_by = \'' . $worker . '\'';
                }
                else{
                    $qFilterSql = 'sub_by = \'' . $worker . '\'';
                }
            }
            if (!empty($client)){
                if (!empty($qFilterSql)){
                    $qFilterSql = $qFilterSql . ' AND clientname = \'' . $client . '\'';
                }
                else{
                    $qFilterSql = 'clientname = \'' . $client . '\'';
                }
            }
            
            $sql = SQL_SELECT_USER_RPT_QF_TTL_PRD_QTY;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P_SC', $qFilterSql, $sql);
            /*$sql = str_replace('P2', '\''.$fmtSdate.'\'', $sql);
            $sql = str_replace('P3', '\''.$fmtEdate.'\'', $sql);
            $sql = str_replace('P4', '\''.$worker.'\'', $sql);
            $sql = str_replace('P5', '\''.$client.'\'', $sql);*/
            
            $this->logger->debug('[' . $orgId . ']' .  ' (findQFilterTtlPrdQty) Finding the query filter records for Total Prd Qty - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $reportQFilterResTempSet = array();
                $reportQFilterResSet = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $reportQFilterRes = new ReportQFilterRes();
                    
                    $reportQFilterRes->setClientName($row['clientname']);
                    $reportQFilterRes->setWorkerName($row['sub_by']);
                    $reportQFilterRes->setLocation($row['location']);
                    $reportQFilterRes->setRptNo($this->generateReportNo($row['idorg_rpts']));
                    $reportQFilterRes->setRptSubmitDate($row['sub_datetime']);
                    $reportQFilterRes->setData($row['data']);
                    
                    array_push($reportQFilterResTempSet, $reportQFilterRes);
                }
                
                foreach ($reportQFilterResTempSet as $reportQFilterRes){
                    $data = $reportQFilterRes->getData();
                    
                    if (!empty($data)){
                        $fieldIdValues = explode(FIELDID_VALUE_DATASET_SEPERATOR, $data);
                        if (!empty($fieldIdValues)){
                            $columnValues = array();
                            foreach ($fieldIdValues as $fieldIdValue){
                                $value = explode(FIELDID_VALUE_SEPERATOR, $fieldIdValue);
                                $columnValues[$value[0]] = $value[1];
                            }
                            
                            if (array_key_exists($prdListDynaId, $columnValues)){
                                $prdListValues = $columnValues[$prdListDynaId];
                                $prdListValues = explode(FIELD_2TB_VALUES_DATASET_SEPERATOR, $prdListValues);
                                $prdListArr = array();
                                foreach ($prdListValues as $prdListValue){
                                    $prdListValueArr = explode(FIELD_2TB_VALUES_SEPERATOR, $prdListValue);
                                    array_push($prdListArr, $prdListValueArr);
                                }
                                $reportQFilterRes->setPrdQtyList($prdListArr);
                                array_push($reportQFilterResSet, $reportQFilterRes);
                            }
                        }
                    }
                    
                }
                
                $this->setReportQFilterResList($reportQFilterResSet);
                
            }
            else{
                array_push($this->messages, getLocaleText('UserOrgDao_findQFilterTtlPrdQty_MSG_2', TXT_U));
                $this->setMsgType(SUBMISSION_MSG_TYPE_MESSAGE);
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_findQFilterTtlPrdQty_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function getDynaId($entity, $field, $id){
        
        $dynaid = '';
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_SELECT_USER_GET_DYNAID;
            $sql = str_replace('P1', $entity, $sql);
            $sql = str_replace('P2', '\''.$field.'\'', $sql);
            $this->logger->debug('Getting Dyna id - Query : ' . $sql);
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                
                $dynaid = $row[$id];
            }
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[User] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('UserOrgDao_getDynaId_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $dynaid;
        
    }
    
    function getTimeDiff($sstime, $setime){
        
        if (strpos($sstime, 'PM') !== FALSE){
            $sstime = explode(" ", $sstime);
            $sstime = new DateTime($sstime[0]);
            $sstime->add(new DateInterval('PT12H'));

            if (strpos($setime, 'AM') !== FALSE){
                $setime = explode(" ", $setime);
                $setime = new DateTime($setime[0]);
                $setime->add(new DateInterval('P1D'));
            }
            else{
                $setime = explode(" ", $setime);
                $setime = new DateTime($setime[0]);
                $setime->add(new DateInterval('PT12H'));
            }
        }
        else{
            $sstime = explode(" ", $sstime);
            $sstime = new DateTime($sstime[0]);

            if (strpos($setime, 'PM') !== FALSE){
                $setime = explode(" ", $setime);
                $setime = new DateTime($setime[0]);
                $setime->add(new DateInterval('PT12H'));
            }
            else{
                $setime = explode(" ", $setime);
                $setime = new DateTime($setime[0]);
                $setime->add(new DateInterval('P1D'));
            }
        }

        $interval = $setime->diff($sstime);
        return $interval;
        
    }
    
    function createMonthDataSetForGraph(){
        
        $monthDataSet = array();
        
        $monthDataSet["1"] = "0";
        $monthDataSet["2"] = "0";
        $monthDataSet["3"] = "0";
        $monthDataSet["4"] = "0";
        $monthDataSet["5"] = "0";
        $monthDataSet["6"] = "0";
        $monthDataSet["7"] = "0";
        $monthDataSet["8"] = "0";
        $monthDataSet["9"] = "0";
        $monthDataSet["10"] = "0";
        $monthDataSet["11"] = "0";
        $monthDataSet["12"] = "0";
        
        return $monthDataSet;
        
    }
    
    function reformToDBDate($date) {
        return date_format( DateTime::createFromFormat('d/m/Y', $date), 'Y-m-d');
        //$date_aux = date_create_from_format($from_format, $date);
        //return date_format($date_aux,$to_format);
    }
    
    function reformDateFromDB($date, $format) {
        return date_format( DateTime::createFromFormat('Y-m-d H:i:s', $date), $format);
    }
    
    function generateReportNo($rptid){
        
        return REPORT_NAMING_PREFIX.$rptid;
        
    }
    
    function delTree($dir) {
        
        $files = array_diff(scandir($dir), array('.', '..')); 

        foreach ($files as $file) { 
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file"); 
        }

        return rmdir($dir); 
    }
    
    function generateMarkersXMLForTCW($markeFilename, $reportQFilterRes){
        
        if (file_exists($markeFilename)) {
            unlink($markeFilename);
        }

        $dom = new DOMDocument("1.0", "utf-8");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        $node = $dom->createElement("marker");
        $markernode = $parnode->appendChild($node);
        
        $node = $dom->createElement("name", $reportQFilterRes->getWorkerName());
        $element = $markernode->appendChild($node);
        
        $node = $dom->createElement("location", $reportQFilterRes->getLocation());
        $element = $markernode->appendChild($node);
        
        $node = $dom->createElement("icon", MAP_MARKERS_XML_FILE_TCW_ICON);
        $element = $markernode->appendChild($node);
        

//        $newnode->setAttribute("name", $reportQFilterRes->getWorkerName());
//        $newnode->setAttribute("location", $reportQFilterRes->getLocation());
//        $newnode->setAttribute("icon", MAP_MARKERS_XML_FILE_TCW_ICON);
        
//        $datetime = (new DateTime($project->getLastUpdated()));
//        $project->setLastUpdated($datetime->format(Config::$datetimeformat));

        return $dom->save($markeFilename);
        
    }
    
    public function getUserEntities() {
        return $this->userEntities;
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

    public function setUserEntities($userEntities) {
        $this->userEntities = $userEntities;
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

    public function getTableHeadPrdDetailsEn() {
        return $this->tableHeadPrdDetailsEn;
    }

    public function setTableHeadPrdDetailsEn($tableHeadPrdDetailsEn) {
        $this->tableHeadPrdDetailsEn = $tableHeadPrdDetailsEn;
    }

    public function getTableBodyPrdDetailsEn() {
        return $this->tableBodyPrdDetailsEn;
    }

    public function getTableHeadPrdDetailsEs() {
        return $this->tableHeadPrdDetailsEs;
    }

    public function setTableBodyPrdDetailsEn($tableBodyPrdDetailsEn) {
        $this->tableBodyPrdDetailsEn = $tableBodyPrdDetailsEn;
    }

    public function setTableHeadPrdDetailsEs($tableHeadPrdDetailsEs) {
        $this->tableHeadPrdDetailsEs = $tableHeadPrdDetailsEs;
    }

    public function getTotalPrds() {
        return $this->totalPrds;
    }

    public function setTotalPrds($totalPrds) {
        $this->totalPrds = $totalPrds;
    }

    public function getIsListLoaded() {
        return $this->isListLoaded;
    }

    public function setIsListLoaded($isListLoaded) {
        $this->isListLoaded = $isListLoaded;
    }

    public function getTableHeadTskDetailsEn() {
        return $this->tableHeadTskDetailsEn;
    }

    public function getTableBodyTskDetailsEn() {
        return $this->tableBodyTskDetailsEn;
    }

    public function getTableHeadTskDetailsEs() {
        return $this->tableHeadTskDetailsEs;
    }

    public function getTotalTsks() {
        return $this->totalTsks;
    }

    public function setTableHeadTskDetailsEn($tableHeadTskDetailsEn) {
        $this->tableHeadTskDetailsEn = $tableHeadTskDetailsEn;
    }

    public function setTableBodyTskDetailsEn($tableBodyTskDetailsEn) {
        $this->tableBodyTskDetailsEn = $tableBodyTskDetailsEn;
    }

    public function setTableHeadTskDetailsEs($tableHeadTskDetailsEs) {
        $this->tableHeadTskDetailsEs = $tableHeadTskDetailsEs;
    }

    public function setTotalTsks($totalTsks) {
        $this->totalTsks = $totalTsks;
    }

    public function getTableHeadWrkrDetailsEn() {
        return $this->tableHeadWrkrDetailsEn;
    }

    public function getTableBodyWrkrDetailsEn() {
        return $this->tableBodyWrkrDetailsEn;
    }

    public function getTableHeadWrkrDetailsEs() {
        return $this->tableHeadWrkrDetailsEs;
    }

    public function getTotalWrks() {
        return $this->totalWrks;
    }

    public function setTableHeadWrkrDetailsEn($tableHeadWrkrDetailsEn) {
        $this->tableHeadWrkrDetailsEn = $tableHeadWrkrDetailsEn;
    }

    public function setTableBodyWrkrDetailsEn($tableBodyWrkrDetailsEn) {
        $this->tableBodyWrkrDetailsEn = $tableBodyWrkrDetailsEn;
    }

    public function setTableHeadWrkrDetailsEs($tableHeadWrkrDetailsEs) {
        $this->tableHeadWrkrDetailsEs = $tableHeadWrkrDetailsEs;
    }

    public function setTotalWrks($totalWrks) {
        $this->totalWrks = $totalWrks;
    }

    public function getTableHeadCstmrDetailsEn() {
        return $this->tableHeadCstmrDetailsEn;
    }

    public function getTableBodyCstmrDetailsEn() {
        return $this->tableBodyCstmrDetailsEn;
    }

    public function getTableHeadCstmrDetailsEs() {
        return $this->tableHeadCstmrDetailsEs;
    }

    public function getTotalCstmrs() {
        return $this->totalCstmrs;
    }

    public function setTableHeadCstmrDetailsEn($tableHeadCstmrDetailsEn) {
        $this->tableHeadCstmrDetailsEn = $tableHeadCstmrDetailsEn;
    }

    public function setTableBodyCstmrDetailsEn($tableBodyCstmrDetailsEn) {
        $this->tableBodyCstmrDetailsEn = $tableBodyCstmrDetailsEn;
    }

    public function setTableHeadCstmrDetailsEs($tableHeadCstmrDetailsEs) {
        $this->tableHeadCstmrDetailsEs = $tableHeadCstmrDetailsEs;
    }

    public function setTotalCstmrs($totalCstmrs) {
        $this->totalCstmrs = $totalCstmrs;
    }

    public function getListRpts() {
        return $this->listRpts;
    }

    public function getTotalRpts() {
        return $this->totalRpts;
    }

    public function setListRpts($listRpts) {
        $this->listRpts = $listRpts;
    }

    public function setTotalRpts($totalRpts) {
        $this->totalRpts = $totalRpts;
    }

    public function getAllUserEntities() {
        return $this->allUserEntities;
    }

    public function setAllUserEntities($allUserEntities) {
        $this->allUserEntities = $allUserEntities;
    }

    public function getPublishBtn() {
        return $this->publishBtn;
    }

    public function setPublishBtn($publishBtn) {
        $this->publishBtn = $publishBtn;
    }

    public function getClientNamesList() {
        return $this->clientNamesList;
    }

    public function setClientNamesList($clientNamesList) {
        $this->clientNamesList = $clientNamesList;
    }

    public function getWorkerNamesList() {
        return $this->workerNamesList;
    }

    public function setWorkerNamesList($workerNamesList) {
        $this->workerNamesList = $workerNamesList;
    }

    public function getReportQFilterResList() {
        return $this->reportQFilterResList;
    }

    public function setReportQFilterResList($reportQFilterResList) {
        $this->reportQFilterResList = $reportQFilterResList;
    }

    public function getTotalServiceHrs() {
        return $this->totalServiceHrs;
    }

    public function setTotalServiceHrs($totalServiceHrs) {
        $this->totalServiceHrs = $totalServiceHrs;
    }

    public function getGraphRptMonthDataSet() {
        return $this->graphRptMonthDataSet;
    }

    public function setGraphRptMonthDataSet($graphRptMonthDataSet) {
        $this->graphRptMonthDataSet = $graphRptMonthDataSet;
    }
    
    public function getGraphTskStatusDataSet() {
        return $this->graphTskStatusDataSet;
    }

    public function setGraphTskStatusDataSet($graphTskStatusDataSet) {
        $this->graphTskStatusDataSet = $graphTskStatusDataSet;
    }
    
    public function getLastLogin() {
        return $this->lastLogin;
    }

    public function setLastLogin($lastLogin) {
        $this->lastLogin = $lastLogin;
    }
    
    public function getReportDetails() {
        return $this->reportDetails;
    }

    public function setReportDetails($reportDetails) {
        $this->reportDetails = $reportDetails;
    }

    public function getTableHeadPrdDetails() {
        return $this->tableHeadPrdDetails;
    }

    public function getTableHeadTskDetails() {
        return $this->tableHeadTskDetails;
    }

    public function getTableHeadWrkrDetails() {
        return $this->tableHeadWrkrDetails;
    }

    public function getTableHeadCstmrDetails() {
        return $this->tableHeadCstmrDetails;
    }

    public function setTableHeadPrdDetails($tableHeadPrdDetails) {
        $this->tableHeadPrdDetails = $tableHeadPrdDetails;
    }

    public function setTableHeadTskDetails($tableHeadTskDetails) {
        $this->tableHeadTskDetails = $tableHeadTskDetails;
    }

    public function setTableHeadWrkrDetails($tableHeadWrkrDetails) {
        $this->tableHeadWrkrDetails = $tableHeadWrkrDetails;
    }

    public function setTableHeadCstmrDetails($tableHeadCstmrDetails) {
        $this->tableHeadCstmrDetails = $tableHeadCstmrDetails;
    }


    
}
