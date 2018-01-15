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

$logger = Logger::getRootLogger();

$logger->debug('[Call From Java Server >> Remote File Upload] Request received from Java application server for image uploading on file server');

$ds = DIRECTORY_SEPARATOR;

$orgId = '';
$rptId = '';
$type = '';

if (isset($_GET['orgId'])){
    $orgId = $_GET['orgId'];
}
if (isset($_GET['rptId'])){
    $rptId = $_GET['rptId'];
}
if (isset($_GET['type'])){
    $type = $_GET['type'];
}


$orgDir = '../admin/orgdata/' . 'org_' . $orgId;
$reportsDir = $orgDir . '/reports';
$thisReportDir = $reportsDir . '/' . REPORT_NAMING_FOLDERNAME_PREFIX . $rptId;

$logger->debug('[Call From Java Server >> Remote File Upload] Report Dir Path : ' . $thisReportDir);

if (!file_exists($thisReportDir)) {
    mkdir($thisReportDir, 0777, true);
}

if (REPORT_DATA_IMAGE_TYPE_PHOTO == $type){
    $logger->debug('[Call From Java Server >> Remote File Upload] Request for Photo Upload');
}
else if (REPORT_DATA_IMAGE_TYPE_SIGN == $type){
    $logger->debug('[Call From Java Server >> Remote File Upload] Request for Signature Upload');
}



if (!empty($_FILES)) {
    
    $tempFile = $_FILES[REPORT_DATA_IMAGE_URI_NAME]['tmp_name'];
    
    $targetPath = dirname( __FILE__ ) . $ds. $thisReportDir . $ds;
    
    $targetFile =  $targetPath. $_FILES[REPORT_DATA_IMAGE_URI_NAME]['name'];
    
    move_uploaded_file($tempFile,$targetFile);
    
    $logger->debug('[Call From Java Server >> Remote File Upload] Image uploaded successfully.');
    
    header('HTTP/1.0 200 Success', true, 200);
    
    if (REPORT_DATA_IMAGE_TYPE_PHOTO == $type){
        $callerReturnPath = $thisReportDir . '/' . $_FILES[REPORT_DATA_IMAGE_URI_NAME]['name'];
        //echo 'Photo Image Uploaded successfully';
        echo $callerReturnPath;
    }
    else if (REPORT_DATA_IMAGE_TYPE_SIGN == $type){
        //echo 'Signature Image Uploaded successfully';
        $callerReturnPath = $thisReportDir . '/' . $_FILES[REPORT_DATA_IMAGE_URI_NAME]['name'];
        echo $callerReturnPath;
    }
    
    
    
}


?>