<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Modal Example</title>
    <style>
        /* Style the modal (hidden by default) */
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
</head>
<body>

    <!-- Button to open the modal -->
    <button class="open-modal-btn" id="openModalBtn">Open Form</button>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal Content -->
        <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <h2>Enter Your Information</h2>
            <form id="modalForm">
                <input type="text" id="name" placeholder="Enter your name" required><br>
                <input type="email" id="email" placeholder="Enter your email" required><br>
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </div>

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

        // Handle form submission (example)
        document.getElementById('modalForm').onsubmit = function(event) {
            event.preventDefault(); // Prevent form from reloading the page
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;

            // Example logic for form submission (e.g., you can send this data to the server via AJAX)
            alert('Form Submitted! Name: ' + name + ', Email: ' + email);
            modal.style.display = 'none'; // Close the modal after submission
        }
    </script>

</body>
</html>
