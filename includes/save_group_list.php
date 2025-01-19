<?php
class GroupMembers {
    public static function createGroup($group_name, $user_ids) {
        global $database;  // Assuming $database is your PDO connection

        // Prepare the SQL query to insert a new group
        $sql = "INSERT INTO groups (group_name) VALUES (:group_name)";
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':group_name', $group_name, PDO::PARAM_STR);
        $stmt->execute();

        // Get the last inserted group_id
        $group_id = $database->lastInsertId(); 
        $placeholders = [];
        $values = [];
        foreach ($user_ids as $user_id) {
            $placeholders[] = "(?, ?)";  // Placeholder for each group_id and user_id
            $values[] = $group_id;       // Add the group_id for each user
            $values[] = $user_id;        // Add the user_id
        }
        $sql = "INSERT INTO group_members (group_id, user_id) VALUES " . implode(", ", $placeholders);
        $stmt = $database->prepare($sql);
        $stmt->execute($values);

        return true;
    }
    public static function getGroup() {
        global $database;
        
        // Prepare SQL to fetch group data with members
        $sql = "SELECT g1.group_id, g1.group_name, GROUP_CONCAT(g2.user_id) AS member_ids 
                FROM groups g1
                LEFT JOIN group_members g2 ON g1.group_id = g2.group_id
                GROUP BY g1.group_id";
        
        // Prepare and execute the query
        $stmt = $database->prepare($sql);
        $stmt->execute();
        
        // Fetch results as an associative array
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $groups;
    }
    
}
