<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head') {
    exit("Unauthorized access.");
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Appeal.php';
require_once __DIR__ . '/../models/Student.php';

$department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $appeal_id = (int)$_POST['appeal_id'];
    $head_note = trim($_POST['head_note']);
    $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

    if (empty($head_note)) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => "Please provide an accompanying note or instruction reason."]);
            exit();
        }
        $_SESSION['error_msg'] = "Please provide an accompanying note or instruction reason.";
        header("Location: ../index.php?page=appeals");
        exit();
    }

    $success = false;
    if ($action === 'approve') {
        if (processAppealDecision($conn, $appeal_id, 'approved', $head_note, $department_id)) {
            $_SESSION['success_msg'] = "Appeal approved. Sent back to faculty for mark revision.";
            $success = true;
        } else {
            $_SESSION['error_msg'] = "Error updating appeal status.";
        }
    } elseif ($action === 'reject') {
        if (processAppealDecision($conn, $appeal_id, 'rejected', $head_note, $department_id)) {
            $_SESSION['success_msg'] = "Appeal rejected with written notification logged.";
            $success = true;
        } else {
            $_SESSION['error_msg'] = "Error updating appeal status.";
        }
    } 

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit();
    }

    header("Location: ../index.php?page=appeals");
    exit();
}

$appeals = [];
if ($department_id) {
    $appeals = getAppealsByDepartment($conn, $department_id);
}
?>