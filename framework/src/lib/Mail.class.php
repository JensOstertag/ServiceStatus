<?php

class Mail {
    private string $senderEmail;
    private string $senderName;
    private string $replyTo;
    private string $subject;
    private string $message;

    public function __construct() {
        $this->senderEmail = Config::$MAIL_SETTINGS["MAIL_DEFAULT_SENDER_EMAIL"];
        $this->senderName = Config::$MAIL_SETTINGS["MAIL_DEFAULT_SENDER_NAME"];
        $this->replyTo = Config::$MAIL_SETTINGS["MAIL_DEFAULT_REPLY_TO"];
        $this->subject = Config::$MAIL_SETTINGS["MAIL_DEFAULT_SUBJECT"];
        $this->message = "";
    }

    public function setSenderEmail($senderEmail): Mail {
        $this->senderEmail = $senderEmail;
        return $this;
    }

    public function setSenderName($senderName): Mail {
        $this->senderName = $senderName;
        return $this;
    }

    public function setReplyTo($replyTo): Mail {
        $this->replyTo = $replyTo;
        return $this;
    }

    public function setSubject($subject): Mail {
        $this->subject = $subject;
        return $this;
    }

    public function setMessage($message): Mail {
        $this->message = $message;
        return $this;
    }

    public function sendHTMLMail($recipient): void {
        $header = "MIME-Version: 1.0" . PHP_EOL;
        $header .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
        $header .= "From: " . $this->senderName . " <" . $this->senderEmail . ">" . PHP_EOL;
        $header .= "Reply-To: " . $this->replyTo . PHP_EOL;
        $header .= "X-Mailer: PHP/" . phpversion();

        if(Config::$MAIL_SETTINGS["MAIL_REDIRECT_ALL_MAILS"]) {
            $recipient = Config::$MAIL_SETTINGS["MAIL_REDIRECT_ALL_MAILS_TO"];
            Logger::getLogger("MAIL")->info("Redirecting mail to " . $recipient);
        }

        mail($recipient, $this->subject, $this->message, $header);
    }

    public function sendTextMail($recipient): void {
        $header = "MIME-Version: 1.0" . PHP_EOL;
        $header .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
        $header .= "From: " . $this->senderName . " <" . $this->senderEmail . ">" . PHP_EOL;
        $header .= "Reply-To: " . $this->replyTo . PHP_EOL;
        $header .= "X-Mailer: PHP/" . phpversion();

        if(Config::$MAIL_SETTINGS["MAIL_REDIRECT_ALL_MAILS"]) {
            $recipient = Config::$MAIL_SETTINGS["MAIL_REDIRECT_ALL_MAILS_TO"];
            Logger::getLogger("MAIL")->info("Redirecting mail to " . $recipient);
        }

        mail($recipient, $this->subject, $this->message, $header);
    }
}