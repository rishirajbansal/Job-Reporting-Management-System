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

/**
 * Description of ExportData
 *
 * @author Rishi Raj
 */
class ExportData {
    
    private $searchCriteria;
    private $qFilterData;
    private $totalHrs;
    
    
    public function getSearchCriteria() {
        return $this->searchCriteria;
    }

    public function getQFilterData() {
        return $this->qFilterData;
    }

    public function getTotalHrs() {
        return $this->totalHrs;
    }

    public function setSearchCriteria($searchCriteria) {
        $this->searchCriteria = $searchCriteria;
    }

    public function setQFilterData($qFilterData) {
        $this->qFilterData = $qFilterData;
    }

    public function setTotalHrs($totalHrs) {
        $this->totalHrs = $totalHrs;
    }


    
    
}
