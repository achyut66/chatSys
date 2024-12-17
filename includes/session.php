<?php
session_start();
function isLoggedIn() {
    return isset($_SESSION['username']);
}

// Set success message in the session
function set_success_message($message) {
    $_SESSION['success_message'] = $message;
}

// Get the success message from the session
function get_success_message() {
    if (isset($_SESSION['success_message'])) {
        return $_SESSION['success_message'];
    } else {
        return null;
    }
}
function getLoggedInUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}


// Clear the success message after displaying it
function clear_success_message() {
    if (isset($_SESSION['success_message'])) {
        unset($_SESSION['success_message']);
    }
}

function login($username) {
    $_SESSION['username'] = $username;
}

function logout() {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

function alertBox($message, $url) {
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    $url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    echo "<script>
            alert('$message');
            window.location.href = '$url';
          </script>";
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}
?>
