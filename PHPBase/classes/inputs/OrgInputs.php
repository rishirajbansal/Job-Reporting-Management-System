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
 * Description of OrgInputs
 *
 * @author Rishi Raj
 */
class OrgInputs {
    
    private $idorgs;
    private $in_name;
    private $in_phone;
    private $in_email;
    private $in_username;
    private $in_password;
    private $logoName;
    private $actDeactivated;
    
    private $prdDynaCBIds;
    private $tskDynaCBIds;
    private $wrkDynaCBIds;
    private $cstDynaCBIds;
    private $rptDynaCBIds;
    
    private $in_templateName;
    private $in_templateId;
    
    private $qFilters;
    
    
    public function getIn_name() {
        return $this->in_name;
    }

    public function getIn_phone() {
        return $this->in_phone;
    }

    public function getIn_email() {
        return $this->in_email;
    }

    public function getIn_username() {
        return $this->in_username;
    }

    public function getIn_password() {
        return $this->in_password;
    }

    public function setIn_name($in_name) {
        $this->in_name = $in_name;
    }

    public function setIn_phone($in_phone) {
        $this->in_phone = $in_phone;
    }

    public function setIn_email($in_email) {
        $this->in_email = $in_email;
    }

    public function setIn_username($in_username) {
        $this->in_username = $in_username;
    }

    public function setIn_password($in_password) {
        $this->in_password = $in_password;
    }

    public function getLogoName() {
        return $this->logoName;
    }

    public function setLogoName($logoName) {
        $this->logoName = $logoName;
    }

    public function getPrdDynaCBIds() {
        return $this->prdDynaCBIds;
    }

    public function setPrdDynaCBIds($prdDynaCBIds) {
        $this->prdDynaCBIds = $prdDynaCBIds;
    }

    public function getTskDynaCBIds() {
        return $this->tskDynaCBIds;
    }

    public function getWrkDynaCBIds() {
        return $this->wrkDynaCBIds;
    }

    public function getCstDynaCBIds() {
        return $this->cstDynaCBIds;
    }

    public function setTskDynaCBIds($tskDynaCBIds) {
        $this->tskDynaCBIds = $tskDynaCBIds;
    }

    public function setWrkDynaCBIds($wrkDynaCBIds) {
        $this->wrkDynaCBIds = $wrkDynaCBIds;
    }

    public function setCstDynaCBIds($cstDynaCBIds) {
        $this->cstDynaCBIds = $cstDynaCBIds;
    }

    public function getIdorgs() {
        return $this->idorgs;
    }

    public function setIdorgs($idorgs) {
        $this->idorgs = $idorgs;
    }

    public function getActDeactivated() {
        return $this->actDeactivated;
    }

    public function setActDeactivated($actDeactivated) {
        $this->actDeactivated = $actDeactivated;
    }

    public function getIn_templateName() {
        return $this->in_templateName;
    }

    public function getIn_templateId() {
        return $this->in_templateId;
    }

    public function setIn_templateName($in_templateName) {
        $this->in_templateName = $in_templateName;
    }

    public function setIn_templateId($in_templateId) {
        $this->in_templateId = $in_templateId;
    }

    public function getRptDynaCBIds() {
        return $this->rptDynaCBIds;
    }

    public function setRptDynaCBIds($rptDynaCBIds) {
        $this->rptDynaCBIds = $rptDynaCBIds;
    }

    public function getQFilters() {
        return $this->qFilters;
    }

    public function setQFilters($qFilters) {
        $this->qFilters = $qFilters;
    }

    
    
}
