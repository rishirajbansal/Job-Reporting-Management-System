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


function show_errors($errMsgObject){

    $error = false;

    if(!empty($errMsgObject['msg'])){

        if(is_array($errMsgObject['text'])){
            foreach($errMsgObject['text'] as $text){
                $error .= "<i class=\"fa-fw fa fa-warning\"></i><strong>Error!</strong> $text"."<br/>";
            }	
        }
        else{
           $error .= "<i class=\"fa-fw fa fa-warning\"></i><strong>Error!</strong>". $errMsgObject['text'];
        }

        $error .= "<br/>";
    }

    return $error;

}

function show_infoMessages($errMsgObject){

    $error = false;

    if(!empty($errMsgObject['msg'])){
        
        if(is_array($errMsgObject['text'])){
            foreach($errMsgObject['text'] as $text){
                $error .= "<i class=\"fa-fw fa fa-info\"></i><strong>Info!</strong> $text"."<br/>";
            }	
        }
        else{
           $error .= "<i class=\"fa-fw fa fa-info\"></i><strong>Info!</strong>". $errMsgObject['text'];
        }

        $error .= "<br/>";
    }

    return $error;

}

function show_successMessages($errMsgObject){

    $error = false;

    if(!empty($errMsgObject['msg'])){
        
        if(is_array($errMsgObject['text'])){
            foreach($errMsgObject['text'] as $text){
                $error .= "<i class=\"fa-fw fa fa-check\"></i><strong>Success!</strong> $text"."<br/>";
            }	
        }
        else{
           $error .= "<i class=\"fa-fw fa fa-check\"></i><strong>Success!</strong>". $errMsgObject['text'];
        }

        $error .= "<br/>";
    }

    return $error;

}

?>