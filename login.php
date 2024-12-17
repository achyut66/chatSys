<html>
    <head>
        <title>UserLoginDashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php
        include("includes/initialize.php");
        if(isset($_POST['submit'])){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user = User::find_by_username($username); 
            if($user) {
                if(password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['id']; 
                    set_success_message("Login Successful!");
                    header("Location: index.php");
                    exit(); 
                } else {
                    echo "Invalid password!";
                }
            } else {
                echo "<div style='background-color:red;width: 446px;border-radius:8px;color:white;font-size:20px;margin-left: 540px;margin-bottom:-49px;'>Invalid username!</div>";
            }
        }
        ?>
        <div class="card" style="width: 28rem; margin: 50px auto; background-color: #a9cce3; border-radius: 8px;">
            <div class="card-header" style="color:black; font-weight:bold; font-size:20px; text-align:center;">
                Chat With Me
            </div>
            <div class="card-body" style="margin-top: 20px;">
                <form method="post">
                    <label style="color:black;">Username:</label> 
                    <input name="username" id="username" class="form-control" placeholder="Username" required>
                    <label style="color:black;">Password:</label> 
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required><br>
                    <div class="text-left">Dont Have An User ? Create Here <a href="register.php">Register Here</a></div>
            </div>
            
            <div class="card-footer" style="text-align:center;">
                <button type="submit" name="submit" class="btn btn-success" style="border-radius: 7px;">Login</button>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
