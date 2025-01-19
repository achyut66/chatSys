<?php
require_once("includes/initialize.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_name = $_POST['group_name'];
    $selected_users = isset($_POST['selecteduser']) ? $_POST['selecteduser'] : [];
    if (empty($selected_users)) {
        echo "Please select at least one user to create a group.";
        exit;
    }
    $group_id = GroupMembers::createGroup($group_name, $selected_users);
    echo alertBox("Group created Successfully !!","index.php");
}
?>
