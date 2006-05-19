<?php
// +---------------------------------------------------------------+
// | Sending emails.                                               |
// | Author: Cezary Tomczak [www.gosu.pl]                          |
// | Free for any use as long as all copyright messages are intact.|
// +---------------------------------------------------------------|

class Mail {
    
    var $charset = 'iso-8859-1';
    var $ctencoding = '8bit';
    var $contentType = 'text/plain'; // text/plain or text/html

    var $from;
    var $replyTo;
    var $to;
    var $subject;
    var $body;

    function Mail($from = null, $replyTo = null, $to = null, $subject = null, $body = null) {
        $this->from    = $from;
        $this->replyTo = $replyTo;
        $this->to      = $to;
        $this->subject = $subject;
        $this->body    = $body;
    }

    function setFrom($from)       { $this->from = $from; }
    function setReplyTo($replyTo) { $this->replyTo = $replyTo; }
    function setTo($to)           { $this->to = $to; }
    function setSubject($subject) { $this->subject = $subject; }
    function setBody($body)       { $this->body = $body; }

    function attachFile() {}

    function send() {
        
        $charset     = $this->charset;
        $ctencoding  = $this->ctencoding;
        $contentType = $this->contentType;

        $from    = $this->quote($this->from);
        $replyTo = $this->replyTo;
        $to      = $this->to;
        $subject = $this->quote($this->subject);
        $body    = $this->body;

        $xMailer = 'PHP/' . phpversion();

        $h  = "From: {$from} <{$replyTo}> \r\n";
        $h .= "X-Sender: <{$replyTo}> \r\n";
        $h .= "X-Mailer: {$xMailer} \r\n";
        $h .= "Return-Path: <{$replyTo}> \r\n";
        $h .= "Mime-Version: 1.0 \r\n";
        $h .= "Content-Type: {$contentType}; charset={$charset} \r\n";
        $h .= "Content-Transfer-Encoding: {$ctencoding} \r\n";

        return @mail($to, $subject, $body, $h);
    }

    function quote($s) {
        $s = str_replace('%', '=', rawurlencode($s));
        $s = "=?{$this->charset}?Q?{$s}?=";
        return $s;
    }
}

?>