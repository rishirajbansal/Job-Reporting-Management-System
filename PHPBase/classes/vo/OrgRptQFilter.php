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
 * Description of OrgRptQFilter
 *
 * @author Rishi Raj
 */
class OrgRptQFilter {
    
    private $idRptsFilters;
    private $qfilterId;
    private $qfilterNameEn;
    private $qfilterNameEs;
    private $qfilterName;
    private $qfilterDescEn;
    private $qfilterDescEs;
    private $qfilterDesc;
    private $pfile;
    
    private $createdOn;
    private $lastUpdated;
    
    
    public function getIdRptsFilters() {
        return $this->idRptsFilters;
    }

    public function getQfilterId() {
        return $this->qfilterId;
    }

    public function getQfilterNameEn() {
        return $this->qfilterNameEn;
    }

    public function getQfilterNameEs() {
        return $this->qfilterNameEs;
    }

    public function getQfilterDescEn() {
        return $this->qfilterDescEn;
    }

    public function getQfilterDescEs() {
        return $this->qfilterDescEs;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function getLastUpdated() {
        return $this->lastUpdated;
    }

    public function setIdRptsFilters($idRptsFilters) {
        $this->idRptsFilters = $idRptsFilters;
    }

    public function setQfilterId($qfilterId) {
        $this->qfilterId = $qfilterId;
    }

    public function setQfilterNameEn($qfilterNameEn) {
        $this->qfilterNameEn = $qfilterNameEn;
    }

    public function setQfilterNameEs($qfilterNameEs) {
        $this->qfilterNameEs = $qfilterNameEs;
    }

    public function setQfilterDescEn($qfilterDescEn) {
        $this->qfilterDescEn = $qfilterDescEn;
    }

    public function setQfilterDescEs($qfilterDescEs) {
        $this->qfilterDescEs = $qfilterDescEs;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    public function setLastUpdated($lastUpdated) {
        $this->lastUpdated = $lastUpdated;
    }

    public function getPfile() {
        return $this->pfile;
    }

    public function setPfile($pfile) {
        $this->pfile = $pfile;
    }

    public function getQfilterName() {
        return $this->qfilterName;
    }

    public function getQfilterDesc() {
        return $this->qfilterDesc;
    }

    public function setQfilterName($qfilterName) {
        $this->qfilterName = $qfilterName;
    }

    public function setQfilterDesc($qfilterDesc) {
        $this->qfilterDesc = $qfilterDesc;
    }


    
}
