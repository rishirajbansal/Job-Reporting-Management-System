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
 * Details of Organization Reporting Template
 *
 * @author Rishi Raj
 */
class OrgRptTemplate {
    
    private $idOrgsRptTemplate;
    private $idorgs;
    private $templateName;
    private $rawTemplateId;
    
    private $createdOn;
    private $lastUpdated;
    
    
    public function getIdOrgsRptTemplate() {
        return $this->idOrgsRptTemplate;
    }

    public function getIdorgs() {
        return $this->idorgs;
    }

    public function getTemplateName() {
        return $this->templateName;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function getLastUpdated() {
        return $this->lastUpdated;
    }

    public function setIdOrgsRptTemplate($idOrgsRptTemplate) {
        $this->idOrgsRptTemplate = $idOrgsRptTemplate;
    }

    public function setIdorgs($idorgs) {
        $this->idorgs = $idorgs;
    }

    public function setTemplateName($templateName) {
        $this->templateName = $templateName;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    public function setLastUpdated($lastUpdated) {
        $this->lastUpdated = $lastUpdated;
    }

    public function getRawTemplateId() {
        return $this->rawTemplateId;
    }

    public function setRawTemplateId($rawTemplateId) {
        $this->rawTemplateId = $rawTemplateId;
    }


    
}
