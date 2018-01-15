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
 * Orgnization VO
 *
 * @author Rishi Raj
 */
class Organization {
    
    private $idorgs;
    private $name;
    private $phone;
    private $email;
    private $username;
    private $password;
    private $activated;
    
    private $logoPath;
    
    private $templateName;
    private $templateModel;
    private $templatePath;
    private $rawTemplateId;
    
    private $isRptStructConfigured;
    private $rptQFilters;
    
    private $createdOn;
    private $lastUpdated;
    
    
    
    public function getIdorgs() {
        return $this->idorgs;
    }

    public function getName() {
        return $this->name;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
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

    public function setName($name) {
        $this->name = $name;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    public function setLastUpdated($lastUpdated) {
        $this->lastUpdated = $lastUpdated;
    }

    public function getLogoPath() {
        return $this->logoPath;
    }

    public function setLogoPath($logoPath) {
        $this->logoPath = $logoPath;
    }

    public function getActivated() {
        return $this->activated;
    }

    public function setActivated($activated) {
        $this->activated = $activated;
    }

    public function getTemplateName() {
        return $this->templateName;
    }

    public function setTemplateName($templateName) {
        $this->templateName = $templateName;
    }

    public function getTemplateModel() {
        return $this->templateModel;
    }

    public function setTemplateModel($templateModel) {
        $this->templateModel = $templateModel;
    }

    public function getTemplatePath() {
        return $this->templatePath;
    }

    public function setTemplatePath($templatePath) {
        $this->templatePath = $templatePath;
    }

    public function getRawTemplateId() {
        return $this->rawTemplateId;
    }

    public function setRawTemplateId($rawTemplateId) {
        $this->rawTemplateId = $rawTemplateId;
    }

    public function getIsRptStructConfigured() {
        return $this->isRptStructConfigured;
    }

    public function setIsRptStructConfigured($isRptStructConfigured) {
        $this->isRptStructConfigured = $isRptStructConfigured;
    }

    public function getRptQFilters() {
        return $this->rptQFilters;
    }

    public function setRptQFilters($rptQFilters) {
        $this->rptQFilters = $rptQFilters;
    }



}
