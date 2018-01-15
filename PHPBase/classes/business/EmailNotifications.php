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
require_once(dirname(__FILE__) . "/../../lib/swiftmail/swift_required.php");
require_once(dirname(__FILE__) . "/MailInfo.php");

/**
 * Description of EmailNotifications
 *
 * @author Rishi Raj
 */
class EmailNotifications {
    
    private $logger;
    
    
    private $errors;
    private $messages;
    private $successMessage;
    private $completeMsg;
    private $criticalError;
    
    /*
     * 1 for simple success message
     * 2 for Complete success Final
     * 3 for array based messages
     * 4 for array based errors
     * 5 for Critical errors
     */
    private $msgType;
    
    
    function __construct() {
        
        $this->logger = Logger::getRootLogger();
        
        $this->errors = array();
        $this->messages = array();
        
    }
    
    
    function dispatchReportMail($mailInfo){
        
        $flag = FALSE;
        
        try{
            $this->logger->debug('[Report] Emailing Report...');
            
            $body = $this->prepareReportMailBody($mailInfo);
            $body = str_replace('{HOST}', EmailConfig::$webHost, $body);
            $subject = getLocaleText('EmailNotifications_mail_subject', TXT_U);
            $subject = str_replace('{REPORT_NO}', $mailInfo['reportNo'], $subject);
            
            $isAuthRequired = EmailConfig::$isAuthRequired;
            $openDebug = EmailConfig::$openDebug;
            
            $transport = Swift_SmtpTransport::newInstance(EmailConfig::$smtp_server, EmailConfig::$smtp_port) 
                        ->setUsername(EmailConfig::$smtp_username)
                        ->setPassword(EmailConfig::$smtp_password);
            
            if ($isAuthRequired){
                $transport->setEncryption('ssl');
                //$transport->setAuthMode('true');
            }
            
            $attachment = Swift_Attachment::fromPath($mailInfo['document'], 'application/pdf');
            
            $mailer = Swift_Mailer::newInstance($transport);
            
            $logger = new Swift_Plugins_Loggers_ArrayLogger();
            $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
            
            $message = Swift_Message::newInstance($subject);
            
            $message->setSubject($subject);
            $message->setFrom(array(EmailConfig::$from => EmailConfig::$from_name));
            $message->setTo(array($mailInfo['email'] => $mailInfo['name']));
            
            $message->attach($attachment);
            
            $message->addPart($body, 'text/html');
			
            $flag = $mailer->send($message);
            
            if ($openDebug){
                $this->logger->debug('[Report] Mail Dispatch info : ' . $logger->dump());
            }
            
            $this->logger->debug('[Report] Done with Emailing Report.');
            
        }
        catch (Exception $ex) {
            $this->logger->error( '[Report] [Exp] {' . __METHOD__ . '} : ' . $ex->getMessage());
            $this->setCriticalError($ex->getMessage() . getLocaleText('EmailNotifications_dispatchReportMail_MSG_EX', TXT_U));
            $this->setMsgType(SUBMISSION_MSG_TYPE_CRITICALERROR);
        }
        
        return $flag;
        
    }
    
    
    function prepareReportMailBody($mailInfo){
        
        $locale = getLocale();
        
        $layoutPath = dirname(__FILE__) . "/../../web/mTemplates/" . RPT_MAIL_LAYOUT . $locale . MAIL_TEMPLATE_EXT;
        
        $template = file_get_contents($layoutPath);
        
        $template = str_replace('{ORG_NAME}', $mailInfo['orgname'], $template);
        $template = str_replace('{REPORT_NO}', $mailInfo['reportNo'], $template);
        $template = str_replace('{WORKER}', $mailInfo['worker'], $template);
        
        
        return $template;
        
    }
    
    
    public function getErrors() {
        return $this->errors;
    }

    public function getMessages() {
        return $this->messages;
    }

    public function getSuccessMessage() {
        return $this->successMessage;
    }

    public function getCompleteMsg() {
        return $this->completeMsg;
    }

    public function getCriticalError() {
        return $this->criticalError;
    }

    public function getMsgType() {
        return $this->msgType;
    }

    public function setErrors($errors) {
        $this->errors = $errors;
    }

    public function setMessages($messages) {
        $this->messages = $messages;
    }

    public function setSuccessMessage($successMessage) {
        $this->successMessage = $successMessage;
    }

    public function setCompleteMsg($completeMsg) {
        $this->completeMsg = $completeMsg;
    }

    public function setCriticalError($criticalError) {
        $this->criticalError = $criticalError;
    }

    public function setMsgType($msgType) {
        $this->msgType = $msgType;
    }


    
}
