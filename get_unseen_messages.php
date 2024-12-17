<?php
include("includes/initialize.php");

if (isset($_POST['sender_id']) && isset($_POST['receiver_id'])) {
    $sender_id = $_POST['sender_id'];  // User who sent the message
    $receiver_id = $_POST['receiver_id'];  // Logged-in user (receiver)
    $stmt = $database->prepare("SELECT COUNT(*) FROM messages WHERE sender_id = :sender_id AND receiver_id = :receiver_id AND is_seen = 0");
    $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
    $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();
    $unseenCount = $stmt->fetchColumn();
    echo json_encode(['unseen_count' => $unseenCount]);
} else {
    echo json_encode(['unseen_count' => 0]);
}
?>
