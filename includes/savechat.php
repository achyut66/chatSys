<?php
require_once("initialize.php");

class SaveChat {
    protected static $table_name = "messages";
    protected static $db_fields = array('id', 'sender_id', 'receiver_id', 'message', 'timestamp');

    public $id;
    public $sender_id;
    public $receiver_id;
    public $message;
    public $timestamp;

    // Fetch chat history between two users
    public static function get_chat_history($sender_id, $receiver_id) {
        global $database;
        $sql = "SELECT * FROM " . self::$table_name . " WHERE (sender_id = {$sender_id} AND receiver_id = {$receiver_id}) OR (sender_id = {$receiver_id} AND receiver_id = {$sender_id}) ORDER BY timestamp ASC";
        $result = $database->query($sql);
        return $result;
    }
}
?>
