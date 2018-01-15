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


require_once(dirname(__FILE__) . "/../../config/dbconfig.php");
require_once(dirname(__FILE__) . "/../common.php");

/**
 * Description of DatabaseConnectionManager
 *
 * @author Rishi Raj
 */
class DatabaseConnectionManager {
    
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;

    private $connection;
    
    private $logger;
    
    function __construct() {
        $this->logger = Logger::getRootLogger();
        
        $this->host = DB_HOST;
        $this->username = DB_USER;
        $this->password = DB_PASSWORD;
        $this->database = DB_NAME;
        $this->port = DB_PORT;
    }

    
    function createConnection() {
        
        try{
            
            $connection = mysql_connect($this->host, $this->username, $this->password);

            if (!$connection) {
                die('Could not connect to MySQL: ' . mysqli_connect_error());
            }
            else{
                $this->connection = $connection;
                mysql_select_db($this->database);
                //$this->logger->info("Database Connection established successfully.");

            }
        } 
        catch (Exception $ex) {
            $this->logger->error("Exception in creating database connection: " . $ex->getMessage());
        }
        
    }
    
    function getConnection() {
        return $this->connection;
    }
    
    function returnConnection() {
        if (isset($this->connection) && is_resource($this->connection)) {
            mysql_close($this->connection);        
        }
    }
    
}
