<?php
include ("include/initialize.php"); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];

    // Update the messages table to mark messages as seen
    $query = "UPDATE messages SET seen = 1 WHERE sender_id = ? AND receiver_id = ? AND seen = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $sender_id, $receiver_id);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Messages marked as seen"]);
    } else {
        echo json_encode(["success" => false, "error" => "Database update failed"]);
    }
    $stmt->close();
}
?>
