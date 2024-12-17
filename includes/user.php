<?php
class User {
    protected static $table_name = "user";
    protected static $db_fields = array('id', 'name', 'email', 'username', 'password','created_at','updated_at');

    public $id;
    public $name;
    public $email;
    public $username;
    public $password;
    public $created_at;
    public $updated_at;

    public static function registrationsave($name, $email, $username, $password) {
        global $database;
    
        try {
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);
            $created_at = date("Y-m-d H:i:s");
            $updated_at = date("Y-m-d H:i:s");
            $sql = "INSERT INTO " . self::$table_name . " (name, email, username, password, created_at, updated_at) 
                    VALUES (:name, :email, :username, :password, :created_at, :updated_at)";
            $stmt = $database->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password_hashed, PDO::PARAM_STR);
            $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
            $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error during registration: " . $e->getMessage());
            return false;
        }
    }
    public static function getAllUsers() {
        global $database; 
        try {
            $query = "SELECT * FROM user";
            if ($database) {
                $stmt = $database->prepare($query);  // Using the global $database connection
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $users;  // Return the array of user data
            } else {
                throw new Exception("Database connection is not initialized.");
            }
        } catch (PDOException $e) {
            return "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    public static function find_by_username($username) {
        global $database;
        $query = $database->prepare("SELECT * FROM user WHERE username = ?");
        $query->execute([$username]);
        return $query->fetch(PDO::FETCH_ASSOC); // Returns the user data if found, else false
    }
    public static function find_by_id($id) {
        global $database;
        $query = $database->prepare("SELECT name FROM user WHERE id = :id"); // Only select the 'name' field
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchColumn();  // Fetch the single column value ('name')
        return $result;  // Return the name (or null if no user found)
    }
    public static function find_by_id_json($id) {
        global $database;
        // Select all necessary fields, e.g., 'username', 'name', etc.
        $query = $database->prepare("SELECT username, name FROM user WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        
        // Fetch the result as an associative array
        $result = $query->fetch(PDO::FETCH_ASSOC); // This fetches the row as an associative array
        
        return $result;  // Return the associative array (or null if no user found)
    }
    
    
}
?>
