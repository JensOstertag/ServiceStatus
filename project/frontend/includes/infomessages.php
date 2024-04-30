<div class="infomessages-list">
    <?php foreach (InfoMessage::getMessages() as $infoMessage): ?>
        <div class="infomessage" message-type="<?php output($infoMessage->getType()->getFormatted()); ?>">
            <?php output($infoMessage->getMessage()); ?>
        </div>
    <?php endforeach; ?>
</div>
