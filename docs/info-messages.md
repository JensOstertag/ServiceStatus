# Documentation
## Info messages
You can send different info messages to the user by using
```php
new InfoMessage("MESSAGE", InfoMessageType::INFO);
new InfoMessage("MESSAGE", InfoMessageType::WARNING);
new InfoMessage("MESSAGE", InfoMessageType::ERROR);
new InfoMessage("MESSAGE", InfoMessageType::SUCCESS);
```
with ``MESSAGE`` being the message that should be displayed.

An info message is stored in the ``$_SESSION`` storage (``$_SESSION["infoMessages"]``) and is displayed by the ``infomessages.php`` Template File on the next page load. Saving the info messages in the ``$_SESSION`` storage allows you to redirect the user to another route without losing them.

Info messages are automatically removed from the ``$_SESSION`` storage when they're retrieved with ``InfoMessage::getMessages()``. That method also returns the info messages ordered by their type (success, error, warning, info).

You might also want to display info messages within JavaScript code. This can be done with the JavaScript ``InfoMessage`` class. It's usage is almost the same as the one in PHP:
```javascript
// Info message
new InfoMessage("This is an info message", InfoMessage.INFO);

// Warning message
new InfoMessage("This is a warning message", InfoMessage.WARNING);

// Error message
new InfoMessage("This is an error message", InfoMessage.ERROR);

// Success message
new InfoMessage("This is a success message", InfoMessage.SUCCESS);
```

To keep the order of the messages, the class inserts a new message above all other messages of the same type. More important messages will still be shown first.

As you can modify the DOM within JavaScript, you can also delete / hide info messages. The following example shows how you can delete all info messages displayed by the backend:
```javascript
$(document).ready(() => {
    InfoMessage.clearMessages();
});
```
