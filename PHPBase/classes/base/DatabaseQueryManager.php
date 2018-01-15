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
 * Description of DatabaseQueryManager
 *
 * @author Rishi Raj
 */
class DatabaseQueryManager {
    
    private $connection;
    
    function __construct($connection) {
        $this->connection = $connection;
    }
    
    function query($sql){
        
        $result = mysql_query($sql, $this->connection);
        
        if (! $result ) {
            //echo 'Could not get data: ' . mysql_error();
            throw new Exception('Could not get data: ' . mysql_error());
        }
        
        return $result;
    }
    
    function queryInsertAndGetId($sql){
        
        $result = mysql_query($sql, $this->connection);
        
        if (! $result ) {
            //echo 'Could not insert data: ' . mysql_error();
            throw new Exception('Could not insert data: ' . mysql_error());
        }
        else{
            $result = mysql_insert_id();
        }
        
        return $result;
    }
    
    function update($sql){
        $result = mysql_query($sql, $this->connection);
          
        return $result;
    }
    
    function fetchSingleRow($result) {
        
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        return $row;
    }
    
    function fetchCount($result){
        $count = mysql_num_rows($result);
        return $count;
    }
            
    function releaseResultSet($result) {
        mysql_free_result($result);        
    }
}
