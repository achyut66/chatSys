<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    // public function onMessage(ConnectionInterface $from, $msg) {
    //     $numRecv = count($this->clients) - 1;
    //     echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
    //         , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

    //     foreach ($this->clients as $client) {
    //         if ($from !== $client) {
    //             $client->send($msg);
    //         }
    //     }
    // }
    public function onMessage(ConnectionInterface $from, $msg) {
        $messageData = json_decode($msg, true); // Decode the incoming message
        $senderId = $messageData['sender_id'];
        $receiverId = $messageData['receiver_id'];
    
        foreach ($this->clients as $client) {
            // Send the message only if the client is the receiver or the sender
            if ($client !== $from) {
                if (isset($client->resourceId)) {
                    // Assuming you have a way to map `resourceId` to user IDs
                    $clientUserId = $client->resourceId; // Replace with actual mapping logic
                    if ($clientUserId == $receiverId || $clientUserId == $senderId) {
                        $client->send($msg);
                    }
                }
            }
        }
    }
    

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}