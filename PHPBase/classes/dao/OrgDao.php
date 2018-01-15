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
require_once(dirname(__FILE__) . "/../inputs/XEditDynaValueInputs.php");
require_once(dirname(__FILE__) . "/../vo/DynaFields.php");
require_once(dirname(__FILE__) . "/../vo/Organization.php");
require_once(dirname(__FILE__) . "/../vo/OrgDynaProduct.php");
require_once(dirname(__FILE__) . "/../vo/OrgDynaTask.php");
require_once(dirname(__FILE__) . "/../vo/OrgDynaWorker.php");
require_once(dirname(__FILE__) . "/../vo/OrgDynaCustomer.php");
require_once(dirname(__FILE__) . "/../vo/OrgRptTemplate.php");
require_once(dirname(__FILE__) . "/../vo/OrgDynaReportStruct.php");
require_once(dirname(__FILE__) . "/../vo/OrgRptQFilter.php");


/**
 * Description of OrgDao
 *
 * @author Rishi Raj
 */
class OrgDao extends Dao{
    
    private $logger;
    
    private $productDynaFields;
    private $taskDynaFields;
    private $workerDynaFields;
    private $customerDynaFields;
    private $reportingDynaFields;
    
    private $orgDetails;
    private $orgProductDynaFields;
    private $orgTaskDynaFields;
    private $orgWorkerDynaField;
    private $orgCustomerDynaFields;
    
    private $isListLoaded;
    private $listOrgs;
    private $totalOrgs;
    
    private $lastLogin;
    private $rptTmpl_totalConfigs;
    private $rptTmpl_totalNonConfigs;
    private $rptQFilters_totalConfigs;
    private $rptQFilters_totalNonConfigs;
    private $rptStructs_totalConfigs;
    private $rptStructs_totalNonConfigs;
    private $graphOrgsRptsCountDataSet;
    
    private $templatesMap;
    private $templates;
    private $orgTemplateDetails;
    
    private $orgRptStructsFields;
    private $rptQFiltersList;
    
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
        
        /*
        * Templates - Any new template need to add here, it will also impact the same declaration in ReportPublishingEngine
        */
       $this->templatesMap = array(
                       'tmpl1' => RPT_TEMPLATE_MODEL_1,
                       'tmpl2' => RPT_TEMPLATE_MODEL_2
                       );
        
    }
    
    function __destruct() {
        parent::__destruct();
    }
    
    
    function loadDashBoardDetails(){
        
        $flag = FALSE;
        $totalOrgs = 0;
        $graphOrgDataset = array();
        $orgIds = array();
        
        try{
            $qMan = parent::getQueryManager();
            
            //1. Load Login time
            $sql = SQL_SELECT_ADMIN_LOGIN_TIME;
            
            $this->logger->debug('(loadDashBoardDetails) Loading login time - Query 1 : ' . $sql);
            
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
            
            //2. Get the Orgs count
            $sql = SQL_SELECT_ORGS_BY_NAME;
            
            $this->logger->debug('(loadDashBoardDetails) Counting Orgs and gettng orgs names - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $totalOrgs = mysql_num_rows($result);
                $this->setTotalOrgs($totalOrgs);
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    
                    $graphOrgDataset[$row['idorgs']] = $row['name'];
                    
                }
            }
            else{
                $this->setTotalOrgs(0);
                $this->logger->debug('No organization exists in the system.');
            }
            
            //3. Load Reporting Structure details
            $sql = SQL_SELECT_ORGS_RPT_STRUCTS_COUNT;
            
            $this->logger->debug('(loadDashBoardDetails) Counting configured Reporting Structures - Query 3 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                
                $rptStructs_totalConfigs = $row['count'];
                $this->setRptStructs_totalConfigs($rptStructs_totalConfigs);
                
                $rptStructs_totalNonConfigs = $totalOrgs - $rptStructs_totalConfigs;
                $this->setRptStructs_totalNonConfigs($rptStructs_totalNonConfigs);
            }
            else{
                $this->setRptStructs_totalConfigs(0);
                $this->setRptStructs_totalNonConfigs($totalOrgs);
                $this->logger->debug('No reporting structure configured for any org.');
            }
            
            //4. Load Reporting Template details
            $sql = SQL_SELECT_ORGS_RPT_TEMPLATE_COUNT;
            
            $this->logger->debug('(loadDashBoardDetails) Counting configured Reporting Templates - Query 4 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                
                $rptTmpl_totalConfigs = $row['count'];
                $this->setRptTmpl_totalConfigs($rptTmpl_totalConfigs);
                
                $rptTmpl_totalNonConfigs = $totalOrgs - $rptTmpl_totalConfigs;
                $this->setRptTmpl_totalNonConfigs($rptTmpl_totalNonConfigs);
            }
            else{
                $this->setRptTmpl_totalConfigs(0);
                $this->setRptTmpl_totalNonConfigs($totalOrgs);
                $this->logger->debug('No reporting template configured for any org.');
            }
            
            //5. Load Reporting Query Filter details
            $sql = SQL_SELECT_ORGS_RPT_QFILTERS_COUNT;
            
            $this->logger->debug('(loadDashBoardDetails) Counting configured Reporting Query filters - Query 5 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $row = $qMan->fetchSingleRow($result);
                
                $rptQFilters_totalConfigs = $row['count'];
                $this->setRptQFilters_totalConfigs($rptQFilters_totalConfigs);
                
                $rptQFilters_totalNonConfigs = $totalOrgs - $rptQFilters_totalConfigs;
                $this->setRptQFilters_totalNonConfigs($rptQFilters_totalNonConfigs);
            }
            else{
                $this->setRptQFilters_totalConfigs(0);
                $this->setRptQFilters_totalNonConfigs($totalOrgs);
                $this->logger->debug('No reporting query filter configured for any org.');
            }
            
            //6. Load Orgs Reports counts for graph
            $orgNames = '';
            $orgsRptsCounts = '';
            
            foreach ($graphOrgDataset as $key => $value){
                $sql = SQL_SELECT_USER_ORGS_RPTS_COUNT;
                
                $orgNames = $orgNames . '"' . $value . '"' . ", ";
                
                $sql = str_replace('P1', $key, $sql);
                
                $result = $qMan->query($sql);
                if (isset($result) && mysql_num_rows($result) > 0){
                    $row = $qMan->fetchSingleRow($result);
                    
                    $count = $row['count'];
                    
                    $orgsRptsCounts = $orgsRptsCounts . $count . ", ";
                }
                else{
                    $orgsRptsCounts = $orgsRptsCounts . 0 . ", ";
                }
                
            }
            if (!empty($orgNames)){
                $orgNames = substr($orgNames, 0, strlen($orgNames)-2);
            }
            if (!empty($orgsRptsCounts)){
                $orgsRptsCounts = substr($orgsRptsCounts, 0, strlen($orgsRptsCounts)-2);
            }

            $graphOrgsRptsCountDataSet = array(
                                            "lables" => $orgNames,
                                            "dataset" => $orgsRptsCounts
                                        );
            $this->setGraphOrgsRptsCountDataSet($graphOrgsRptsCountDataSet);
            
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_loadDashBoardDetails_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadOrgList($listMode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_SELECT_LIST_ORGS_DETAILS;
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $this->logger->debug('List of Organizations is being loaded.');
                
                $listOrgs = array();
                $totalOrgs = 0;
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    
                    $orgDetails = new Organization();
                    $orgDetails->setIdorgs($row['idorgs']);
                    $orgDetails->setName($row['name']);
                    $orgDetails->setPhone($row['phone']);
                    $orgDetails->setEmail($row['email']);
                    $orgDetails->setUsername($row['username']);
                    $orgDetails->setActivated($row['activated']);
                    
                    switch ($listMode) {
                        case ORG_LIST_DEFAULT:
                            
                            break;
                        
                        case ORG_LIST_TEMPLATES:
                            
                            $sql1 = SQL_SELECT_ORGS_TEMPLATE_DETAILS;
                            $sql1 = str_replace('P1', $row['idorgs'], $sql1);

                            $result1 = $qMan->query($sql1);
                            if (isset($result1) && mysql_num_rows($result1) > 0){
                                $row1 = $qMan->fetchSingleRow($result1);
                                $orgDetails->setTemplateName($row1['template_name']);
                                $orgDetails->setRawTemplateId($row1['rawtemplate_id']);
                                $templateName = $this->templatesMap[$row1['rawtemplate_id']];
                                $orgDetails->setTemplateModel($templateName);
                                $path = 'rptTemplates/pdfs/'.$row1['rawtemplate_id'];
                                $orgDetails->setTemplatePath($path);
                            }
                            else{
                                $orgDetails->setTemplateName('');
                            }
                            
                            break;
                            
                        case ORG_LIST_RPTSTRUCT:
                            
                            $sql1 = SQL_SELECT_ORGS_RPT_STRUCTURE_DETAILS;
                            $sql1 = str_replace('P1', $row['idorgs'], $sql1);

                            $result1 = $qMan->query($sql1);
                            if (isset($result1) && mysql_num_rows($result1) > 0){
                                $row1 = $qMan->fetchSingleRow($result1);
                                $orgDetails->setIsRptStructConfigured(TRUE);
                            }
                            else{
                                $orgDetails->setIsRptStructConfigured(FALSE);
                            }
                                
                            break;
                            
                        case ORG_LIST_QFILTERS:
                            
                            $sql1 = SQL_SELECT_ORGS_RPT_QFILTERS;
                            $sql1 = str_replace('P1', $row['idorgs'], $sql1);
                            
                            $result1 = $qMan->query($sql1);
                            if (isset($result1) && mysql_num_rows($result1) > 0){
                                $row1 = $qMan->fetchSingleRow($result1);
                                $orgDetails->setRptQFilters($row1['qfilters']);
                            }
                            else{
                                $orgDetails->setRptQFilters('');
                            }
                            
                            break;

                        default:
                            break;
                    }
                    
                    array_push($listOrgs, $orgDetails);
                    ++$totalOrgs;
                }
                
                $this->logger->debug('List of Organizations are loaded successfully.');
                $this->setListOrgs($listOrgs);
                $this->setTotalOrgs($totalOrgs);
                $this->setIsListLoaded(TRUE);
                $flag = TRUE;
            }
            else{
                $this->setIsListLoaded(FALSE);
                $this->setTotalOrgs(0);
                $this->logger->debug('No organization exists in the system, list cannot be generated.');
                $flag = TRUE;
                return $flag;
            }
            
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_loadOrgList_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    /*
     * Dependent Functions:
     * Dependent files: editorg, viewOrg, newRptStruct, help
     */
    function loadDynaFields(){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            //1. Load Prodcuts Dyna Fields
            $sql = SQL_SELECT_DYNAFIELDS_PRODCUTS;
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $this->logger->debug('Product Management dynamic fields exists, being loaded.');
                
                $productDynaFields = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $dynaFields = $this->populateDynaFieldsObj($row, 'idprdts_fields', DYNAFIELDS_PRODUCT_CBID_PREFIX);
                    array_push($productDynaFields, $dynaFields);
                }
                
                $this->setProductDynaFields($productDynaFields);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_loadDynaFields_MSG_1', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //2. Load Tasks Dyna Fields
            $sql = SQL_SELECT_DYNAFIELDS_TASKS;
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $this->logger->debug('Tasks Management dynamic fields exists, being loaded.');
                
                $tasksDynaFields = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $dynaFields = $this->populateDynaFieldsObj($row, 'idtsks_fields', DYNAFIELDS_TASK_CBID_PREFIX);
                    array_push($tasksDynaFields, $dynaFields);
                }
                
                $this->setTaskDynaFields($tasksDynaFields);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_loadDynaFields_MSG_2', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //3. Load Workers Dyna Fields
            $sql = SQL_SELECT_DYNAFIELDS_WORKERS;
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $this->logger->debug('Workers Management dynamic fields exists, being loaded.');
                
                $workerDynaFields = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $dynaFields = $this->populateDynaFieldsObj($row, 'idwrks_fields', DYNAFIELDS_WORKER_CBID_PREFIX);
                    array_push($workerDynaFields, $dynaFields);
                }
                
                $this->setWorkerDynaFields($workerDynaFields);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_loadDynaFields_MSG_3', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //4. Load Customers Dyna Fields
            $sql = SQL_SELECT_DYNAFIELDS_CUSTOMERS;
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $this->logger->debug('Customers Management dynamic fields exists, being loaded.');
                
                $customerDynaFields = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $dynaFields = $this->populateDynaFieldsObj($row, 'idcstmrs_fields', DYNAFIELDS_CUSTOMER_CBID_PREFIX);
                    array_push($customerDynaFields, $dynaFields);
                }
                
                $this->setCustomerDynaFields($customerDynaFields);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_loadDynaFields_MSG_4', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //5. Load Reporting Dyna Fields
            $sql = SQL_SELECT_DYNAFIELDS_REPORTING;
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $this->logger->debug('Reporting dynamic fields exists, being loaded.');
                
                $reportingDynaFields = array();
                
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $dynaFields = $this->populateDynaFieldsObj($row, 'idrpts_fields', DYNAFIELDS_REPORTING_CBID_PREFIX);
                    array_push($reportingDynaFields, $dynaFields);
                }
                
                $this->setReportingDynaFields($reportingDynaFields);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_loadDynaFields_MSG_5', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }

            
            $flag = TRUE;
            
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_loadDynaFields_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function saveOrg(OrgInputs $orgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $isOrgExist = $this->checkOrgExist($orgInputs->getIn_name());
            
            if ($isOrgExist){
                $this->logger->debug('The orgnization with this name already exists in the system.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_1', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //1. Save the record for Org details
            $sql = SQL_INSERT_ORGS;
            $sql = str_replace('P1', '\''.$orgInputs->getIn_name().'\'', $sql);
            $sql = str_replace('P2', '\''.$orgInputs->getIn_phone().'\'', $sql);
            $sql = str_replace('P3', '\''.$orgInputs->getIn_email().'\'', $sql);
            $sql = str_replace('P4', '\''.$orgInputs->getIn_username().'\'', $sql);
            $sql = str_replace('P5', '\''.$orgInputs->getIn_password().'\'', $sql);
            $sql = str_replace('P6', ORG_ACTIVATED, $sql);
            $sql = str_replace('P7', 'NOW()', $sql);
            
            $this->logger->debug('Saving Organisation - Query 1 : ' . $sql);
            
            $orgId = $qMan->queryInsertAndGetId($sql);
            
            if (!$orgId){
                $this->logger->error('Failed to insert the organisation details in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_2', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('Org Id generated for new record : ' . $orgId);
                
            //2. Create folder location in file server for the organisation
            $dir = 'orgdata/' . 'org_' . $orgId;
            $isDirCreated = mkdir($dir, 0777, true);

            if ($isDirCreated !== true){
                $this->logger->error('Failed to create dir in orgdata for orgnization : ' . $orgId);
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_3', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }

            $logoDir = $dir . '/logo';
            $templatesDir = $dir . '/templates';
            $reportsDir = $dir . '/reports';
            mkdir($logoDir);
            mkdir($templatesDir);
            mkdir($reportsDir);

            $this->logger->debug('dir created : ' . $dir);

            //3. Save the logo image in file server
            $tempImageDir = '../tempuploads';
            $file = '';
            if ($handle = opendir($tempImageDir)){
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != ".." && $entry != "thumbs") {
                        $file = $entry;
                    }
                }
                closedir($handle);
            }

            if (!empty($file)) {
                $ext = substr($file, strpos($file, '.'));
                rename($tempImageDir.'/'.$file, $logoDir.'/'.ORG_FOLDER_LOGO.$ext);

                $this->logger->debug('Logo File uploaded by user: ' . $file);
                $this->logger->debug('Logo is saved in the file server successfully after renamings');
            }
            //Logo is not mandatory, no need of error
            /*else{
                $this->logger->error('Failed to upload the logo on server');
                array_push($this->errors, 'Failed to upload the logo on server, plesae send this error to your administrator.');
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }*/

            //4. Save the dyna records for Products
            $postPrdIds = $orgInputs->getPrdDynaCBIds();
            $xEditValues = $this->fetchXEditDynaValues(DYNAFIELDS_TYPE_PRODUCT);
            $xEditValuesArr = explode(XEDIT_VALUES_SPEERATOR_LOCALES, $xEditValues);
            
            $sql = SQL_INSERT_ORGS_PRDTS;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P2', '\''.$postPrdIds.'\'', $sql);
            $sql = str_replace('P3', '\''.$xEditValuesArr[0].'\'', $sql);
            $sql = str_replace('P4', '\''.$xEditValuesArr[1].'\'', $sql);
            $sql = str_replace('P5', 'NOW()', $sql);
            
            $this->logger->debug('Saving Product Dyna Fields - Query 2 : ' . $sql);
            
            $prdDynaDBId = $qMan->queryInsertAndGetId($sql);
            
            if (!$prdDynaDBId){
                $this->logger->error('Failed to insert the Product Dyna details in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_4', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('Product Dyna Id generated for new record : ' . $prdDynaDBId);
            
            //5. Save the dyna records for Tasks
            $postTskIds = $orgInputs->getTskDynaCBIds();
            $xEditValues = $this->fetchXEditDynaValues(DYNAFIELDS_TYPE_TASKS);
            $xEditValuesArr = explode(XEDIT_VALUES_SPEERATOR_LOCALES, $xEditValues);
            
            $sql = SQL_INSERT_ORGS_TSKS;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P2', '\''.$postTskIds.'\'', $sql);
            $sql = str_replace('P3', '\''.$xEditValuesArr[0].'\'', $sql);
            $sql = str_replace('P4', '\''.$xEditValuesArr[1].'\'', $sql);
            $sql = str_replace('P5', 'NOW()', $sql);
            
            $this->logger->debug('Saving Task Dyna Fields - Query 3 : ' . $sql);
            
            $tskDynaDBId = $qMan->queryInsertAndGetId($sql);
            
            if (!$tskDynaDBId){
                $this->logger->error('Failed to insert the Task Dyna details in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_5', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('Task Dyna Id generated for new record : ' . $tskDynaDBId);

            //6. Save the dyna records for Workers
            $postWrkIds = $orgInputs->getWrkDynaCBIds();
            $xEditValues = $this->fetchXEditDynaValues(DYNAFIELDS_TYPE_WORKERS);
            $xEditValuesArr = explode(XEDIT_VALUES_SPEERATOR_LOCALES, $xEditValues);
            
            $sql = SQL_INSERT_ORGS_WRKS;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P2', '\''.$postWrkIds.'\'', $sql);
            $sql = str_replace('P3', '\''.$xEditValuesArr[0].'\'', $sql);
            $sql = str_replace('P4', '\''.$xEditValuesArr[1].'\'', $sql);
            $sql = str_replace('P5', 'NOW()', $sql);
            
            $this->logger->debug('Saving Worker Dyna Fields - Query 4 : ' . $sql);
            
            $wrkDynaDBId = $qMan->queryInsertAndGetId($sql);
            
            if (!$wrkDynaDBId){
                $this->logger->error('Failed to insert the Worker Dyna details in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_6', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('Worker Dyna Id generated for new record : ' . $wrkDynaDBId);

            //7. Save the dyna records for Customers
            $postCstIds = $orgInputs->getCstDynaCBIds();
            $xEditValues = $this->fetchXEditDynaValues(DYNAFIELDS_TYPE_CUSTOMERS);
            $xEditValuesArr = explode(XEDIT_VALUES_SPEERATOR_LOCALES, $xEditValues);
            
            $sql = SQL_INSERT_ORGS_CSTMRS;
            $sql = str_replace('P1', $orgId, $sql);
            $sql = str_replace('P2', '\''.$postCstIds.'\'', $sql);
            $sql = str_replace('P3', '\''.$xEditValuesArr[0].'\'', $sql);
            $sql = str_replace('P4', '\''.$xEditValuesArr[1].'\'', $sql);
            $sql = str_replace('P5', 'NOW()', $sql);
            
            $this->logger->debug('Saving Customer Dyna Fields - Query 5 : ' . $sql);
            
            $cstDynaDBId = $qMan->queryInsertAndGetId($sql);
            
            if (!$cstDynaDBId){
                $this->logger->error('Failed to insert the Customer Dyna details in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_7', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('Customer Dyna Id generated for new record : ' . $cstDynaDBId);
            
            //8. Remove all the temp entries of xEdit Dyna List values
            $this->deleteXEditDynaValues('all', '');
            
            //9. Multi-Tenancy - Create Org Products table
            $sql = SQL_CREATE_TABLE_ORG_PRDS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Creating table structure for Products - Query 6 : ' . $sql);
            
            $table = $qMan->query($sql);
            
            if (!$table){
                $this->logger->error('Failed to create table structure for Products in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_8', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('Structure for Products created successfully.');
            
            //10. Multi-Tenancy - Create Org Tasks table
            $sql = SQL_CREATE_TABLE_ORG_TSKS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Creating table structure for Tasks - Query 7 : ' . $sql);
            
            $table = $qMan->query($sql);
            
            if (!$table){
                $this->logger->error('Failed to create table structure for Tasks in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_9', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('Structure for Tasks created successfully.');
            
            //11. Multi-Tenancy - Create Org Workers table
            $sql = SQL_CREATE_TABLE_ORG_WRKS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Creating table structure for Workers - Query 8 : ' . $sql);
            
            $table = $qMan->query($sql);
            
            if (!$table){
                $this->logger->error('Failed to create table structure for Workers in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_10', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('Structure for Workers created successfully.');
            
            //12. Multi-Tenancy - Create Org End-Customers table
            $sql = SQL_CREATE_TABLE_ORG_CSTMRS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Creating table structure for Customers - Query 9 : ' . $sql);
            
            $table = $qMan->query($sql);
            
            if (!$table){
                $this->logger->error('Failed to create table structure for Customers in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_11', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('Structure for Customers created successfully.');
            
            //13. Multi-Tenancy - Create Org Reporting table
            $sql = SQL_CREATE_TABLE_ORG_RPTS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Creating table structure for Reporting - Query 10 : ' . $sql);
            
            $table = $qMan->query($sql);
            
            if (!$table){
                $this->logger->error('Failed to create table structure for Reporting in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrg_MSG_12', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $this->logger->debug('Structure for Reporting created successfully.');


            $this->logger->debug('All details of new organization are saved successfully. New Organization is created.');
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            $this->setCompleteMsg(getLocaleText('OrgDao_saveOrg_MSG_13', TXT_A));
            
            
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_saveOrg_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function cancelOrg(){
        
        //1. Remove all the unused files from temp upload folder
        $tempImageDir = '../tempuploads';
        if ($handle = opendir($tempImageDir)){
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && $entry != "thumbs") {
                    if (!is_dir($tempImageDir.'/'.$entry)){
                        unlink($tempImageDir.'/'.$entry);
                    }
                }
            }
            closedir($handle);
        }
        
        //2. Remove all the temp entries of xEdit Dyna List values
        $this->deleteXEditDynaValues('all', '');
        
    }
    
    /*
     * Dependent Functions: 
     */
    function fetchOrg(OrgInputs $orgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $orgInputs->getIdorgs();
            
            //1. Load Org Details
            $sql = SQL_SELECT_ORGS_DETAILS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Loading Org Details - Query 1 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $this->logger->debug('Loading details for Org : '. $orgId);
                
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
                array_push($this->errors, getLocaleText('OrgDao_fetchOrg_MSG_1', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //2. Load Org CoBranding Details
            $dir = 'orgdata/' . 'org_' . $orgId;
            $logoDir = $dir . '/logo';
            
            $file = '';
            if ($handle = opendir($logoDir)){
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {
                        $file = $entry;
                    }
                }
                closedir($handle);
            }
            if (!empty($file)){
                $this->logger->debug('Logo exists for this organization, loading it from : ' . $logoDir);
                $this->getOrgDetails()->setLogoPath($logoDir.'/'.$file);
            }
            else{
                $this->logger->debug('No Logo exists for this organization.');
                $this->getOrgDetails()->setLogoPath('No logo found for this organization');
            }
            
            //3. Load dyna records for Products
            $sql = SQL_SELECT_ORGS_PRODUCTS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Loading dyna records for Products Details - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $orgDynaProduct = new OrgDynaProduct();
                $orgDynaProduct->setIdorgs($row['idorgs_prdts']);
                $orgDynaProduct->setDynaFieldIds($row['dyna_fields_ids']);
                $orgDynaProduct->setDyna_fields_list_values_en($row['dyna_fields_list_values_en']);
                $orgDynaProduct->setDyna_fields_list_values_es($row['dyna_fields_list_values_es']);

                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);

                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);
                
                $dynaFieldsListValuesEs = $row['dyna_fields_list_values_es'];
                $dynaFieldsListValuesEsArr = explode('|', $dynaFieldsListValuesEs);

                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    $consLocaleBasedListValues = '';
                    foreach ($dynaFieldsListValuesEnArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValueEn = substr($value, strlen($dynaFieldsId.':'));
                            $dynaFieldsValueEs = '';
                            foreach ($dynaFieldsListValuesEsArr as $value) {
                                if (strpos($value, $dynaFieldsId.':') !== FALSE){
                                    $dynaFieldsValueEs = substr($value, strlen($dynaFieldsId.':'));
                                }
                            }
                            
                            $dynaFieldsValueEnArr = explode(XEDIT_VALUES_SPEERATOR_ON_SUBMIT, $dynaFieldsValueEn);
                            $dynaFieldsValueEsArr = explode(XEDIT_VALUES_SPEERATOR_ON_SUBMIT, $dynaFieldsValueEs);
                            $size = sizeof($dynaFieldsValueEnArr);
                            if ($size > 0){
                                for ($ctr = 0 ; $ctr < $size; ++$ctr){
                                    $consLocaleBasedListValues = $consLocaleBasedListValues . $dynaFieldsValueEnArr[$ctr] . DYNAFIELDS_LIST_VALUES_LOCALE_SEPERATOR . $dynaFieldsValueEsArr[$ctr] .',' ;
                                }
                                $consLocaleBasedListValues = substr($consLocaleBasedListValues, 0, strlen($consLocaleBasedListValues)-1);
                            }
                            break;
                        }
                    }
                    if (!empty($consLocaleBasedListValues) && $consLocaleBasedListValues != DYNAFIELDS_LIST_VALUES_LOCALE_SEPERATOR){
                        $dynaFieldsValue = $consLocaleBasedListValues;
                    }
                    
                    $dynaFieldIdDetail = array(
                                        'fieldId' => DYNAFIELDS_PRODUCT_CBID_PREFIX.$dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                }
                $orgDynaProduct->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $this->setOrgProductDynaFields($orgDynaProduct);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_fetchOrg_MSG_2', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //4. Load dyna records for Tasks
            $sql = SQL_SELECT_ORGS_TASKS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Loading dyna records for Tasks Details - Query 3 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $orgDynaTask = new OrgDynaTask();
                $orgDynaTask->setIdorgs($row['idorgs_tsks']);
                $orgDynaTask->setDynaFieldIds($row['dyna_fields_ids']);
                $orgDynaTask->setDyna_fields_list_values_en($row['dyna_fields_list_values_en']);
                $orgDynaTask->setDyna_fields_list_values_es($row['dyna_fields_list_values_es']);

                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);

                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);
                
                $dynaFieldsListValuesEs = $row['dyna_fields_list_values_es'];
                $dynaFieldsListValuesEsArr = explode('|', $dynaFieldsListValuesEs);

                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    $consLocaleBasedListValues = '';
                    foreach ($dynaFieldsListValuesEnArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValueEn = substr($value, strlen($dynaFieldsId.':'));
                            $dynaFieldsValueEs = '';
                            foreach ($dynaFieldsListValuesEsArr as $value) {
                                if (strpos($value, $dynaFieldsId.':') !== FALSE){
                                    $dynaFieldsValueEs = substr($value, strlen($dynaFieldsId.':'));
                                }
                            }
                            
                            $dynaFieldsValueEnArr = explode(XEDIT_VALUES_SPEERATOR_ON_SUBMIT, $dynaFieldsValueEn);
                            $dynaFieldsValueEsArr = explode(XEDIT_VALUES_SPEERATOR_ON_SUBMIT, $dynaFieldsValueEs);
                            $size = sizeof($dynaFieldsValueEnArr);
                            if ($size > 0){
                                for ($ctr = 0 ; $ctr < $size; ++$ctr){
                                    $consLocaleBasedListValues = $consLocaleBasedListValues . $dynaFieldsValueEnArr[$ctr] . DYNAFIELDS_LIST_VALUES_LOCALE_SEPERATOR . $dynaFieldsValueEsArr[$ctr] .',' ;
                                }
                                $consLocaleBasedListValues = substr($consLocaleBasedListValues, 0, strlen($consLocaleBasedListValues)-1);
                            }
                            break;
                        }
                    }
                    if (!empty($consLocaleBasedListValues) && $consLocaleBasedListValues != DYNAFIELDS_LIST_VALUES_LOCALE_SEPERATOR){
                        $dynaFieldsValue = $consLocaleBasedListValues;
                    }
                    
                    $dynaFieldIdDetail = array(
                                        'fieldId' => DYNAFIELDS_TASK_CBID_PREFIX.$dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                }
                $orgDynaTask->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $this->setOrgTaskDynaFields($orgDynaTask);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_fetchOrg_MSG_3', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //5. Load dyna records for Workers
            $sql = SQL_SELECT_ORGS_WORKERS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Loading dyna records for Workers Details - Query 4 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $orgDynaWorker = new OrgDynaWorker();
                $orgDynaWorker->setIdorgs($row['idorgs_wrks']);
                $orgDynaWorker->setDynaFieldIds($row['dyna_fields_ids']);
                $orgDynaWorker->setDyna_fields_list_values_en($row['dyna_fields_list_values_en']);
                $orgDynaWorker->setDyna_fields_list_values_es($row['dyna_fields_list_values_es']);

                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);

                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);
                
                $dynaFieldsListValuesEs = $row['dyna_fields_list_values_es'];
                $dynaFieldsListValuesEsArr = explode('|', $dynaFieldsListValuesEs);

                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    $consLocaleBasedListValues = '';
                    foreach ($dynaFieldsListValuesEnArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValueEn = substr($value, strlen($dynaFieldsId.':'));
                            $dynaFieldsValueEs = '';
                            foreach ($dynaFieldsListValuesEsArr as $value) {
                                if (strpos($value, $dynaFieldsId.':') !== FALSE){
                                    $dynaFieldsValueEs = substr($value, strlen($dynaFieldsId.':'));
                                }
                            }
                            
                            $dynaFieldsValueEnArr = explode(XEDIT_VALUES_SPEERATOR_ON_SUBMIT, $dynaFieldsValueEn);
                            $dynaFieldsValueEsArr = explode(XEDIT_VALUES_SPEERATOR_ON_SUBMIT, $dynaFieldsValueEs);
                            $size = sizeof($dynaFieldsValueEnArr);
                            if ($size > 0){
                                for ($ctr = 0 ; $ctr < $size; ++$ctr){
                                    $consLocaleBasedListValues = $consLocaleBasedListValues . $dynaFieldsValueEnArr[$ctr] . DYNAFIELDS_LIST_VALUES_LOCALE_SEPERATOR . $dynaFieldsValueEsArr[$ctr] .',' ;
                                }
                                $consLocaleBasedListValues = substr($consLocaleBasedListValues, 0, strlen($consLocaleBasedListValues)-1);
                            }
                            break;
                        }
                    }
                    if (!empty($consLocaleBasedListValues) && $consLocaleBasedListValues != DYNAFIELDS_LIST_VALUES_LOCALE_SEPERATOR){
                        $dynaFieldsValue = $consLocaleBasedListValues;
                    }
                    
                    $dynaFieldIdDetail = array(
                                        'fieldId' => DYNAFIELDS_WORKER_CBID_PREFIX.$dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                }
                $orgDynaWorker->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $this->setOrgWorkerDynaField($orgDynaWorker);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_fetchOrg_MSG_4', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //6. Load dyna records for Customers
            $sql = SQL_SELECT_ORGS_CUSTOMERS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Loading dyna records for Customer Details - Query 5 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $orgDynaCustomer = new OrgDynaCustomer();
                $orgDynaCustomer->setIdorgs($row['idorgs_cstmrs']);
                $orgDynaCustomer->setDynaFieldIds($row['dyna_fields_ids']);
                $orgDynaCustomer->setDyna_fields_list_values_en($row['dyna_fields_list_values_en']);
                $orgDynaCustomer->setDyna_fields_list_values_es($row['dyna_fields_list_values_es']);

                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);

                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);
                
                $dynaFieldsListValuesEs = $row['dyna_fields_list_values_es'];
                $dynaFieldsListValuesEsArr = explode('|', $dynaFieldsListValuesEs);

                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    $consLocaleBasedListValues = '';
                    foreach ($dynaFieldsListValuesEnArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValueEn = substr($value, strlen($dynaFieldsId.':'));
                            $dynaFieldsValueEs = '';
                            foreach ($dynaFieldsListValuesEsArr as $value) {
                                if (strpos($value, $dynaFieldsId.':') !== FALSE){
                                    $dynaFieldsValueEs = substr($value, strlen($dynaFieldsId.':'));
                                }
                            }
                            
                            $dynaFieldsValueEnArr = explode(XEDIT_VALUES_SPEERATOR_ON_SUBMIT, $dynaFieldsValueEn);
                            $dynaFieldsValueEsArr = explode(XEDIT_VALUES_SPEERATOR_ON_SUBMIT, $dynaFieldsValueEs);
                            $size = sizeof($dynaFieldsValueEnArr);
                            if ($size > 0){
                                for ($ctr = 0 ; $ctr < $size; ++$ctr){
                                    $consLocaleBasedListValues = $consLocaleBasedListValues . $dynaFieldsValueEnArr[$ctr] . DYNAFIELDS_LIST_VALUES_LOCALE_SEPERATOR . $dynaFieldsValueEsArr[$ctr] .',' ;
                                }
                                $consLocaleBasedListValues = substr($consLocaleBasedListValues, 0, strlen($consLocaleBasedListValues)-1);
                            }
                            break;
                        }
                    }
                    if (!empty($consLocaleBasedListValues) && $consLocaleBasedListValues != DYNAFIELDS_LIST_VALUES_LOCALE_SEPERATOR){
                        $dynaFieldsValue = $consLocaleBasedListValues;
                    }
                    
                    $dynaFieldIdDetail = array(
                                        'fieldId' => DYNAFIELDS_CUSTOMER_CBID_PREFIX.$dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                }
                $orgDynaCustomer->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $this->setOrgCustomerDynaFields($orgDynaCustomer);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_fetchOrg_MSG_5', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            
            $this->logger->debug('All details of organization : ' . $orgId . ' are loaded successfully.');
            $flag = TRUE;
            
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_fetchOrg_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
    }
    
    function updateOrg(OrgInputs $orgInputs, $updatedPart){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            switch ($updatedPart) {
                case ORG_UPDATE_PART_ORG_DETAILS:
                    
                    $sql = SQL_UPDATE_ORGS;
                    $sql = str_replace('P1', '\''.$orgInputs->getIn_name().'\'', $sql);
                    $sql = str_replace('P2', '\''.$orgInputs->getIn_phone().'\'', $sql);
                    $sql = str_replace('P3', '\''.$orgInputs->getIn_email().'\'', $sql);
                    $sql = str_replace('P4', '\''.$orgInputs->getIn_username().'\'', $sql);
                    $sql = str_replace('P5', '\''.$orgInputs->getIn_password().'\'', $sql);
                    $sql = str_replace('P6', 'NOW()', $sql);
                    $sql = str_replace('P7', $orgInputs->getIdorgs(), $sql);

                    $this->logger->debug('Updating Organisation - Query 1 : ' . $sql);

                    $update = $qMan->update($sql);

                    if (!$update){
                        $this->logger->error('Failed to update the organisation details in the system, some internal error occured.');
                        array_push($this->errors, getLocaleText('OrgDao_updateOrg_MSG_1', TXT_A));
                        $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                        return $flag;
                    }

                    break;
                    
                case ORG_UPDATE_PART_ORG_COBRADING:
                    
                    $dir = 'orgdata/' . 'org_' . $orgInputs->getIdorgs();
                    $logoDir = $dir . '/logo';

                    $tempImageDir = '../tempuploads';
                    $file = '';
                    if ($handle = opendir($tempImageDir)){
                        while (false !== ($entry = readdir($handle))) {
                            if ($entry != "." && $entry != ".." && $entry != "thumbs") {
                                $file = $entry;
                            }
                        }
                        closedir($handle);
                    }

                    if (!empty($file)) {
                        $ext = substr($file, strpos($file, '.'));
                        rename($tempImageDir.'/'.$file, $logoDir.'/'.ORG_FOLDER_LOGO.$ext);

                        $this->logger->debug('Logo File uploaded by user: ' . $file);
                        $this->logger->debug('Logo is updated in the file server successfully after renamings');
                    }
                    
                    break;
                
                case ORG_UPDATE_PART_ORG_PRODUCT_DETAILS:
                    
                    $postPrdIds = $orgInputs->getPrdDynaCBIds();
                    $xEditValues = $this->fetchXEditDynaValues(DYNAFIELDS_TYPE_PRODUCT);
                    $xEditValuesArr = explode(XEDIT_VALUES_SPEERATOR_LOCALES, $xEditValues);
            
                    $sql = SQL_UPDATE_ORGS_PRDTS;
                    $sql = str_replace('P1', '\''.$postPrdIds.'\'', $sql);
                    $sql = str_replace('P2', '\''.$xEditValuesArr[0].'\'', $sql);
                    $sql = str_replace('P3', '\''.$xEditValuesArr[1].'\'', $sql);
                    $sql = str_replace('P3', '\'\'', $sql);
                    $sql = str_replace('P4', 'NOW()', $sql);
                    $sql = str_replace('P5', $orgInputs->getIdorgs(), $sql);

                    $this->logger->debug('Updating Product Dyna Fields - Query 2 : ' . $sql);

                    $update = $qMan->update($sql);

                    if (!$update){
                        $this->logger->error('Failed to update the Product Dyna details in the system, some internal error occured.');
                        array_push($this->errors, getLocaleText('OrgDao_updateOrg_MSG_2', TXT_A));
                        $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                        return $flag;
                    }
                    
                    break;
                
                case ORG_UPDATE_PART_ORG_TASK_DETAILS:
                    
                    $postTskIds = $orgInputs->getTskDynaCBIds();
                    $xEditValues = $this->fetchXEditDynaValues(DYNAFIELDS_TYPE_TASKS);
                    $xEditValuesArr = explode(XEDIT_VALUES_SPEERATOR_LOCALES, $xEditValues);
            
                    $sql = SQL_UPDATE_ORGS_TSKS;
                    $sql = str_replace('P1', '\''.$postTskIds.'\'', $sql);
                    $sql = str_replace('P2', '\''.$xEditValuesArr[0].'\'', $sql);
                    $sql = str_replace('P3', '\''.$xEditValuesArr[1].'\'', $sql);
                    $sql = str_replace('P4', 'NOW()', $sql);
                    $sql = str_replace('P5', $orgInputs->getIdorgs(), $sql);

                    $this->logger->debug('Updating Task Dyna Fields - Query 3 : ' . $sql);

                    $update = $qMan->update($sql);

                    if (!$update){
                        $this->logger->error('Failed to update the Task Dyna details in the system, some internal error occured.');
                        array_push($this->errors, getLocaleText('OrgDao_updateOrg_MSG_3', TXT_A));
                        $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                        return $flag;
                    }
                    
                    break;
                
                case ORG_UPDATE_PART_ORG_WORKER_DETAILS:
                    
                    $postWrkIds = $orgInputs->getWrkDynaCBIds();
                    $xEditValues = $this->fetchXEditDynaValues(DYNAFIELDS_TYPE_WORKERS);
                    $xEditValuesArr = explode(XEDIT_VALUES_SPEERATOR_LOCALES, $xEditValues);
            
                    $sql = SQL_UPDATE_ORGS_WRKS;
                    $sql = str_replace('P1', '\''.$postWrkIds.'\'', $sql);
                    $sql = str_replace('P2', '\''.$xEditValuesArr[0].'\'', $sql);
                    $sql = str_replace('P3', '\''.$xEditValuesArr[1].'\'', $sql);
                    $sql = str_replace('P4', 'NOW()', $sql);
                    $sql = str_replace('P5', $orgInputs->getIdorgs(), $sql);

                    $this->logger->debug('Updating Worker Dyna Fields - Query 4 : ' . $sql);

                    $update = $qMan->update($sql);

                    if (!$update){
                        $this->logger->error('Failed to update the Worker Dyna details in the system, some internal error occured.');
                        array_push($this->errors, getLocaleText('OrgDao_updateOrg_MSG_4', TXT_A));
                        $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                        return $flag;
                    }
                    
                    break;
                
                case ORG_UPDATE_PART_ORG_CUSTOMER_DETAILS:
                    
                    $postCstIds = $orgInputs->getCstDynaCBIds();
                    $xEditValues = $this->fetchXEditDynaValues(DYNAFIELDS_TYPE_CUSTOMERS);
                    $xEditValuesArr = explode(XEDIT_VALUES_SPEERATOR_LOCALES, $xEditValues);
            
                    $sql = SQL_UPDATE_ORGS_CSTMRS;
                    $sql = str_replace('P1', '\''.$postCstIds.'\'', $sql);
                    $sql = str_replace('P2', '\''.$xEditValuesArr[0].'\'', $sql);
                    $sql = str_replace('P3', '\''.$xEditValuesArr[1].'\'', $sql);
                    $sql = str_replace('P4', 'NOW()', $sql);
                    $sql = str_replace('P5', $orgInputs->getIdorgs(), $sql);

                    $this->logger->debug('Updating Customer Dyna Fields - Query 4 : ' . $sql);

                    $update = $qMan->update($sql);

                    if (!$update){
                        $this->logger->error('Failed to update the Customer Dyna details in the system, some internal error occured.');
                        array_push($this->errors, getLocaleText('OrgDao_updateOrg_MSG_5', TXT_A));
                        $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                        return $flag;
                    }
                    
                    break;

                default:
                    break;
            }
            
            //Remove all the temp entries of xEdit Dyna List values
            $this->deleteXEditDynaValues('all', '');


            $this->logger->debug('New details of organization are updated successfully.');
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            $this->setCompleteMsg(getLocaleText('OrgDao_updateOrg_MSG_6', TXT_A));
            
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_updateOrg_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function viewOrg(OrgInputs $orgInputs){
        $this->logger->debug('Loading details for View Organization...');
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $orgInputs->getIdorgs();
            
            //1. Load Org Details
            $sql = SQL_SELECT_ORGS_DETAILS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Loading Org Details - Query 1 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $this->logger->debug('Loading details for Org : '. $orgId);
                
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
                array_push($this->errors, getLocaleText('OrgDao_viewOrg_MSG_1', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //2. Load Org CoBranding Details
            $dir = 'orgdata/' . 'org_' . $orgId;
            $logoDir = $dir . '/logo';
            
            $file = '';
            if ($handle = opendir($logoDir)){
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {
                        $file = $entry;
                    }
                }
                closedir($handle);
            }
            if (!empty($file)){
                $this->logger->debug('Logo exists for this organization, loading it from : ' . $logoDir);
                $this->getOrgDetails()->setLogoPath($logoDir.'/'.$file);
            }
            else{
                $this->logger->debug('No Logo exists for this organization.');
                $this->getOrgDetails()->setLogoPath('No logo found for this organization');
            }
            
            //3. Load dyna records for Products
            $sql = SQL_SELECT_ORGS_PRODUCTS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Loading dyna records for Products Details - Query 2 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $orgDynaProduct = new OrgDynaProduct();
                $orgDynaProduct->setIdorgs($row['idorgs_prdts']);
                $orgDynaProduct->setDynaFieldIds($row['dyna_fields_ids']);
                $orgDynaProduct->setDyna_fields_list_values_en($row['dyna_fields_list_values_en']);
                $orgDynaProduct->setDyna_fields_list_values_es($row['dyna_fields_list_values_es']);

                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);

                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);

                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    foreach ($dynaFieldsListValuesEnArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValue = substr($value, strlen($dynaFieldsId.':'));
                        }
                    }
                    $dynaFieldIdDetail = array(
                                        'fieldId' => DYNAFIELDS_PRODUCT_CBID_PREFIX.$dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                }
                $orgDynaProduct->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $this->setOrgProductDynaFields($orgDynaProduct);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_viewOrg_MSG_2', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //4. Load dyna records for Tasks
            $sql = SQL_SELECT_ORGS_TASKS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Loading dyna records for Tasks Details - Query 3 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $orgDynaTask = new OrgDynaTask();
                $orgDynaTask->setIdorgs($row['idorgs_tsks']);
                $orgDynaTask->setDynaFieldIds($row['dyna_fields_ids']);
                $orgDynaTask->setDyna_fields_list_values_en($row['dyna_fields_list_values_en']);
                $orgDynaTask->setDyna_fields_list_values_es($row['dyna_fields_list_values_es']);

                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);

                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);

                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    foreach ($dynaFieldsListValuesEnArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValue = substr($value, strlen($dynaFieldsId.':'));
                        }
                    }
                    $dynaFieldIdDetail = array(
                                        'fieldId' => DYNAFIELDS_TASK_CBID_PREFIX.$dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                }
                $orgDynaTask->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $this->setOrgTaskDynaFields($orgDynaTask);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_viewOrg_MSG_3', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //5. Load dyna records for Workers
            $sql = SQL_SELECT_ORGS_WORKERS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Loading dyna records for Workers Details - Query 4 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $orgDynaWorker = new OrgDynaWorker();
                $orgDynaWorker->setIdorgs($row['idorgs_wrks']);
                $orgDynaWorker->setDynaFieldIds($row['dyna_fields_ids']);
                $orgDynaWorker->setDyna_fields_list_values_en($row['dyna_fields_list_values_en']);
                $orgDynaWorker->setDyna_fields_list_values_es($row['dyna_fields_list_values_es']);

                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);

                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);

                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    foreach ($dynaFieldsListValuesEnArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValue = substr($value, strlen($dynaFieldsId.':'));
                        }
                    }
                    $dynaFieldIdDetail = array(
                                        'fieldId' => DYNAFIELDS_WORKER_CBID_PREFIX.$dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                }
                $orgDynaWorker->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $this->setOrgWorkerDynaField($orgDynaWorker);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_viewOrg_MSG_4', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            //6. Load dyna records for Customers
            $sql = SQL_SELECT_ORGS_CUSTOMERS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Loading dyna records for Customer Details - Query 5 : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                
                $row = $qMan->fetchSingleRow($result);
                
                $orgDynaCustomer = new OrgDynaCustomer();
                $orgDynaCustomer->setIdorgs($row['idorgs_cstmrs']);
                $orgDynaCustomer->setDynaFieldIds($row['dyna_fields_ids']);
                $orgDynaCustomer->setDyna_fields_list_values_en($row['dyna_fields_list_values_en']);
                $orgDynaCustomer->setDyna_fields_list_values_es($row['dyna_fields_list_values_es']);

                $dynaFieldsIds = $row['dyna_fields_ids'];
                $dynaFieldsIdsArr = explode('|', $dynaFieldsIds);

                $dynaFieldsListValuesEn = $row['dyna_fields_list_values_en'];
                $dynaFieldsListValuesEnArr = explode('|', $dynaFieldsListValuesEn);
                
                $dynaFieldsProcessedDetails = array();

                foreach ($dynaFieldsIdsArr as $dynaFieldsId) {
                    $dynaFieldsValue = 'none';
                    foreach ($dynaFieldsListValuesEnArr as $value) {
                        if (strpos($value, $dynaFieldsId.':') !== FALSE){
                            $dynaFieldsValue = substr($value, strlen($dynaFieldsId.':'));
                        }
                    }
                    $dynaFieldIdDetail = array(
                                        'fieldId' => DYNAFIELDS_CUSTOMER_CBID_PREFIX.$dynaFieldsId,
                                        'fieldValues' => $dynaFieldsValue
                                    );
                    array_push($dynaFieldsProcessedDetails, $dynaFieldIdDetail);
                }
                $orgDynaCustomer->setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails);
                
                $this->setOrgCustomerDynaFields($orgDynaCustomer);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_viewOrg_MSG_5', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            
            $this->logger->debug('All details of organization : ' . $orgId . ' are loaded successfully for Viewing.');
            $flag = TRUE;
            
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_viewOrg_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function actDeactOrg(OrgInputs $orgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_UPDATE_ORGS_ACTDEACTIVATE;
            $sql = str_replace('P1', $orgInputs->getActDeactivated(), $sql);
            $sql = str_replace('P2', 'NOW()', $sql);
            $sql = str_replace('P3', $orgInputs->getIdorgs(), $sql);

            $this->logger->debug('Updating Organization [Act/Deact] - Query : ' . $sql);

            $update = $qMan->update($sql);
            if (!$update){
                $this->logger->error('Failed to update the Organization [Act/Deact] details in the system, some internal error occured.');
                array_push($this->errors, getLocaleText('OrgDao_actDeactOrg_MSG_1', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            if ($orgInputs->getActDeactivated() == ORG_ACTIVATED){
                $this->logger->debug('Organization \'activated\' updated successfully.');
                $this->setCompleteMsg('<strong>' . $orgInputs->getIn_name() . '</strong> ' . getLocaleText('OrgDao_actDeactOrg_MSG_2', TXT_A));
            }
            else{
                $this->logger->debug('Organization \'deactivated\' updated successfully.');
                $this->setCompleteMsg('<strong>' . $orgInputs->getIn_name() . '</strong> ' . getLocaleText('OrgDao_actDeactOrg_MSG_3', TXT_A));
            }
            
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_actDeactOrg_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function deleteOrg(OrgInputs $orgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $orgInputs->getIdorgs();
            
            //1. Delete the dyna records from Products
            $sql = SQL_DELETE_ORGS_PRDTS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Deleting Product Dyna Fields - Query 1 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to delete the Products Dyna details from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrg_MSG_1', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            //2. Delete the dyna records from Tasks
            $sql = SQL_DELETE_ORGS_TSKS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Deleting Tasks Dyna Fields - Query 2 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to delete the Tasks Dyna details from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrg_MSG_2', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            //3. Delete the dyna records from Workers
            $sql = SQL_DELETE_ORGS_WRKS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Deleting Workers Dyna Fields - Query 3 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to delete the Workers Dyna details from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrg_MSG_3', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            //4. Delete the dyna records from Customers
            $sql = SQL_DELETE_ORGS_CSTMRS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Deleting Customers Dyna Fields - Query 4 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to delete the Customers Dyna details from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrg_MSG_4', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            //5. Delete the folder structure
            $dir = 'orgdata/' . 'org_' . $orgId;
            if (file_exists($dir)){
                $dirDeleted = $this->delTree($dir);
                if (!$dirDeleted){
                    $this->logger->error('Failed to remove the structure from the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('OrgDao_deleteOrg_MSG_5', TXT_A));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
                else{
                    $this->logger->error('Resources structure removed.');
                }
            }
            
            //6. Delete the template record
            $sql = SQL_DELETE_ORGS_TEMPLATES;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Deleting Template record - Query 5 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->debug('Not able to delete template record for this org, probably there is no template configured.');
            }
               
            //7. Delete the reporting structure record
            $sql = SQL_DELETE_ORGS_RPT_STRUCTURE;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Deleting reporting strucutre record - Query 6 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->debug('Not able to delete reporting strucutre record for this org, probably there is no reporting strucutre configured.');
            }
            
            //8. Delete the reporting query filter record
            $sql = SQL_DELETE_ORGS_RPQFILTERS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Deleting reporting query filter record - Query 7 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->debug('Not able to delete reporting query filter record for this org, probably there is no reporting query filter configured.');
            }
            
            //9. Drop Products table strcutre
            $sql = SQL_DROP_TABLE_ORG_PRDS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Dropping table structure for products - Query 8 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to drop the table structure for Products, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrg_MSG_6', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            //9. Drop Tasks table strcutre
            $sql = SQL_DROP_TABLE_ORG_TSKS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Dropping table structure for Tasks - Query 8 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to drop the table structure for Tasks, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrg_MSG_7', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            //10. Drop Workers table strcutre
            $sql = SQL_DROP_TABLE_ORG_WRKS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Dropping table structure for Workers - Query 9 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to drop the table structure for Workers, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrg_MSG_8', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            //11. Drop Customers table strcutre
            $sql = SQL_DROP_TABLE_ORG_CSTMRS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Dropping table structure for Customers - Query 9 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to drop the table structure for Customers, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrg_MSG_9', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            //12. Drop Reporting table strcutre
            $sql = SQL_DROP_TABLE_ORG_RPTS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Dropping table structure for Reporting - Query 10 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to drop the table structure for Reporting, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrg_MSG_10', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            //13. Delete the records from Orgs
            $sql = SQL_DELETE_ORGS;
            $sql = str_replace('P1', $orgId, $sql);
            
            $this->logger->debug('Deleting Organization details - Query 5 : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to delete the Organization details from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrg_MSG_11', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            
            $this->logger->debug('Organization is deleted from the system successfully.');
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            $this->setCompleteMsg(getLocaleText('OrgDao_deleteOrg_MSG_12', TXT_A));

            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_deleteOrg_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadRptTemplates(){
        
        $flag = FALSE;
        
        try{
            
            $templatesDir = 'rptTemplates/pdfs';
            $file = '';
            if ($handle = opendir($templatesDir)){
                $templates = array();
                while (false !== ($entry = readdir($handle))) {
                    if (strpos($entry, 'pdf') !== FALSE) {
                        $file = $entry;
                        $templateId = substr($file, 0, strpos($file, '.'));
                        $templateName = $this->templatesMap[$templateId];
                        $record = array(
                                        'templateId' => substr($file, 0, strpos($file, '.')),
                                        'templateName' => $templateName,
                                        'path' => $templatesDir.'/'.$file
                                        );
                        
                        array_push($templates, $record);
                    }
                }
                closedir($handle);
                
                $this->setTemplates($templates);
                
                $flag = TRUE;
            }
            else{
                $this->logger->debug('No templates found in the system. Please report this error to the administrator of the application. ');
                array_push($this->errors, getLocaleText('OrgDao_loadRptTemplates_MSG_1', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_loadRptTemplates_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function loadRptQFilters(){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_SELECT_RPT_QFILTERS;
            
            $result = $qMan->query($sql);
            $this->logger->debug('Loading Report Query Filters  - Query : ' . $sql);
            
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
                    $orgRptQFilter->setCreatedOn($row['created_on']);
                    $orgRptQFilter->setLastUpdated($row['last_updated']);
                    
                    $orgRptQFilter->setQfilterName(setLocaleDynaText($row['qfilter_name_en'], $row['qfilter_name_es']));
                    $orgRptQFilter->setQfilterDesc(setLocaleDynaText($row['qfilter_desc_en'], $row['qfilter_desc_es']));
                    
                    array_push($rptQFilters, $orgRptQFilter);
                }
                
                $this->setRptQFiltersList($rptQFilters);
            }
            else{
                array_push($this->errors, getLocaleText('OrgDao_loadRptQFilters_MSG_1', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_loadRptQFilters_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function saveOrgTemplate(OrgInputs $orgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $orgInputs->getIdorgs();
            
            if ($mode == ORG_TMPL_LIST_MODE_NEW){
                $sql = SQL_INSERT_ORGS_TEMPLATES;
                
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', '\''.$orgInputs->getIn_templateName().'\'', $sql);
                $sql = str_replace('P3', '\''.$orgInputs->getIn_templateId().'\'', $sql);
                $sql = str_replace('P4', 'NOW()', $sql);

                $this->logger->debug('Saving Template record for this organization - Query : ' . $sql);
            
                $orgTmplId = $qMan->queryInsertAndGetId($sql);

                if (!$orgTmplId){
                    $this->logger->error('Failed to insert the template details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('OrgDao_saveOrgTemplate_MSG_1', TXT_A));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }

                $this->logger->debug('Template Id generated for new record : ' . $orgTmplId);
                
                $this->logger->debug('All details of new template for this organization are saved successfully. New template is created.');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('OrgDao_saveOrgTemplate_MSG_2', TXT_A));
                
            }
            else if ($mode == ORG_TMPL_LIST_MODE_EDIT){
                $sql = SQL_UPDATE_ORGS_TEMPLATES;
                
                $sql = str_replace('P1', '\''.$orgInputs->getIn_templateName().'\'', $sql);
                $sql = str_replace('P2', '\''.$orgInputs->getIn_templateId().'\'', $sql);
                $sql = str_replace('P3', 'NOW()', $sql);
                $sql = str_replace('P4', $orgId, $sql);
                
                $this->logger->debug('Updating Template record for this organization - Query : ' . $sql);
                
                $update = $qMan->update($sql);
                
                if (!$update){
                    $this->logger->error('Failed to update the template details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('OrgDao_saveOrgTemplate_MSG_3', TXT_A));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
                
                $this->logger->debug('New details of template are updated successfully.');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('OrgDao_saveOrgTemplate_MSG_4', TXT_A));

            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, getLocaleText('OrgDao_saveOrgTemplate_MSG_5', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_saveOrgTemplate_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function saveOrgRptStructure(OrgInputs $orgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $orgInputs->getIdorgs();
            
            if ($mode == ORG_RPT_STRUCT_LIST_MODE_NEW){
                $sql = SQL_INSERT_ORGS_RPT_STRUCTURE;
                
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', '\''.$orgInputs->getPrdDynaCBIds().'\'', $sql);
                $sql = str_replace('P3', '\''.$orgInputs->getTskDynaCBIds().'\'', $sql);
                $sql = str_replace('P4', '\''.$orgInputs->getWrkDynaCBIds().'\'', $sql);
                $sql = str_replace('P5', '\''.$orgInputs->getCstDynaCBIds().'\'', $sql);
                $sql = str_replace('P6', '\''.$orgInputs->getRptDynaCBIds().'\'', $sql);
                $sql = str_replace('P7', 'NOW()', $sql);

                $this->logger->debug('Saving Reporting Structure record for this organization - Query : ' . $sql);
            
                $orgRptStructId = $qMan->queryInsertAndGetId($sql);

                if (!$orgRptStructId){
                    $this->logger->error('Failed to insert the Reporting Structure details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('OrgDao_saveOrgRptStructure_MSG_1', TXT_A));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }

                $this->logger->debug('Reporting Structure Id generated for new record : ' . $orgRptStructId);
                
                $this->logger->debug('All details of new Reporting Structure for this organization are saved successfully. New template is created.');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('OrgDao_saveOrgRptStructure_MSG_2', TXT_A));
                
            }
            else if ($mode == ORG_RPT_STRUCT_LIST_MODE_EDIT){
                $sql = SQL_UPDATE_ORGS_RPT_STRUCTURE;
                
                $sql = str_replace('P1', '\''.$orgInputs->getPrdDynaCBIds().'\'', $sql);
                $sql = str_replace('P2', '\''.$orgInputs->getTskDynaCBIds().'\'', $sql);
                $sql = str_replace('P3', '\''.$orgInputs->getWrkDynaCBIds().'\'', $sql);
                $sql = str_replace('P4', '\''.$orgInputs->getCstDynaCBIds().'\'', $sql);
                $sql = str_replace('P5', '\''.$orgInputs->getRptDynaCBIds().'\'', $sql);
                $sql = str_replace('P6', 'NOW()', $sql);
                $sql = str_replace('P7', $orgId, $sql);
                
                $this->logger->debug('Updating Reporting Structure record for this organization - Query : ' . $sql);
                
                $update = $qMan->update($sql);
                
                if (!$update){
                    $this->logger->error('Failed to update the Reporting Structure details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('OrgDao_saveOrgRptStructure_MSG_3', TXT_A));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
                
                $this->logger->debug('New details of Reporting Structure are updated successfully.');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('OrgDao_saveOrgRptStructure_MSG_4', TXT_A));

            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, 'Invalid Mode.');
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_saveOrgRptStructure_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function saveOrgRptQFilter(OrgInputs $orgInputs){
        
        $flag = FALSE;
        $recordExists = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $orgInputs->getIdorgs();
            
            $sql = SQL_SELECT_ORGS_RPT_QFILTERS;
            $sql = str_replace('P1', $orgId, $sql);

            $this->logger->debug('Checking if the record already exists for Report filter for this Org - Query 1 : ' . $sql);

            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                $this->logger->debug('Record already exists, existing record will be updated with new details.');
                $recordExists = TRUE;
            }
            else{
                $this->logger->debug('No record exists, new Org report Query filter record will be created.');
            }
            
            if (!$recordExists){
                $sql = SQL_INSERT_ORGS_RPT_QFILTERS;
                $sql = str_replace('P1', $orgId, $sql);
                $sql = str_replace('P2', '\''.$orgInputs->getQFilters().'\'', $sql);
                $sql = str_replace('P3', 'NOW()', $sql);
                
                $this->logger->debug('Saving Reporting Query Filters record for this organization - Query 2 : ' . $sql);
                
                $orgRotQFilterId = $qMan->queryInsertAndGetId($sql);

                if (!$orgRotQFilterId){
                    $this->logger->error('Failed to insert the Reporting Query Filters in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('OrgDao_saveOrgRptQFilter_MSG_1', TXT_A));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
                
                $this->logger->debug('Reporting Query Filter Id generated for new record : ' . $orgRotQFilterId);
                
                $this->logger->debug('All details of new Reporting Query Filters for this organization are saved successfully.');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('OrgDao_saveOrgRptQFilter_MSG_2', TXT_A));
                
            }
            else{
                $sql = SQL_UPDATE_ORGS_RPQFILTERS;
                $sql = str_replace('P1', '\''.$orgInputs->getQFilters().'\'', $sql);
                $sql = str_replace('P2', 'NOW()', $sql);
                $sql = str_replace('P3', $orgId, $sql);
                
                $this->logger->debug('Updating Reporting Query Filters record for this organization - Query 2 : ' . $sql);
                
                $update = $qMan->update($sql);
                
                if (!$update){
                    $this->logger->error('Failed to update the Reporting Query Filters details in the system, some internal error occured.');
                    array_push($this->errors, getLocaleText('OrgDao_saveOrgRptQFilter_MSG_3', TXT_A));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
                
                $this->logger->debug('New details of Reporting Query Filters are updated successfully.');
                $flag = TRUE;
                $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
                $this->setCompleteMsg(getLocaleText('OrgDao_saveOrgRptQFilter_MSG_4', TXT_A));
                
            }
        
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_saveOrgRptQFilter_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function fetchOrgTemplate(OrgInputs $orgInputs, $mode){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $orgId = $orgInputs->getIdorgs();
            $orgRptTemplate = new OrgRptTemplate();
            
            if ($mode == ORG_TMPL_LIST_MODE_NEW){
                $orgRptTemplate->setIdorgs($orgId);
                $orgRptTemplate->setTemplateName('');
                $orgRptTemplate->setRawTemplateId('');
                
                $this->setOrgTemplateDetails($orgRptTemplate);
            }
            else if ($mode == ORG_TMPL_LIST_MODE_EDIT){
                $sql = SQL_SELECT_ORGS_TEMPLATE_DETAILS;
                $sql = str_replace('P1', $orgId, $sql);

                $this->logger->debug('Loading Org Template Details - Query : ' . $sql);

                $result = $qMan->query($sql);
                if (isset($result) && mysql_num_rows($result) > 0){
                    $this->logger->debug('Loading details for Org Template details : '. $orgId);

                    $row = $qMan->fetchSingleRow($result);

                    $orgRptTemplate->setIdorgs($row['idorgs']);
                    $orgRptTemplate->setTemplateName($row['template_name']);
                    $orgRptTemplate->setRawTemplateId($row['rawtemplate_id']);
                    
                    $this->setOrgTemplateDetails($orgRptTemplate);
                }
                else{
                    array_push($this->errors, getLocaleText('OrgDao_fetchOrgTemplate_MSG_1', TXT_A));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, getLocaleText('OrgDao_fetchOrgTemplate_MSG_2', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_fetchOrgTemplate_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function fetchRptStruct(OrgInputs $orgInputs, $mode){
        
        $flag = FALSE;
        $orgRptStructsFields = array();
        
        try{
            $orgId = $orgInputs->getIdorgs();
            
            if ($mode == ORG_RPT_STRUCT_LIST_MODE_NEW){
                $this->setOrgRptStructsFields($orgRptStructsFields);
            }
            else if ($mode == ORG_RPT_STRUCT_LIST_MODE_EDIT){
                $qMan = parent::getQueryManager();
            
                $sql = SQL_SELECT_ORGS_RPT_STRUCTURE_DETAILS;
                $sql = str_replace('P1', $orgId, $sql);
                
                $this->logger->debug('Loading Org Report Structure Details - Query : ' . $sql);

                $result = $qMan->query($sql);
                if (isset($result) && mysql_num_rows($result) > 0){
                    
                    $row = $qMan->fetchSingleRow($result);
                    
                    $prdDynaFields = $row['prds_dyna_fields'];
                    $tsksDynaFields = $row['tsks_dyna_fields'];
                    $wrksDynaFields = $row['wrks_dyna_fields'];
                    $cstmrsDynaFields = $row['cstmrs_dyna_fields'];
                    $rptsDynaFields = $row['rpts_dyna_fields'];
                    
                    $prdDynaFieldsArr = explode('|', $prdDynaFields);
                    $tsksDynaFieldsArr = explode('|', $tsksDynaFields);
                    $wrksDynaFieldsArr = explode('|', $wrksDynaFields);
                    $cstmrsDynaFieldsArr = explode('|', $cstmrsDynaFields);
                    $rptsDynaFieldsArr = explode('|', $rptsDynaFields);
                    
                    foreach  ($prdDynaFieldsArr as $prdDynaField){
                        $orgRptStructsFields[DYNAFIELDS_PRODUCT_CBID_PREFIX.$prdDynaField] = $prdDynaField;
                    }
                    
                    foreach  ($tsksDynaFieldsArr as $tsksDynaField){
                        $orgRptStructsFields[DYNAFIELDS_TASK_CBID_PREFIX.$tsksDynaField] = $tsksDynaField;
                    }
                    
                    foreach  ($wrksDynaFieldsArr as $wrksDynaField){
                        $orgRptStructsFields[DYNAFIELDS_WORKER_CBID_PREFIX.$wrksDynaField] = $wrksDynaField;
                    }
                    
                    foreach  ($cstmrsDynaFieldsArr as $cstmrsDynaField){
                        $orgRptStructsFields[DYNAFIELDS_CUSTOMER_CBID_PREFIX.$cstmrsDynaField] = $cstmrsDynaField;
                    }
                    
                    foreach  ($rptsDynaFieldsArr as $rptsDynaField){
                        $orgRptStructsFields[DYNAFIELDS_REPORTING_CBID_PREFIX.$rptsDynaField] = $rptsDynaField;
                    }
                    
                    $this->setOrgRptStructsFields($orgRptStructsFields);

                }
                else{
                    $this->logger->debug('Reporting Structure not found for this org in the records, Please report this error to the administrator of the application. ');
                    array_push($this->errors, getLocaleText('OrgDao_fetchRptStruct_MSG_1', TXT_A));
                    $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                    return $flag;
                }
            }
            else{
                $this->logger->error('Invalid Mode.');
                array_push($this->errors, getLocaleText('OrgDao_fetchRptStruct_MSG_2', TXT_A));
                $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
                return $flag;
            }
            
            $flag = TRUE;
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_fetchRptStruct_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function deleteOrgTemplaate(OrgInputs $orgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_DELETE_ORGS_TEMPLATES;
            
            $sql = str_replace('P1', $orgInputs->getIdorgs(), $sql);
            
            $this->logger->debug('Deleting Organization template details - Query : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to delete the Organization template details from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrgTemplaate_MSG_1', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            $this->logger->debug('Organization template details are deleted from the system successfully.');
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            $this->setCompleteMsg(getLocaleText('OrgDao_deleteOrgTemplaate_MSG_2', TXT_A));
            
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_deleteOrgTemplaate_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function deleteOrgRptStruct(OrgInputs $orgInputs){
        
        $flag = FALSE;
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_DELETE_ORGS_RPT_STRUCTURE;
            
            $sql = str_replace('P1', $orgInputs->getIdorgs(), $sql);
            
            $this->logger->debug('Deleting Organization reporting structure details - Query : ' . $sql);
            
            $delete = $qMan->query($sql);
            if (!$delete){
               $this->logger->error('Failed to delete the Organization reporting structure details from the system, some internal error occured.');
               array_push($this->errors, getLocaleText('OrgDao_deleteOrgRptStruct_MSG_1', TXT_A));
               $this->setMsgType(SUBMISSION_MSG_TYPE_ERROR);
               return $flag;
            }
            
            $this->logger->debug('Organization reporting structure details are deleted from the system successfully.');
            $flag = TRUE;
            $this->setMsgType(SUBMISSION_MSG_TYPE_COMPLETESUCCESS);
            $this->setCompleteMsg(getLocaleText('OrgDao_deleteOrgRptStruct_MSG_2', TXT_A));
        }
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_deleteOrgRptStruct_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    function checkOrgExist($orgname){
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_SELECT_ORGS_NAMECHECK;
            $sql = str_replace('P1', '\''.$orgname.'\'', $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                return TRUE;
            }
            else{
                return FALSE;
            }
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('OrgDao_checkOrgExist_MSG_EX', TXT_A));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
    }
    
    function saveXEditDynaValues(XEditDynaValueInputs $xEditDynaInputs){
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_INSERT_XEDIT_DYNA_VALUES;
            $sql = str_replace('P1', '\''.$xEditDynaInputs->getDynaType().'\'', $sql);
            $sql = str_replace('P2', '\''.$xEditDynaInputs->getDynaId().'\'', $sql);
            $sql = str_replace('P3', '\''.$xEditDynaInputs->getValues().'\'', $sql);
            
            //$this->logger->debug('Saving xEdit values : ' . $sql);
            
            $id = $qMan->queryInsertAndGetId($sql);
            
            if (!$id){
                $this->logger->error('Failed to insert the xEdit Dyna record in the system, some internal error occured.');
            }
            $this->logger->debug('Id generated for xEdit Dyna record : ' . $id);
        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
        }
    }
    
    function fetchXEditDynaValues($dynaType){
        
        $xEditDynaValuesEn = '';
        $xEditDynaValuesEs = '';
        $xEditDynaValues = '';
        
        try{
            $qMan = parent::getQueryManager();
            
            $sql = SQL_SELECT_XEDIT_DYNA_VALUES;
            $sql = str_replace('P1', '\''.$dynaType.'\'', $sql);
            
            $this->logger->debug('Fetching xEdit values : ' . $sql);
            
            $result = $qMan->query($sql);
            if (isset($result) && mysql_num_rows($result) > 0){
                while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $dynaValues = $row['dyna_values'];
                    $dynaValuesArr = explode(XEDIT_VALUES_SPEERATOR_ON_SUBMIT, $dynaValues);
                    $consEnValues = '';
                    $consEsValues = '';
                    foreach ($dynaValuesArr as $dynaValue){
                        $dynaValueLocales = explode(DYNAFIELDS_LIST_VALUES_LOCALE_SEPERATOR, $dynaValue);
                        $consEnValues = $consEnValues . $dynaValueLocales[0] . XEDIT_VALUES_SPEERATOR_ON_SUBMIT;
                        $consEsValues = $consEsValues . $dynaValueLocales[1] . XEDIT_VALUES_SPEERATOR_ON_SUBMIT;
                    }
                    $consEnValues = substr($consEnValues, 0, strlen($consEnValues)-1);
                    $consEsValues = substr($consEsValues, 0, strlen($consEsValues)-1);
                    
                    
                    //$xEditDynaValues = $xEditDynaValues . $row['dyna_id'] . ':' . $row['dyna_values'] . '|';
                    $xEditDynaValuesEn = $xEditDynaValuesEn . $row['dyna_id'] . ':' . $consEnValues . '|';
                    $xEditDynaValuesEs = $xEditDynaValuesEs . $row['dyna_id'] . ':' . $consEsValues . '|';
                }
            }
            if (!empty($xEditDynaValuesEn)){
                $xEditDynaValuesEn = substr($xEditDynaValuesEn, 0, strlen($xEditDynaValuesEn)-1);
            }
            if (!empty($xEditDynaValuesEs)){
                $xEditDynaValuesEs = substr($xEditDynaValuesEs, 0, strlen($xEditDynaValuesEs)-1);
            }
            
            $xEditDynaValues = $xEditDynaValuesEn . XEDIT_VALUES_SPEERATOR_LOCALES . $xEditDynaValuesEs;

        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
        }
        $this->logger->debug($xEditDynaValues);
        return $xEditDynaValues;
        
    }
    
    function deleteXEditDynaValues($dynaType, $dynaId){
        $sql = '';
        
        try{
            $qMan = parent::getQueryManager();
            
            if ($dynaType === 'all'){
                $sql = SQL_DELETE_XEDIT_DYNA_VALUES;
            }
            else {
                $sql = SQL_DELETE_XEDIT_DYNA_VALUES_FILTERED;
                $sql = str_replace('P1', '\''.$dynaType.'\'', $sql);
                $sql = str_replace('P2', '\''.$dynaId.'\'', $sql);
            }
            
            //$this->logger->debug('Deleting xEdit values : ' . $sql);
            
            $delete = $qMan->query($sql);

        } 
        catch (Exception $ex) {
            $this->logger->error( '[Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
        }
        
    }
    
    function populateDynaFieldsObj($row, $id, $idPrefix){
        
        $dynaFields = new DynaFields();
        $dynaFields->setIdDynaFields($idPrefix.$row[$id]);
        $dynaFields->setNameEn($row['name_en']);
        $dynaFields->setNameEs($row['name_es']);
        $dynaFields->setDescriptionEn($row['description_en']);
        $dynaFields->setDescriptionEs($row['description_es']);
        $dynaFields->setHtmlName($row['html_name']);
        $dynaFields->setHtmlType(getLocaleText(DYNAFILE_CONTROLTYPE, TXT_A).$row['html_type']);
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
        
        $dynaFields->getHtmlValidationsMessages(setLocaleDynaText($row['html_validations_messages_en'], $row['html_validations_messages_es']));
        
        $htmlListValuesEnArr = explode(DYNAFIELDS_LIST_VALUES_SEPERATOR, $dynaFields->getHtmlListValuesEn());
        $htmlListValuesEsArr = explode(DYNAFIELDS_LIST_VALUES_SEPERATOR, $dynaFields->getHtmlListValuesEs());
        
        $htmlListValues = explode(DYNAFIELDS_LIST_VALUES_SEPERATOR, setLocaleDynaText($row['html_list_values_en'], $row['html_list_values_es']));
        $htmlListValuesArr = array();
        $ctr = 0;
        
        if (sizeof($htmlListValues) > 0){
            foreach ($htmlListValues as $htmlListValue){
            
                $enValue = DYNAFIELDS_LIST_VALUES_LOCALE_NA;
                $esValue = DYNAFIELDS_LIST_VALUES_LOCALE_NA;
                if (sizeof($htmlListValuesEnArr) > $ctr){
                    $enValue = $htmlListValuesEnArr[$ctr];
                }
                if (sizeof($htmlListValuesEsArr) > $ctr){
                    $esValue = $htmlListValuesEsArr[$ctr];
                }

                if (!empty($enValue) && !empty($esValue)) {
                    $htmlListValuesArr[$enValue . DYNAFIELDS_LIST_VALUES_LOCALE_SEPERATOR . $esValue] = $htmlListValue;
                }

                ++$ctr;
            }
        }
        
        $dynaFields->setHtmlListValues($htmlListValuesArr);
        
        return $dynaFields;
        
    }
    
    function delTree($dir) {
        
        $files = array_diff(scandir($dir), array('.', '..')); 

        foreach ($files as $file) { 
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file"); 
        }

        return rmdir($dir); 
    } 
    
    function reformDateFromDB($date, $format) {
        return date_format( DateTime::createFromFormat('Y-m-d H:i:s', $date), $format);
    }
    
    
    public function getErrors() {
        return $this->errors;
    }

    public function getMessages() {
        return $this->messages;
    }

    public function getCompleteMsg() {
        return $this->completeMsg;
    }

    public function getCriticalError() {
        return $this->criticalError;
    }

    public function setErrors($errors) {
        $this->errors = $errors;
    }

    public function setMessages($messages) {
        $this->messages = $messages;
    }

    public function setCompleteMsg($completeMsg) {
        $this->completeMsg = $completeMsg;
    }

    public function setCriticalError($criticalError) {
        $this->criticalError = $criticalError;
    }
    
    public function getMsgType() {
        return $this->msgType;
    }

    public function setMsgType($msgType) {
        $this->msgType = $msgType;
    }

    public function getSuccessMessage() {
        return $this->successMessage;
    }

    public function setSuccessMessage($successMessage) {
        $this->successMessage = $successMessage;
    }

    public function getProductDynaFields() {
        return $this->productDynaFields;
    }

    public function getTaskDynaFields() {
        return $this->taskDynaFields;
    }

    public function getWorkerDynaFields() {
        return $this->workerDynaFields;
    }

    public function getCustomerDynaFields() {
        return $this->customerDynaFields;
    }

    public function setProductDynaFields($productDynaFields) {
        $this->productDynaFields = $productDynaFields;
    }

    public function setTaskDynaFields($taskDynaFields) {
        $this->taskDynaFields = $taskDynaFields;
    }

    public function setWorkerDynaFields($workerDynaFields) {
        $this->workerDynaFields = $workerDynaFields;
    }

    public function setCustomerDynaFields($customerDynaFields) {
        $this->customerDynaFields = $customerDynaFields;
    }

    public function getLogger() {
        return $this->logger;
    }

    public function getOrgDetails() {
        return $this->orgDetails;
    }

    public function getOrgProductDynaFields() {
        return $this->orgProductDynaFields;
    }

    public function getOrgTaskDynaFields() {
        return $this->orgTaskDynaFields;
    }

    public function getOrgWorkerDynaField() {
        return $this->orgWorkerDynaField;
    }

    public function getOrgCustomerDynaFields() {
        return $this->orgCustomerDynaFields;
    }

    public function setLogger($logger) {
        $this->logger = $logger;
    }

    public function setOrgDetails($orgDetails) {
        $this->orgDetails = $orgDetails;
    }

    public function setOrgProductDynaFields($orgProductDynaFields) {
        $this->orgProductDynaFields = $orgProductDynaFields;
    }

    public function setOrgTaskDynaFields($orgTaskDynaFields) {
        $this->orgTaskDynaFields = $orgTaskDynaFields;
    }

    public function setOrgWorkerDynaField($orgWorkerDynaField) {
        $this->orgWorkerDynaField = $orgWorkerDynaField;
    }

    public function setOrgCustomerDynaFields($orgCustomerDynaFields) {
        $this->orgCustomerDynaFields = $orgCustomerDynaFields;
    }

    public function getIsListLoaded() {
        return $this->isListLoaded;
    }

    public function setIsListLoaded($isListLoaded) {
        $this->isListLoaded = $isListLoaded;
    }

    public function getListOrgs() {
        return $this->listOrgs;
    }

    public function setListOrgs($listOrgs) {
        $this->listOrgs = $listOrgs;
    }

    public function getTotalOrgs() {
        return $this->totalOrgs;
    }

    public function setTotalOrgs($totalOrgs) {
        $this->totalOrgs = $totalOrgs;
    }

    public function getTemplates() {
        return $this->templates;
    }

    public function setTemplates($templates) {
        $this->templates = $templates;
    }

    public function getOrgTemplateDetails() {
        return $this->orgTemplateDetails;
    }

    public function setOrgTemplateDetails($orgTemplateDetails) {
        $this->orgTemplateDetails = $orgTemplateDetails;
    }

    public function getReportingDynaFields() {
        return $this->reportingDynaFields;
    }

    public function setReportingDynaFields($reportingDynaFields) {
        $this->reportingDynaFields = $reportingDynaFields;
    }

    public function getOrgRptStructsFields() {
        return $this->orgRptStructsFields;
    }

    public function setOrgRptStructsFields($orgRptStructsFields) {
        $this->orgRptStructsFields = $orgRptStructsFields;
    }

    public function getRptQFiltersList() {
        return $this->rptQFiltersList;
    }

    public function setRptQFiltersList($rptQFiltersList) {
        $this->rptQFiltersList = $rptQFiltersList;
    }

    public function getLastLogin() {
        return $this->lastLogin;
    }

    public function setLastLogin($lastLogin) {
        $this->lastLogin = $lastLogin;
    }

    public function getRptTmpl_totalConfigs() {
        return $this->rptTmpl_totalConfigs;
    }

    public function getRptTmpl_totalNonConfigs() {
        return $this->rptTmpl_totalNonConfigs;
    }

    public function getRptQFilters_totalConfigs() {
        return $this->rptQFilters_totalConfigs;
    }

    public function getRptQFilters_totalNonConfigs() {
        return $this->rptQFilters_totalNonConfigs;
    }

    public function getRptStructs_totalConfigs() {
        return $this->rptStructs_totalConfigs;
    }

    public function getRptStructs_totalNonConfigs() {
        return $this->rptStructs_totalNonConfigs;
    }

    public function setRptTmpl_totalConfigs($rptTmpl_totalConfigs) {
        $this->rptTmpl_totalConfigs = $rptTmpl_totalConfigs;
    }

    public function setRptTmpl_totalNonConfigs($rptTmpl_totalNonConfigs) {
        $this->rptTmpl_totalNonConfigs = $rptTmpl_totalNonConfigs;
    }

    public function setRptQFilters_totalConfigs($rptQFilters_totalConfigs) {
        $this->rptQFilters_totalConfigs = $rptQFilters_totalConfigs;
    }

    public function setRptQFilters_totalNonConfigs($rptQFilters_totalNonConfigs) {
        $this->rptQFilters_totalNonConfigs = $rptQFilters_totalNonConfigs;
    }

    public function setRptStructs_totalConfigs($rptStructs_totalConfigs) {
        $this->rptStructs_totalConfigs = $rptStructs_totalConfigs;
    }

    public function setRptStructs_totalNonConfigs($rptStructs_totalNonConfigs) {
        $this->rptStructs_totalNonConfigs = $rptStructs_totalNonConfigs;
    }

    public function getGraphOrgsRptsCountDataSet() {
        return $this->graphOrgsRptsCountDataSet;
    }

    public function setGraphOrgsRptsCountDataSet($graphOrgsRptsCountDataSet) {
        $this->graphOrgsRptsCountDataSet = $graphOrgsRptsCountDataSet;
    }


    
}
