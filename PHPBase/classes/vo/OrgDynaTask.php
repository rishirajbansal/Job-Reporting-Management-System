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


require_once(dirname(__FILE__) . "/OrgDynaSuper.php");

/**
 * Details of dynamic Task fields
 *
 * @author Rishi Raj
 */
class OrgDynaTask extends OrgDynaSuper {
    
    private $idOrgsDynaTask;
    
    
    public function getIdOrgsDynaTask() {
        return $this->idOrgsDynaTask;
    }

    public function setIdOrgsDynaTask($idOrgsDynaTask) {
        $this->idOrgsDynaTask = $idOrgsDynaTask;
    }


}
