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
 * VO for Dynamic Fields
 *
 * @author Rishi Raj
 */
class DynaFields {
    
    private $idDynaFields;
    private $nameEn;
    private $nameEs;
    private $name;
    private $descriptionEn;
    private $descriptionEs;
    private $description;
    private $htmlName;
    private $htmlType;
    private $htmlListValuesEn;
    private $htmlListValuesEs;
    private $htmlListValues;
    private $htmlValidations;
    private $htmlValidationsMessagesEn;
    private $htmlValidationsMessagesEs;
    private $htmlValidationsMessages;
    private $icon;
    private $recom;
    
    private $createdOn;
    private $lastUpdated;
    
    
    
    public function getIdDynaFields() {
        return $this->idDynaFields;
    }

    public function getNameEn() {
        return $this->nameEn;
    }

    public function getNameEs() {
        return $this->nameEs;
    }

    public function getDescriptionEn() {
        return $this->descriptionEn;
    }

    public function getDescriptionEs() {
        return $this->descriptionEs;
    }

    public function getHtmlName() {
        return $this->htmlName;
    }

    public function getHtmlType() {
        return $this->htmlType;
    }

    public function getHtmlValidations() {
        return $this->htmlValidations;
    }

    public function getHtmlListValuesEn() {
        return $this->htmlListValuesEn;
    }

    public function getHtmlListValuesEs() {
        return $this->htmlListValuesEs;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function getLastUpdated() {
        return $this->lastUpdated;
    }

    public function setIdDynaFields($idDynaFields) {
        $this->idDynaFields = $idDynaFields;
    }

    public function setNameEn($nameEn) {
        $this->nameEn = $nameEn;
    }

    public function setNameEs($nameEs) {
        $this->nameEs = $nameEs;
    }

    public function setDescriptionEn($descriptionEn) {
        $this->descriptionEn = $descriptionEn;
    }

    public function setDescriptionEs($descriptionEs) {
        $this->descriptionEs = $descriptionEs;
    }

    public function setHtmlName($htmlName) {
        $this->htmlName = $htmlName;
    }

    public function setHtmlType($htmlType) {
        $this->htmlType = $htmlType;
    }

    public function setHtmlValidations($htmlValidations) {
        $this->htmlValidations = $htmlValidations;
    }

    public function setHtmlListValuesEn($htmlListValuesEn) {
        $this->htmlListValuesEn = $htmlListValuesEn;
    }

    public function setHtmlListValuesEs($htmlListValuesEs) {
        $this->htmlListValuesEs = $htmlListValuesEs;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    public function setLastUpdated($lastUpdated) {
        $this->lastUpdated = $lastUpdated;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }
    public function getHtmlValidationsMessagesEn() {
        return $this->htmlValidationsMessagesEn;
    }

    public function getHtmlValidationsMessagesEs() {
        return $this->htmlValidationsMessagesEs;
    }

    public function setHtmlValidationsMessagesEn($htmlValidationsMessagesEn) {
        $this->htmlValidationsMessagesEn = $htmlValidationsMessagesEn;
    }

    public function setHtmlValidationsMessagesEs($htmlValidationsMessagesEs) {
        $this->htmlValidationsMessagesEs = $htmlValidationsMessagesEs;
    }

    public function getRecom() {
        return $this->recom;
    }

    public function setRecom($recom) {
        $this->recom = $recom;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getHtmlListValues() {
        return $this->htmlListValues;
    }

    public function getHtmlValidationsMessages() {
        return $this->htmlValidationsMessages;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setHtmlListValues($htmlListValues) {
        $this->htmlListValues = $htmlListValues;
    }

    public function setHtmlValidationsMessages($htmlValidationsMessages) {
        $this->htmlValidationsMessages = $htmlValidationsMessages;
    }



}
