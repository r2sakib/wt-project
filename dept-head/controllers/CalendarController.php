<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head') {
    exit("Unauthorized access.");
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Calendar.php';

$department_id = null;
if (isset($conn)) {
    $department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);
}

$events = [];
if (isset($conn) && $department_id) {
    $events = getEventsForDepartmentHead($conn, $department_id);
}
?>