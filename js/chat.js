
        $(document).ready(function() {

            var conn = new WebSocket('ws://localhost:8080'); // WebSocket connection
            var messageInput = document.getElementById('messageInput');
            var chatWindow = document.getElementById('chatWindow');
            var selectedUser = null;
            conn.onopen = function() {
                console.log('Connected to the WebSocket server');
            };
            conn.onmessage = function (event) {
                var messageData = JSON.parse(event.data); // Parse the incoming WebSocket message
                var loggedInUserId = $("#sender_id").val(); // Logged-in user ID
                var receiverId = messageData.receiver_id; // Receiver ID in the message
                var senderId = messageData.sender_id; // Sender ID in the message
            $.ajax({
                type: "GET",
                url: "getusername.php",  // Endpoint to get the username by sender_id
                data: { sender_id: messageData.sender_id },  // Pass sender_id as a GET parameter
                success: function(data) {
                    // console.log('AJAX Response:', data.username);  // Check the response format
                    if (data.username) {
                        // var senderName = data.username;
                        if (messageData.message) {
                            // messageElement.innerHTML = `<span style="color:black;><img src="image/blank.jpg" style="width:15px;height:15px;border-radius:50px;"> ${messageData.message}</span>`;
                            // if (messageData.sender_id == $("#sender_id").val()) {
                            //     messageElement.classList.add('message-sender'); // Sender style
                            //     chatWindow.appendChild(messageElement); // Append sender's message
                            //     messageElement.style.textAlign = 'left';  // Align sender's messages to the right
                            //     // messageElement.style.borderRadius = '3px';  // Align sender's messages to the right
                            // } else {
                            //     messageElement.classList.add('message-receiver'); // Receiver style
                            //     chatWindow.appendChild(messageElement); // Append receiver's message
                            //     messageElement.style.marginLeft  = '300px';  // Align receiver's messages to the left
                            // }
                            // chatWindow.appendChild(messageElement);
                            if (loggedInUserId == senderId || loggedInUserId == receiverId) {
                                var messageElement = document.createElement('div'); // Create a new div to hold the message
                                var messageText = document.createElement('span'); // Create a span to hold the message text
                                var profileImage = document.createElement('img');
                                profileImage.src = "image/blank.jpg"; // Set the image source
                                profileImage.style.borderRadius = "50px"; // Style the image
                                profileImage.style.height = "20px";
                                profileImage.style.width = "20px";
                                profileImage.style.marginLeft = "380px";
                                profileImage.style.marginBottom = "-43px";
                                messageText.style.marginLeft = "407px";;

                                if (loggedInUserId == senderId) {
                                    messageElement.classList.add('message-sender');
                                    messageText.classList.add('message-text');
                                } else {
                                    messageElement.classList.add('message-receiver');
                                    messageText.classList.add('message-text');
                                }
                                messageText.textContent = messageData.message;
                                messageElement.appendChild(profileImage);
                                messageElement.appendChild(messageText);
                                document.getElementById('chatWindow').appendChild(messageElement);
                            }
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
                $('#chatheader').append('<div style="color:white;font-weight:bold;font-size:22px;">' + selectedUsername + '<img src="image/im.jpg" style="width:35px;height:35px;border-radius:50px;margin-left:10px;"></div><hr style="2px solid black;">');
                $("#receiver_id").val(selectedUser);
                // get notification 
                
    
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
                    messageElement.innerHTML = `
                        <div class="message sender">
                            <img src="image/man.jpeg" class="user-img" />
                            <span class="message-text">${message}</span>
                        </div>
                    `;

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
            // notification
            $('.username').each(function() {
                var selectedUser = $(this).data('user-id'); // Get the user_id from the data attribute
                console.log("Logged-in User ID:", loggedInUserId);
                console.log("Selected User ID:", selectedUser);
                var loggedInUserId = $("#sender_id").val();  // Logged-in user ID from the hidden input
                // Ensure selectedUser is not the same as the logged-in user
                if (selectedUser != loggedInUserId) {
                    // Send AJAX request to check unseen messages
                    $.ajax({
                        type: "POST",
                        url: "get_unseen_messages.php", // Endpoint to get unseen message count
                        data: { sender_id: selectedUser, receiver_id: loggedInUserId }, // Check unseen messages between selectedUser and loggedInUserId
                        success: function(response) {
                            try {
                                var data = JSON.parse(response); // Parse the JSON response
                                console.log("Unseen messages for User ID", selectedUser, ":", data.unseen_count);
            
                                if (data.unseen_count > 0) {
                                    // Create a notification bubble
                                    var notificationBubble = $('<div class="notification-bubble"></div>');
                                    notificationBubble.text(data.unseen_count); // Set the unseen message count
            
                                    // Append or update the notification bubble next to the selected user's username
                                    var userElement = $(".username[data-user-id='" + selectedUser + "']");
                                    // userElement.next('.notification-bubble').remove(); // Remove any existing notification bubble
                                    userElement.after(notificationBubble); // Add the new notification bubble
                                }
                            } catch (e) {
                                console.error("Error parsing response for User ID", selectedUser, ":", e);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching unseen message count for User ID", selectedUser, ":", error);
                        }
                    });
                }
            });
            // if seen 
            $('.username').on('click', function () {
                var selectedUser = $(this).data('user-id'); // Get the user ID of the selected user
                var loggedInUserId = $("#sender_id").val();  // Logged-in user ID from the hidden input
                if (selectedUser != loggedInUserId) {
                    // Send AJAX request to mark messages as seen
                    $.ajax({
                        type: "POST",
                        url: "mark_messages_as_seen.php", // Endpoint to update message status
                        data: { sender_id: selectedUser, receiver_id: loggedInUserId }, // Sender and receiver IDs
                        success: function (response) {
                            console.log("Messages marked as seen for user ID:", selectedUser);
                            
                            // Remove the notification bubble for the selected user
                            $(".username[data-user-id='" + selectedUser + "']")
                                .next('.notification-bubble')
                                .remove(); // Remove notification bubble
                        },
                        error: function (xhr, status, error) {
                            console.error("Error marking messages as seen:", error);
                        }
                    });
                }
            });
            
        });