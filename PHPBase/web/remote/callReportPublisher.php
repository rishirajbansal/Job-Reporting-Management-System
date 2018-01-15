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

require_once(dirname(__FILE__) . "/../../classes/common.php");
require_once(dirname(__FILE__) . "/../../classes/business/ReportPublishingEngine.php");

$logger = Logger::getRootLogger();

$logger->debug('[Call From Java Server >> Remote Publishing] Request received from Java application server for report publishing');

$orgId = '';
$rptId = '';

if (isset($_GET['orgId'])){
    $orgId = $_GET['orgId'];
}
if (isset($_GET['rptId'])){
    $rptId = $_GET['rptId'];
}

if (empty($orgId) || empty($rptId)){
    header('HTTP/1.0 400 Bad Request', true, 400);
    echo 'OrgId or Report Id is missing in the request.';
}
else{
    $reportPublishing = new ReportPublishingEngine();
    
    $publishFlag = $reportPublishing->handlePublishing($orgId, $rptId, null);
    
    if ($publishFlag){
        header('HTTP/1.0 200 Success', true, 200);
        echo 'Report Published and dispatched successfully.';
    }
    else{
        header('HTTP/1.0 202 Bad Request', true, 202);
        echo $reportPublishing->getReturnCallerMsg();
    }


}

?>