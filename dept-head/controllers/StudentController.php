<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head') {
    exit("Unauthorized access.");
}
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Student.php';

$department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $student_id = (int)$_POST['student_id'];

    if ($action === 'change_status') {
        $status = $_POST['academic_status']; // good_standing, probation, or dismissed
        if (updateStudentStatus($conn, $student_id, $status, $department_id)) {
            $_SESSION['success_msg'] = "Student academic standing revised successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to update academic status controls.";
        }
    }

    if ($action === 'add_note') {
        $note = trim($_POST['note_content']);
        if (!empty($note)) {
            if (addAdvisorNote($conn, $student_id, $_SESSION['user_id'], $note)) {
                $_SESSION['success_msg'] = "Private advisor note logged chronologically.";
            } else {
                $_SESSION['error_msg'] = "Failed to store evaluation note text.";
            }
        } else {
            $_SESSION['error_msg'] = "Note content cannot be empty.";
        }
    }

    header("Location: ../index.php?page=students");
    exit();
}

$students = [];
if ($department_id) {
    $students = getStudentsByDepartment($conn, $department_id);
}
?>