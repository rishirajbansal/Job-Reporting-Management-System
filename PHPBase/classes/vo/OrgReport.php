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
 * Description of OrgRptStructure
 *
 * @author Rishi Raj
 */
class OrgReport {
    
    private $idOrgRpts;
    private $idorgs; 
    private $rptNo;
    private $subDatetime;
    private $subBy;
    private $data;
    private $clientname;
    private $coordinates;
    private $location;
    private $updateHistory;
    
    private $createdOn;
    private $lastUpdated;
    
    
    
    public function getIdOrgRpts() {
        return $this->idOrgRpts;
    }

    public function getIdorgs() {
        return $this->idorgs;
    }

    public function getRptNo() {
        return $this->rptNo;
    }

    public function getSubDatetime() {
        return $this->subDatetime;
    }

    public function getSubBy() {
        return $this->subBy;
    }

    public function getData() {
        return $this->data;
    }

    public function getClientname() {
        return $this->clientname;
    }

    public function getCoordinates() {
        return $this->coordinates;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getUpdateHistory() {
        return $this->updateHistory;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function getLastUpdated() {
        return $this->lastUpdated;
    }

    public function setIdOrgRpts($idOrgRpts) {
        $this->idOrgRpts = $idOrgRpts;
    }

    public function setIdorgs($idorgs) {
        $this->idorgs = $idorgs;
    }

    public function setRptNo($rptNo) {
        $this->rptNo = $rptNo;
    }

    public function setSubDatetime($subDatetime) {
        $this->subDatetime = $subDatetime;
    }

    public function setSubBy($subBy) {
        $this->subBy = $subBy;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setClientname($clientname) {
        $this->clientname = $clientname;
    }

    public function setCoordinates($coordinates) {
        $this->coordinates = $coordinates;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function setUpdateHistory($updateHistory) {
        $this->updateHistory = $updateHistory;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    public function setLastUpdated($lastUpdated) {
        $this->lastUpdated = $lastUpdated;
    }


}
