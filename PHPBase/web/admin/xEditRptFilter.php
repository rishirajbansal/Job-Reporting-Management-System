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

include(dirname(__FILE__) . "/../../classes/inputs/OrgInputs.php");
include(dirname(__FILE__) . "/../../classes/dao/OrgDao.php");
require_once(dirname(__FILE__) . "/../../classes/base/Constants.php");


$orgInputs = new OrgInputs();
$orgDao = new OrgDao();

$xCtlName = $_POST['name'];
$xCtlValues = '';
if (isset($_POST['value'])){
    $xCtlValues = $_POST['value'];
}

$consValues = '';

if (!empty($xCtlValues)){
    foreach($xCtlValues as $value){
        $consValues = $consValues . $value . XEDIT_VALUES_SPEERATOR_ON_SUBMIT;
    }
    $consValues = substr($consValues, 0, strlen($consValues)-1);
}

$orgId = substr($xCtlName, strlen(DYNAFIELDS_XEDIT_PREFIX));

$orgInputs->setIdorgs($orgId);
$orgInputs->setQFilters($consValues);

$saveFlag = $orgDao->saveOrgRptQFilter($orgInputs);
if ($saveFlag){
    $message = '';
    
    if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_SUCCESS){
        $errMsgObject['msg'] = 'success';
        $errMsgObject['text'] = $orgDao->getSuccessMessage();
        foreach($errMsgObject['text'] as $text){
            $message .= $text;
        }
    }
    else if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_COMPLETESUCCESS){
        $errMsgObject['msg'] = 'completeSuccess';
        $errMsgObject['text'] = $orgDao->getCompleteMsg();
        $message = $errMsgObject['text'];
    }
    
    header('HTTP/1.0 200 Success', true, 200);
    echo $message;
    
}
else{
    $error = '';
    
    if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_ERROR){
        $errMsgObject['msg'] = 'error';
        $errMsgObject['text'] = $orgDao->getErrors();
        foreach($errMsgObject['text'] as $text){
            $error .= $text;
        }
    }
    else if ($orgDao->getMsgType() == SUBMISSION_MSG_TYPE_CRITICALERROR){
        $errMsgObject['msg'] = 'criticalError';
        $error = $orgDao->getCriticalError();
    }
    
    header('HTTP/1.0 400 Bad Request', true, 400);
    echo $error;
}


?>