<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
    .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
            justify-content: center; /* Center modal */
            align-items: center; /* Center modal */
        }

        /* Modal content */
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            text-align: center;
        }

        /* Close button */
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 25px;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Input field styling */
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Submit button styling */
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        /* Open modal button styling */
        .open-modal-btn {
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .open-modal-btn:hover {
            background-color: #007bb5;
        }
</style>
<?php
include("includes/initialize.php");
$success_message = get_success_message();
$user_info = User::getAllUsers();
$username = User::find_by_id($_SESSION['user_id']);
$groups = GroupMembers::getGroup();
// print_r($groups);
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
                        <?php echo $username.'<span style=""><img src="image/im.jpg" style="height:50px;width:50px;border-radius:50px;margin-left:10px;"></span>'; ?>
                    </div>
                    <div class="text-left" style="color:white; font-weight:bold; font-size:20px;">Users List<span>
                        <div class="text-left" style="margin-left: 507px;margin-top: -30px;"><button class="btn btn-primary" id="openModalBtn">Create Group</button></div>
                    </span></div>
                    <hr style="border:2px solid white;">
                    <div class="list-group">
                    <table class="table table-borderless userslist">
                        
                    <?php foreach ($user_info as $key => $ui): ?>
                        <tr>
                        <td class="username" data-user-id="<?= $ui['id']; ?>" style="color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= $ui['name'] ?>
                        </td>
                        <td class="image" style="text-align: right;">
                            <img src="image/jp.jpg" class="imagefile" style="width:35px;height:35px;border-radius:50%;">
                        </td>
                    </tr>

                    <?php endforeach; ?>

                    </table>
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
                        <!-- <button onclick="addEmoji()">😊</button> -->
                        <button type="submit" id="sendMessage" style="margin-top: -75px;;" class="btn btn-primary send-btn">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal form -->
    <div id="myModal" class="modal">
        <!-- Modal Content -->
        <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <form id="modalForm createGroupForm" action="create_group.php" method="post">
                <!-- <div class="text-center">Users List</div> -->
                <div class="text-left"><label style="font-weight: bold;">Group Name: </label><input type="text" name="group_name" required></td></div>
                <table class="table table-bordered table responsive groupusers">
                    <?php foreach($user_info as $uif):?>
                    <tbody>
                        <tr>
                            <td><?=$uif['name'].'<img src="image/im.jpg" style="border-radius:50px;height:15px;width:15px;">'?></td>
                            <td><input name="selecteduser[]" type="checkbox" value="<?=$uif['id']?>"></td>
                        </tr>
                    </tbody>
                    <?php endforeach;?>
                    <tfoot></tfoot>
                </table>
                <button type="submit" class="submit-btn" name="submit">Submit</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/chat.js"></script>
    <script>
        // Get modal and buttons
        var modal = document.getElementById('myModal');
        var openModalBtn = document.getElementById('openModalBtn');
        var closeModalBtn = document.getElementById('closeModalBtn');

        // When the user clicks the button, open the modal
        openModalBtn.onclick = function() {
            modal.style.display = 'flex';
        }

        // When the user clicks the close button, close the modal
        closeModalBtn.onclick = function() {
            modal.style.display = 'none';
        }

        // When the user clicks outside the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        $('#createGroupForm').on('submit', function(event) {
        event.preventDefault();  // Prevent the page from reloading
        var formData = $(this).serialize();  // Get the form data

        $.ajax({
            url: 'index.php',  // Submit to the same page
            type: 'POST',
            data: formData,
            success: function(response) {
                // Assuming the response is a JSON object
                try {
                    var jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        $('#message').html('<div class="alert alert-success">' + jsonResponse.message + '</div>');
                    } else {
                    $('#message').html('<div class="alert alert-danger">' + jsonResponse.message + '</div>');
                }
            } catch (e) {
                console.error('Invalid JSON response:', e);
            }
        },
        error: function(xhr, status, error) {
            $('#message').html('<div class="alert alert-danger">An error occurred while creating the group.</div>');
        }
    });
});
    </script>

</body>
</html>
