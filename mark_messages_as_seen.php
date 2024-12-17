<?php
include("includes/initialize.php");

if (isset($_POST['sender_id']) && isset($_POST['receiver_id'])) {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];

    // Update the is_seen field for messages
    $stmt = $database->prepare("
        UPDATE messages 
        SET is_seen = 1 
        WHERE sender_id = :sender_id AND receiver_id = :receiver_id AND is_seen = 0
    ");
    $stmt->bindParam(':sender_id', $sender_id);
    $stmt->bindParam(':receiver_id', $receiver_id);
    $stmt->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
}
?>
