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
 * Description of MailInfo
 *
 * @author Rishi Raj
 */
class MailInfo {
    
    private $to;
    private $subject;
    private $body;
    private $contentType;
    private $username;
    private $event;
    
    
    
    public function getTo() {
        return $this->to;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function getBody() {
        return $this->body;
    }

    public function getContentType() {
        return $this->contentType;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEvent() {
        return $this->event;
    }

    public function setTo($to) {
        $this->to = $to;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function setContentType($contentType) {
        $this->contentType = $contentType;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEvent($event) {
        $this->event = $event;
    }


    
}
