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
 * Description of UserOrgInputs
 *
 * @author Rishi Raj
 */
class UserOrgInputs {
    
    private $idorgs;
    private $postedIdValues;
    private $idprds;
    private $idtsks;
    private $idwrks;
    private $idcstmrs;
    private $idrpts;
    
    private $in_orgname;
    private $in_username;
    private $in_password;
    
    private $client;
    private $worker;
    
    private $qf_sdate;
    private $qf_edate;
    private $qf_clientname;
    private $qf_workername;
    
    private $ex_type;
            
    
    
    public function getIdorgs() {
        return $this->idorgs;
    }

    public function setIdorgs($idorgs) {
        $this->idorgs = $idorgs;
    }

    public function getPostedIdValues() {
        return $this->postedIdValues;
    }

    public function setPostedIdValues($postedIdValues) {
        $this->postedIdValues = $postedIdValues;
    }

    public function getIdprds() {
        return $this->idprds;
    }

    public function setIdprds($idprds) {
        $this->idprds = $idprds;
    }

    public function getIn_username() {
        return $this->in_username;
    }

    public function getIn_password() {
        return $this->in_password;
    }

    public function setIn_username($in_username) {
        $this->in_username = $in_username;
    }

    public function setIn_password($in_password) {
        $this->in_password = $in_password;
    }

    public function getIn_orgname() {
        return $this->in_orgname;
    }

    public function setIn_orgname($in_orgname) {
        $this->in_orgname = $in_orgname;
    }

    public function getIdtsks() {
        return $this->idtsks;
    }

    public function setIdtsks($idtsks) {
        $this->idtsks = $idtsks;
    }

    public function getIdwrks() {
        return $this->idwrks;
    }

    public function setIdwrks($idwrks) {
        $this->idwrks = $idwrks;
    }

    public function getIdcstmrs() {
        return $this->idcstmrs;
    }

    public function setIdcstmrs($idcstmrs) {
        $this->idcstmrs = $idcstmrs;
    }

    public function getIdrpts() {
        return $this->idrpts;
    }

    public function setIdrpts($idrpts) {
        $this->idrpts = $idrpts;
    }

    public function getQf_sdate() {
        return $this->qf_sdate;
    }

    public function getQf_edate() {
        return $this->qf_edate;
    }

    public function getQf_clientname() {
        return $this->qf_clientname;
    }

    public function getQf_workername() {
        return $this->qf_workername;
    }

    public function setQf_sdate($qf_sdate) {
        $this->qf_sdate = $qf_sdate;
    }

    public function setQf_edate($qf_edate) {
        $this->qf_edate = $qf_edate;
    }

    public function setQf_clientname($qf_clientname) {
        $this->qf_clientname = $qf_clientname;
    }

    public function setQf_workername($qf_workername) {
        $this->qf_workername = $qf_workername;
    }

    public function getClient() {
        return $this->client;
    }

    public function getWorker() {
        return $this->worker;
    }

    public function setClient($client) {
        $this->client = $client;
    }

    public function setWorker($worker) {
        $this->worker = $worker;
    }

    public function getEx_type() {
        return $this->ex_type;
    }

    public function setEx_type($ex_type) {
        $this->ex_type = $ex_type;
    }

    
}
