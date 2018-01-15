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
 * Super class for Dyna Classes
 *
 * @author Rishi Raj
 */
class OrgDynaSuper {
    
    private $idorgs;
    private $dynaFieldIds;
    private $dyna_fields_list_values_en;
    private $dyna_fields_list_values_es;
    
    private $dynaFieldsProcessedDetails;
    
    private $dynaFieldIdsList;
    
    private $createdOn;
    private $lastUpdated;
    
    
    public function getIdorgs() {
        return $this->idorgs;
    }

    public function getDynaFieldIds() {
        return $this->dynaFieldIds;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function getLastUpdated() {
        return $this->lastUpdated;
    }

    public function setIdorgs($idorgs) {
        $this->idorgs = $idorgs;
    }

    public function setDynaFieldIds($dynaFieldIds) {
        $this->dynaFieldIds = $dynaFieldIds;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    public function setLastUpdated($lastUpdated) {
        $this->lastUpdated = $lastUpdated;
    }

    public function getDyna_fields_list_values_en() {
        return $this->dyna_fields_list_values_en;
    }

    public function getDyna_fields_list_values_es() {
        return $this->dyna_fields_list_values_es;
    }

    public function setDyna_fields_list_values_en($dyna_fields_list_values_en) {
        $this->dyna_fields_list_values_en = $dyna_fields_list_values_en;
    }

    public function setDyna_fields_list_values_es($dyna_fields_list_values_es) {
        $this->dyna_fields_list_values_es = $dyna_fields_list_values_es;
    }

    public function getDynaFieldsProcessedDetails() {
        return $this->dynaFieldsProcessedDetails;
    }

    public function setDynaFieldsProcessedDetails($dynaFieldsProcessedDetails) {
        $this->dynaFieldsProcessedDetails = $dynaFieldsProcessedDetails;
    }
    
    public function getDynaFieldIdsList() {
        return $this->dynaFieldIdsList;
    }

    public function setDynaFieldIdsList($dynaFieldIdsList) {
        $this->dynaFieldIdsList = $dynaFieldIdsList;
    }



    
}
