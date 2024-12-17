<?php
include("includes/initialize.php");
header('Content-Type: application/json');
if (isset($_GET['sender_id'])) {
    $sender_id = $_GET['sender_id'];
    if (!is_numeric($sender_id)) {
        echo json_encode(['error' => 'Invalid sender_id']);
        exit;
    }
    $user = User::find_by_id_json($sender_id);
    if ($user) {
        echo json_encode(['username' => $user['username']]);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} else {
    echo json_encode(['error' => 'No sender_id provided']);
}
exit;
?>
