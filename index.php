<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .container {
            background-color: #5d6d7e;
            border-radius: 5px;
            padding: 20px;
        }

        .user-container {
            position: relative;
            display: inline-block;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #ddd;
            object-fit: cover;
        }

        .online-indicator {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 15px;
            height: 15px;
            background-color: green;
            border-radius: 50%;
            border: 2px solid white;
        }

        /* Chat window styles */
        #chatWindow {
            background-color: #f5f5f5;
            height: 300px;
            margin-top: 10px;
            overflow-y: scroll;
            padding: 10px;
            border-radius: 5px;
        }

        #messageInput {
            width: 90%;
            margin-top: 5px;
            padding: 10px;
        }

        /* Send button positioning */
        .send-btn {
            margin-top: -46px;
            padding: 10px;
            margin-left: 90%;
        }

        .username:hover {
            border-radius: 2px;
            background-color: red; /* Color on hover */
        }

        /* notification */
        .notification-bubble {
    position: absolute;
    top: -5px;
    left: 25px; /* Adjust this based on the positioning of your username */
    width: 20px;
    height: 20px;
    background-color: red;
    color: white;
    border-radius: 50%;
    font-size: 12px;
    text-align: center;
    line-height: 20px;
}


    </style>
</head>

<?php
include("includes/initialize.php");
$success_message = get_success_message();
$user_info = User::getAllUsers();
$username = User::find_by_id($_SESSION['user_id']);
?>

<body>
    <div class="col-md-12">
        <div class="container">
            <div class="row">
                <div class="text-center">
                    <!-- Display success message -->
                    <?php if ($success_message): ?>
                    <div class="alert alert-success" role="alert">
                        <?php 
                            echo $success_message;
                            clear_success_message(); // Clear the message after displaying
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <div class="text-left" style="font-weight:bold;font-size:22px;color:white;">
                        <?php echo 'LoggedIn As : '. $username; ?>
                    </div>
                    <div class="text-center" style="color:white; font-weight:bold; font-size:20px;">Users List</div>
                    <hr style="border:2px solid white;">
                    <div class="list-group">
                    <?php foreach ($user_info as $key => $ui): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2" style="background-color:darkgray;border-radius:5px;">
                        <div class="user-info">
                            <span class="username" data-user-id="<?= $ui['id']; ?>" style="color:white; cursor:pointer;margin-left:8px;">
                                <?= $ui['username']; ?>
                            </span>
                        </div>
                        <div class="user-container">
                            <img src="https://via.placeholder.com/150" alt="User Avatar" class="user-avatar">
                            <div class="online-indicator"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                </div>

                <div class="col-md-6">
                    <form id="chatForm">
                        <div class="text-center" style="color:white; font-weight:bold; font-size:20px;">Chat Window
                            <span style="margin-left:500px;">
                                <div class="text-right" style="margin-top:-33px;margin-left:550px;">
                                    <a href="logout.php" class="btn btn-danger">LogOut</a>
                                </div>
                            </span>
                        </div>
                        <input type="hidden" name="sender_id" id="sender_id" value="<?=$_SESSION['user_id']?>">
                        <input type="hidden" name="receiver_id" id="receiver_id">
                        <div id="chatheader"></div>
                        <div id="chatWindow"></div>
                        <input type="text" name="message" id="messageInput" class="form-control" placeholder="Type your message...">
                        <button type="submit" id="sendMessage" style="margin-top: -75px;;" class="btn btn-primary send-btn">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            var conn = new WebSocket('ws://localhost:8080'); // WebSocket connection
            var messageInput = document.getElementById('messageInput');
            var chatWindow = document.getElementById('chatWindow');
            var selectedUser = null;

            // When the WebSocket connection opens
            conn.onopen = function() {
                console.log('Connected to the WebSocket server');
            };

            // When a message is received from WebSocket
            conn.onmessage = function(event) {
                var messageData = JSON.parse(event.data); // Parse the incoming WebSocket message
                var messageElement = document.createElement('div');  // Create a new div to hold the message
            $.ajax({
                type: "GET",
                url: "getusername.php",  // Endpoint to get the username by sender_id
                data: { sender_id: messageData.sender_id },  // Pass sender_id as a GET parameter
                success: function(data) {
                    console.log('AJAX Response:', data.username);  // Check the response format
                    if (data.username) {
                        var senderName = data.username;
                        if (messageData.message) {
                            messageElement.innerHTML = `<strong>${senderName}:</strong> ${messageData.message}`;
                            if (messageData.sender_id == $("#sender_id").val()) {
                                messageElement.classList.add('message-sender'); // Sender style
                                chatWindow.appendChild(messageElement); // Append sender's message
                                messageElement.style.backgroundColor = '#a9cce3';  // Green background for sender
                                messageElement.style.textAlign = 'left';  // Align sender's messages to the right
                                messageElement.style.borderRadius = '3px';  // Align sender's messages to the right
                            } else {
                                messageElement.classList.add('message-receiver'); // Receiver style
                                chatWindow.appendChild(messageElement); // Append receiver's message
                                messageElement.style.backgroundColor = '#95a5a6';  // Red background for receiver
                                messageElement.style.marginLeft  = '300px';  // Align receiver's messages to the left
                                messageElement.style.borderRadius = '3px';  // Align sender's messages to the right
                            }
                            // chatWindow.appendChild(messageElement);
                            chatWindow.scrollTop = chatWindow.scrollHeight; // Scroll to bottom of chat
                        } else {
                            messageElement.innerHTML = "Error: message data is incomplete";
                        }
                    } else {
                        console.error('User not found:', data.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });


            };

            $('.username').on('click', function(event) {
                event.preventDefault();
                selectedUser = $(this).data('user-id'); // Get the user ID from the clicked element
                var selectedUsername = $(this).text(); // Get the username (text) of the clicked element

                $('#chatheader').empty();  // Clear previous chat header
                $('#chatheader').append('<div style="color:white;font-weight:bold;font-size:22px;">Chatting with ' + selectedUsername + '......</div><hr style="2px solid black;">');
                $("#receiver_id").val(selectedUser);
                // get notification 
                var loggedInUserId = $("#sender_id").val();  // Logged-in user ID from the hidden input
    
    // Loop through all the users and check unseen messages for the logged-in user
    $('.username').each(function() {
        var selectedUser = $(this).data('user-id');  // Get the user_id from the data attribute
        
        // Only check unseen messages for other users (not the logged-in user)
        if (selectedUser != loggedInUserId) {
            $.ajax({
                type: "POST",
                url: "get_unseen_messages.php",  // Endpoint to get unseen message count
                data: { sender_id: selectedUser, receiver_id: loggedInUserId },  // Check unseen messages from `selectedUser` to `loggedInUserId`
                success: function(response) {
                    var data = JSON.parse(response);  // Parse the JSON response
                    if (data.unseen_count > 0) {
                        // If there are unseen messages, show a notification bubble next to the username
                        var notificationBubble = $('<div class="notification-bubble"></div>');
                        notificationBubble.text(data.unseen_count);  // Set the count of unseen messages
                        $(".username[data-user-id='" + selectedUser + "']").after(notificationBubble);  // Append the notification bubble next to the sender's username
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching unseen message count:", error);
                }
            });
        }
    });
                // Fetch chat history
            $.ajax({
            type: "POST",
            url: "mark_messages_as_seen.php", // Endpoint to mark messages as seen
            data: {
            sender_id: $("#sender_id").val(),
            receiver_id: selectedUser
            },
            success: function(response) {
                // Fetch chat history
                $.ajax({
                    type: "POST",
                    url: "fetch_chat_history.php",
                    data: {
                        sender_id: $("#sender_id").val(),
                        receiver_id: selectedUser
                    },
                    success: function(response) {
                        $("#chatWindow").html(response); // Display chat history
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching chat history:", error);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Error marking messages as seen:", error);
            }
        });
            });

            // When the chat form is submitted
            $('#chatForm').on('submit', function(event) {
                event.preventDefault(); // Prevent form from reloading the page

                var message = messageInput.value.trim();
                var receiverId = $("#receiver_id").val();
                var senderId = $("#sender_id").val();

                // Ensure message is not empty and receiver is selected
                if (message !== '' && receiverId) {
                    var messageData = {
                        sender_id: senderId,
                        receiver_id: receiverId,
                        message: message
                    };
                    conn.send(JSON.stringify(messageData)); // Send via WebSocket

                    // Display message locally after sending
                    var messageElement = document.createElement('div');
                    messageElement.innerHTML = `You: ${message}`;  // Correct usage of template literal
                    messageElement.classList.add('message-sender');
                    chatWindow.appendChild(messageElement);
                    chatWindow.scrollTop = chatWindow.scrollHeight; // Scroll to the bottom


                    // Save message to the database via AJAX
                    $.ajax({
                        type: "POST",
                        url: "save_message.php",  // PHP script to save message
                        data: { sender_id: senderId, receiver_id: receiverId, message: message },
                        success: function(response) {
                            messageInput.value = '';  // Clear input field
                        },
                        error: function(xhr, status, error) {
                            console.error("Error saving message:", error);
                        }
                    });
                } else {
                    console.error("Message or receiver is missing");
                }
            });
        });
    </script>
</body>

</html>
