<?php
namespace Mnl;

class Mail
{
    public $boundaryId;
    public $fromAddress;
    public $replyToAddress;
    public $toAddress;
    public $subject;
    public $mailContent;
    public $headers;

    public function __construct()
    {
        $this->boundaryId = md5(date('r', time()));
    }

    public function setHeaders()
    {
        if (!isset($this->fromAddress)) {
            return false;
        }
        if (!isset($this->replyToAddress) || $this->replyToAddress == '') {
            $this->replyToAddress = $this->fromAddress;
        }
        $headers = "From: ".$this->fromAddress."\r\nReply-To: ".$this->replyToAddress;
        $headers .= "\r\nContent-Type: multipart/alternative; boundary=\"alt-".$this->boundaryId."\"";
        $this->headers = $headers;
    }

    public function setContent($text, $html = '')
    {
        if ($text == '') {
            return false;
        }
        $content = "--alt-".$this->boundaryId."\r\n";
        $content .= 'Content-Type: text/plain; charset="iso-8859-1"'."\r\n";
        $content .= 'Content-Transfer-Encoding: 7bit'."\r\n\r\n";
        $content .= $text;

        if ($html != '') {
            $content .= "\r\n--alt-".$this->boundaryId."\r\n";
            $content .= 'Content-Type: text/html; charset="iso-8859-1"'."\r\n";
            $content .= 'Content-Transfer-Encoding: 7bit'."\r\n\r\n";
            $content .= $html;
        }
        $content .= "\r\n--alt-".$this->boundaryId."--"."\r\n";

        $this->mailContent = $content;

        return true;
    }

    public function send($to, $from, $subject, $contentText, $contentHtml = '', $replyTo = '')
    {
        $this->fromAddress = $from;
        $this->replyToAddress = $replyTo;
        $this->setHeaders();
        $sent = false;
        if ($this->setContent($contentText, $contentHtml)) {
            $sent = mail($to, $subject, $this->mailContent, $this->headers);
        }

        return $sent;
    }
}
