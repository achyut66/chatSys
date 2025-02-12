<?php
require_once("includes/initialize.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $username = User::find_by_id($sender_id);
    $chat_history = SaveChat::get_chat_history($sender_id, $receiver_id);

    if ($chat_history) {
        foreach ($chat_history as $message) {
            if ($message['sender_id'] == $sender_id) {
                // Sender's message, align to the right
                echo "<div class='message sender'>"
                    . "<strong><img src='image/blank.jpg' class='user-img'></strong>"
                    . "<span class='message-text'>" . $message['message'] . "</span>"
                    . "</div>";
            } else {
                // Receiver's message, align to the left
                echo "<div class='message receiver'>"
                    . "<strong><img src='image/blank.jpg' class='user-img'></strong>"
                    . "<span class='message-text'>" . $message['message'] . "</span>"
                    . "</div>";
            }
        }
    } else {
        echo "<div>No messages yet.</div>";
    }
}
?>
