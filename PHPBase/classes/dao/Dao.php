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



require_once(dirname(__FILE__) . "/../common.php");
require_once(dirname(__FILE__) . "/Sqls.php");

/**
 * Description of dao
 *
 * @author Rishi Raj
 */
class Dao {
    
    private $connectionManager;
    private $queryManager;
    
    
    function __construct() {
        
        $this->connectionManager = new DatabaseConnectionManager();
        $this->connectionManager->createConnection();
        $this->queryManager = new DatabaseQueryManager($this->connectionManager->getConnection());
    }
    
    function __destruct() {
        if ($this->connectionManager->getConnection()){
            $this->connectionManager->returnConnection();
        }
    }
    
    
    public function getConnectionManager() {
        return $this->connectionManager;
    }

    public function getQueryManager() {
        return $this->queryManager;
    }

    public function setConnectionManager($connectionManager) {
        $this->connectionManager = $connectionManager;
    }

    public function setQueryManager($queryManager) {
        $this->queryManager = $queryManager;
    }


}
