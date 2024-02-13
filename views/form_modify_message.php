<h1>Edit Message</h1>
    <form method="post" action="index.php?action=modify_message">
        <input type="hidden" name="id" value="<?php echo $message_id; ?>">
        <label for="message">Message:</label><br>
        <textarea name="message"><?php echo $message['message']; ?></textarea><br>
        <input type="submit" value="Modifier le message">
    </form>