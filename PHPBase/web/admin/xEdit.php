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

include(dirname(__FILE__) . "/../../classes/inputs/XEditDynaValueInputs.php");
include(dirname(__FILE__) . "/../../classes/dao/OrgDao.php");
require_once(dirname(__FILE__) . "/../../classes/base/Constants.php");


$xEditDynaInputs = new XEditDynaValueInputs();
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


$dynaType = '';
$idx = 0;
if (strpos($xCtlName, DYNAFIELDS_PRODUCT_CBID_PREFIX)){
    $dynaType = DYNAFIELDS_TYPE_PRODUCT;
    $idx = strlen(DYNAFIELDS_XEDIT_PREFIX) + strlen(DYNAFIELDS_PRODUCT_CBID_PREFIX);
}
else if (strpos($xCtlName, DYNAFIELDS_TASK_CBID_PREFIX)){
    $dynaType = DYNAFIELDS_TYPE_TASKS;
    $idx = strlen(DYNAFIELDS_XEDIT_PREFIX) + strlen(DYNAFIELDS_TASK_CBID_PREFIX);
}
else if (strpos($xCtlName, DYNAFIELDS_WORKER_CBID_PREFIX)){
    $dynaType = DYNAFIELDS_TYPE_WORKERS;
    $idx = strlen(DYNAFIELDS_XEDIT_PREFIX) + strlen(DYNAFIELDS_WORKER_CBID_PREFIX);
}
else if (strpos($xCtlName, DYNAFIELDS_CUSTOMER_CBID_PREFIX)){
    $dynaType = DYNAFIELDS_TYPE_CUSTOMERS;
    $idx = strlen(DYNAFIELDS_XEDIT_PREFIX) + strlen(DYNAFIELDS_CUSTOMER_CBID_PREFIX);
}

$dynaId = substr($xCtlName, $idx);

//Remove previous values based on dyna id, using dyna id will allow to have multile combo boxes on same page
$orgDao->deleteXEditDynaValues($dynaType, $dynaId);

//Save new values
$xEditDynaInputs->setDynaId($dynaId);
$xEditDynaInputs->setDynaType($dynaType);
$xEditDynaInputs->setValues($consValues);

if (!empty($consValues) && $consValues !== 'none' ){
    $orgDao->saveXEditDynaValues($xEditDynaInputs);
}

?>