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

require_once(dirname(__FILE__) . "/../../classes/business/ExportEngine.php");

include_once("sessionMgmt.php");


$exportEngine = new ExportEngine();

if (isset($_GET['qf']) && $_GET['qf'] == 'tcw'){
    
    $exportData = $_SESSION['exportData_tcw'];
    
    $exportEngine->generateTCWReport($exportData);
    
}
else if (isset($_GET['qf']) && $_GET['qf'] == 'ttlPrdQty'){

    $exportData = $_SESSION['exportData_ttlPrdQty'];
    
    $exportEngine->generateTtlPrdQtyReport($exportData);
    
}
else if (isset($_GET['qf']) && $_GET['qf'] == 'calWHrs'){
    
    $exportData = $_SESSION['exportData_CalWHrs'];
    
    $exportEngine->generateCalWHrsReport($exportData);
    
}



?>