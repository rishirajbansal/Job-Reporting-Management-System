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
 * Description of XEditDynaValueInputs
 *
 * @author Rishi Raj
 */
class XEditDynaValueInputs {
    
    private $dynaType;
    private $dynaId;
    private $values;
    
    
    public function getDynaId() {
        return $this->dynaId;
    }

    public function getValues() {
        return $this->values;
    }

    public function setDynaId($dynaId) {
        $this->dynaId = $dynaId;
    }

    public function setValues($values) {
        $this->values = $values;
    }

    public function getDynaType() {
        return $this->dynaType;
    }

    public function setDynaType($dynaType) {
        $this->dynaType = $dynaType;
    }


    
}
