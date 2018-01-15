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


//require_once(dirname(__FILE__) . "/../../config/config.php");

define("I18N_PATH", dirname(__FILE__) ."/../I18n/locale/");
define("I18N_PATH_FILE_ADMIN", "/strings/admin.json");
define("I18N_PATH_FILE_USER", "/strings/user.json");

define("TXT_A", "a");
define("TXT_U", "u");


function getLocaleText($string, $txt) {
    
    static $localeStringsA = NULL;
    static $localeStringsU = NULL;
    
    /*if (session_id() == "") {
        //If session is not available it means Constants.php is being loaded, continue only if session is there
        return;
    }*/

    
    $localeStrings = $txt == TXT_A ? $localeStringsA : $localeStringsU;
    
    if (is_null($localeStrings)) {
        
        $locale = DEFAULT_LOCALE;
        
        if (isset($_SESSION['locale'])){
            $locale = $_SESSION['locale'];
        }
        
        $localFile = '';
        if ($txt == TXT_A){
            $localFile = I18N_PATH_FILE_ADMIN;
        }
        else{
            $localFile = I18N_PATH_FILE_USER;
        }
        
        $localePath = I18N_PATH . $locale . $localFile;
        
        if (!file_exists($localePath)) {
            $localePath = I18N_PATH . DEFAULT_LOCALE . $localFile;
        }
        
        $localeStringContents = file_get_contents($localePath);
        
        if ($txt == TXT_A){
            $localeStringsA = json_decode($localeStringContents, true);
            $localeStrings = $localeStringsA;
        }
        else{
             $localeStringsU = json_decode($localeStringContents, true);
             $localeStrings = $localeStringsU;
        }
        
    }
    
    if (!array_key_exists($string, $localeStrings)) {
        return $string;
    }
    else {
        return $localeStrings[$string];
    }
    
    
}

function setLocaleDynaText($enContent, $spContent){
    
    $locale = DEFAULT_LOCALE;
        
    if (isset($_SESSION['locale'])){
        $locale = $_SESSION['locale'];
    }
    
    if ($locale == LOCALE_ESP_SP){
        return $spContent;
    }
    else{
        return $enContent;
    }
    
}

function getLocale(){
    
    $locale = DEFAULT_LOCALE;
        
    if (isset($_SESSION['locale'])){
        $locale = $_SESSION['locale'];
    }
    
    return $locale;
    
}


?>