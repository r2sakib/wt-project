<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head') {
    exit("Unauthorized access.");
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Announcement.php';

$department_id = null;
if (isset($conn)) {
    $department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!$department_id) {
        $_SESSION['error_msg'] = "You are not assigned to any department yet.";
        header("Location: ../index.php?page=announcements");
        exit();
    }

    $action = $_POST['action'];

    if ($action === 'add') {
        $title = trim($_POST['title']);
        $body = trim($_POST['body']);
        $scope = 'department'; 

        if (addAnnouncement($conn, $department_id, $_SESSION['user_id'], $scope, $title, $body)) {
            $_SESSION['success_msg'] = "Announcement published successfully.";
        } else {
            $_SESSION['error_msg'] = "Error publishing announcement.";
        }
    } 
    
    elseif ($action === 'delete') {
        $id = (int)$_POST['announcement_id'];

        if (deleteAnnouncement($conn, $id, $department_id)) {
            $_SESSION['success_msg'] = "Announcement removed successfully.";
        } else {
            $_SESSION['error_msg'] = "Error removing announcement.";
        }
    }
    
    header("Location: ../index.php?page=announcements");
    exit();
}

$announcements = [];
if (isset($conn) && $department_id) {
    $announcements = getAnnouncementsByDepartment($conn, $department_id);
}
?>