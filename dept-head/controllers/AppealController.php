<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head') {
    exit("Unauthorized access.");
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Appeal.php';

$department_id = null;
if (isset($conn)) {
    $department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!$department_id) {
        $_SESSION['error_msg'] = "You are not assigned to any department yet.";
        header("Location: ../index.php?page=appeals");
        exit();
    }

    $action = $_POST['action'];
    $appeal_id = (int)$_POST['appeal_id'];

    if ($action === 'approve') {
        if (updateAppealStatus($conn, $appeal_id, 'approved', $department_id)) {
            $_SESSION['success_msg'] = "Grade appeal approved successfully.";
        } else {
            $_SESSION['error_msg'] = "Error approving grade appeal.";
        }
    } elseif ($action === 'reject') {
        if (updateAppealStatus($conn, $appeal_id, 'rejected', $department_id)) {
            $_SESSION['success_msg'] = "Grade appeal rejected successfully.";
        } else {
            $_SESSION['error_msg'] = "Error rejecting grade appeal.";
        }
    }
    header("Location: ../index.php?page=appeals");
    exit();
}

$appeals = [];
if (isset($conn) && $department_id) {
    $appeals = getAppealsByDepartment($conn, $department_id);
}
?>