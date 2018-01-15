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


require_once(dirname(__FILE__) . "/DynaFields.php");

/**
 * Description of UserEntity
 *
 * @author Rishi Raj
 */
class UserEntity extends DynaFields {
    
    private $selectedListValuesEn;
    private $selectedListValuesEs;
    private $selectedListValues;
    private $htmlValidationsArr;
    private $htmlValidationsMessagesEnArr;
    private $htmlValidationsMessagesEsArr;
    private $htmlValidationsMessagesArr;
    
    private $savedValue;
    
    
    public function getSelectedListValuesEn() {
        return $this->selectedListValuesEn;
    }

    public function setSelectedListValuesEn($selectedListValuesEn) {
        $this->selectedListValuesEn = $selectedListValuesEn;
    }

    public function getSelectedListValuesEs() {
        return $this->selectedListValuesEs;
    }

    public function setSelectedListValuesEs($selectedListValuesEs) {
        $this->selectedListValuesEs = $selectedListValuesEs;
    }

    public function getHtmlValidationsArr() {
        return $this->htmlValidationsArr;
    }

    public function getHtmlValidationsMessagesEnArr() {
        return $this->htmlValidationsMessagesEnArr;
    }

    public function getHtmlValidationsMessagesEsArr() {
        return $this->htmlValidationsMessagesEsArr;
    }

    public function setHtmlValidationsArr($htmlValidationsArr) {
        $this->htmlValidationsArr = $htmlValidationsArr;
    }

    public function setHtmlValidationsMessagesEnArr($htmlValidationsMessagesEnArr) {
        $this->htmlValidationsMessagesEnArr = $htmlValidationsMessagesEnArr;
    }

    public function setHtmlValidationsMessagesEsArr($htmlValidationsMessagesEsArr) {
        $this->htmlValidationsMessagesEsArr = $htmlValidationsMessagesEsArr;
    }

    public function getSavedValue() {
        return $this->savedValue;
    }

    public function setSavedValue($savedValue) {
        $this->savedValue = $savedValue;
    }

    public function getSelectedListValues() {
        return $this->selectedListValues;
    }

    public function getHtmlValidationsMessagesArr() {
        return $this->htmlValidationsMessagesArr;
    }

    public function setSelectedListValues($selectedListValues) {
        $this->selectedListValues = $selectedListValues;
    }

    public function setHtmlValidationsMessagesArr($htmlValidationsMessagesArr) {
        $this->htmlValidationsMessagesArr = $htmlValidationsMessagesArr;
    }


    
}
