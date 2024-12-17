<?php
include("includes/initialize.php");  // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    // Prepare SQL to insert the message
    $stmt = $database->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)");
    $stmt->bindParam(':sender_id', $sender_id);
    $stmt->bindParam(':receiver_id', $receiver_id);
    $stmt->bindParam(':message', $message);
    $stmt->execute();

    echo json_encode(['success' => true]);  // Return success
}
?>
