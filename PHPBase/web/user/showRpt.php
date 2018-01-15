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

require_once(dirname(__FILE__) . "/../../classes/base/Constants.php");


$orgId = '';
$rptId = '';
$mode = '';

if (isset($_GET['orgId'])){
    $orgId = $_GET['orgId'];
}
if (isset($_GET['rptId'])){
    $rptId = $_GET['rptId'];
}
if (isset($_GET['mode'])){
    $mode = $_GET['mode'];
}

if (empty($orgId) || empty($rptId)){
    echo getLocaleText('SHOW_RPT_MSG_3', TXT_U);
    return;
}

$orgDir = dirname(__FILE__) . '/../admin/orgdata/' . 'org_' . $orgId;
$reportsDir = $orgDir . '/reports';
$reportsDir = $reportsDir . '/' . REPORT_NAMING_FOLDERNAME_PREFIX . $rptId . '/';
$documentFile = $reportsDir . REPORT_NAMING_PREFIX . $rptId . '.pdf';

if (file_exists($documentFile)){
    $filename = REPORT_NAMING_PREFIX . $rptId . '.pdf';

    header('Content-type: application/pdf');
    if ($mode == ORG_USERRPT_LIST_REPORT_EXPORT){
       header("Content-Disposition: attachment;filename=\"" . $filename . '"');
    }
    else{
        header('Content-Disposition: inline; filename="' . $filename . '"');
    }
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($documentFile));
    header('Accept-Ranges: bytes');

    @readfile($documentFile);
}
else{
    if ($mode == ORG_USERRPT_LIST_REPORT_EXPORT){
        echo '<br/><br/>' . getLocaleText('SHOW_RPT_MSG_1', TXT_U);
    }
    else{
        echo '<br/><br/>' . getLocaleText('SHOW_RPT_MSG_2', TXT_U);
    }
}

?>