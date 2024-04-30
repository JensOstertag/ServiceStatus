# Documentation
## Mail
You can use the ``Mail`` class to send emails. To do that, use
```php
// Initialize the mail object
$mail = new Mail();
$mail->setSenderEmail("SENDER_EMAIL");
$mail->setSenderName("SENDER_NAME");
$mail->setReplyTo("REPLY_TO");
$mail->setSubject("SUBJECT");
$mail->setMessage("MESSAGE");

// Send the mail either as plain text or as HTML
$mail->sendTextMail("RECIPIENT_EMAIL");
$mail->sendHTMLMail("RECIPIENT_EMAIL");
```
with ``SENDER_EMAIL`` being the displayed sender email address, ``SENDER_NAME`` the displayed sender name, ``REPLY_TO`` the email address that should be used as reply-to address, ``SUBJECT`` the mail's subject, ``MESSAGE`` it's body and ``RECIPIENT_EMAIL`` the email address the mail should be sent to.

The mail's body can be formatted as plain text or as HTML. To send it as plain text, use the ``sendTextMail`` method, if you want to send an HTML mail, use the ``sendHTMLMail`` method. The difference between both methods is that the ``sendHTMLMail`` method will add the ``Content-Type: text/html`` header to the mail.