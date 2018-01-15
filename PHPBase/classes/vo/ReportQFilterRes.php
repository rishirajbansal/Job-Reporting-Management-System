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
 * Description of ReportQFilterRes
 *
 * @author Rishi Raj
 */
class ReportQFilterRes {
    
    private $rptNo;
    private $idorgRpts;
    private $clientName;
    private $workerName;
    private $rptSubmitDate;
    private $location;
    private $data;
    private $latitude;
    private $longitude;
    private $mapMarkerFile;
    
    private $idorgs;
    
    private $totalHrs;
    private $prdQtyList;
    private $serviceHrs;
    
    
    public function getRptNo() {
        return $this->rptNo;
    }

    public function getClientName() {
        return $this->clientName;
    }

    public function getWorkerName() {
        return $this->workerName;
    }

    public function getRptSubmitDate() {
        return $this->rptSubmitDate;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setRptNo($rptNo) {
        $this->rptNo = $rptNo;
    }

    public function setClientName($clientName) {
        $this->clientName = $clientName;
    }

    public function setWorkerName($workerName) {
        $this->workerName = $workerName;
    }

    public function setRptSubmitDate($rptSubmitDate) {
        $this->rptSubmitDate = $rptSubmitDate;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }
    
    public function getTotalHrs() {
        return $this->totalHrs;
    }

    public function setTotalHrs($totalHrs) {
        $this->totalHrs = $totalHrs;
    }

    public function getTotalQtyPrd() {
        return $this->totalQtyPrd;
    }

    public function setTotalQtyPrd($totalQtyPrd) {
        $this->totalQtyPrd = $totalQtyPrd;
    }

    public function getPrdQtyList() {
        return $this->prdQtyList;
    }

    public function setPrdQtyList($prdQtyList) {
        $this->prdQtyList = $prdQtyList;
    }

    public function getServiceHrs() {
        return $this->serviceHrs;
    }

    public function setServiceHrs($serviceHrs) {
        $this->serviceHrs = $serviceHrs;
    }

    public function getIdorgRpts() {
        return $this->idorgRpts;
    }

    public function setIdorgRpts($idorgRpts) {
        $this->idorgRpts = $idorgRpts;
    }

    public function getIdorgs() {
        return $this->idorgs;
    }

    public function setIdorgs($idorgs) {
        $this->idorgs = $idorgs;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function setLatitude($latitude) {
        $this->latitude = $latitude;
    }

    public function setLongitude($longitude) {
        $this->longitude = $longitude;
    }

    public function getMapMarkerFile() {
        return $this->mapMarkerFile;
    }

    public function setMapMarkerFile($mapMarkerFile) {
        $this->mapMarkerFile = $mapMarkerFile;
    }


    
}
