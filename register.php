<html>
    <head>
        <title>Register Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
        include("includes/initialize.php");
        if(isset($_POST['submit'])){
            $name = $_POST['name'];
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];  // Added confirm password field
            
            // Check if passwords match
            if ($password !== $confirm_password) {
                echo alertBox("Passwords do not match!", "registration.php");
            } else {
                // Proceed with registration if passwords match
                if($name){
                    User::registrationsave($name,$email,$username,$password);
                    echo alertBox("User Registration Successful!!!","login.php");
                } else {
                    echo alertBox("Registration Unsuccessful!","registration.php");
                }
            }
        }
        ?>
        
        <div class="card" style="width: 28rem; margin: 50px auto; background-color: #a9cce3; border-radius: 8px;">
            <div class="card-header" style="color:black; font-weight:bold; font-size:20px; text-align:center;">
                Chat With Me (Register Here)
            </div>
            <div class="card-body" style="margin-top: 20px;">
                <form id="registerForm" method="post">
                    <label style="color:black;">Fullname:</label> 
                    <input name="name" id="name" class="form-control" placeholder="name" required>
                    
                    <label style="color:black;">Email:</label> 
                    <input name="email" id="email" class="form-control" placeholder="email" required>
                    
                    <label style="color:black;">Username:</label> 
                    <input name="username" id="username" class="form-control" placeholder="Username" required>
                    
                    <label style="color:black;">Password:</label> 
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    
                    <label style="color:black;">Confirm Password:</label> 
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required><br>
                    
                    <!-- Live validation message -->
                    <span id="passwordMatchMessage" style="color: red; font-size: 14px; display: none;">Passwords do not match!</span>
            </div>
            
            <div class="card-footer" style="text-align:center;">
                <button type="submit" name="submit" class="btn btn-success" style="border-radius: 7px;">SignUp</button>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <!-- JavaScript to validate passwords match in real-time -->
        <script>
            // Get the password and confirm password fields
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('confirm_password');
            const passwordMatchMessage = document.getElementById('passwordMatchMessage');

            // Listen for input event on the confirm password field
            confirmPasswordField.addEventListener('input', function() {
                // Compare password and confirm password
                if (passwordField.value !== confirmPasswordField.value) {
                    // If passwords do not match, show the message
                    passwordMatchMessage.style.display = 'inline';
                    confirmPasswordField.style.borderColor = 'red'; // Optional: style the input field
                } else {
                    // If passwords match, hide the message
                    passwordMatchMessage.style.display = 'none';
                    confirmPasswordField.style.borderColor = ''; // Reset input field style
                }
            });
        </script>
    </body>
</html>
