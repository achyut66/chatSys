<?php
// Function to establish database connection
function checkDatabaseConnection() {
    global $database;  // Declare $database as global to use it in this function
    
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "chatme";

    try {
        // Create a PDO connection and assign it to the global $database variable
        $database = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Set the PDO error mode to exception
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;  // Exit if the connection fails
    }
}
?>
