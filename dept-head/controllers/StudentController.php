<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head') {
    exit("Unauthorized access.");
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Student.php';

$department_id = null;
if (isset($conn)) {
    $department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);
}

$students = [];
if (isset($conn) && $department_id) {
    $students = getStudentsByDepartment($conn, $department_id);
}
?>