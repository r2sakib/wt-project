<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head') {
    exit("Unauthorized access.");
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Program.php';

$department_id = null;
if (isset($conn)) {
    $department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if (!$department_id) {
        $_SESSION['error_msg'] = "You are not assigned to any department yet.";
        header("Location: ../index.php");
        exit();
    }

    $action = $_POST['action'];

    if ($action === 'add') {
        $name = trim($_POST['name']);
        $code = strtoupper(trim($_POST['code']));
        $credits = (int)$_POST['total_credit_hours'];
        $duration = (int)$_POST['duration_years'];
        $description = trim($_POST['description']);

        if (addProgram($conn, $department_id, $name, $code, $credits, $duration, $description)) {
            $_SESSION['success_msg'] = "Program '$name' added successfully.";
        } else {
            $_SESSION['error_msg'] = "Database Error: Could not add program. Check if the code '$code' already exists.";
        }
        
        header("Location: ../index.php?page=programs");
        exit();
    } 
    
    elseif ($action === 'edit') {
        $id = (int)$_POST['program_id'];
        $name = trim($_POST['name']);
        $code = strtoupper(trim($_POST['code']));
        $credits = (int)$_POST['total_credit_hours'];
        $duration = (int)$_POST['duration_years'];
        $description = trim($_POST['description']);

        if (updateProgram($conn, $id, $department_id, $name, $code, $credits, $duration, $description)) {
            $_SESSION['success_msg'] = "Program updated successfully.";
        } else {
            $_SESSION['error_msg'] = "Error updating program.";
        }
        
        header("Location: ../index.php?page=programs");
        exit();
    }
    
    elseif ($action === 'delete') {
        $id = (int)$_POST['program_id'];
        
        if (deleteProgram($conn, $id, $department_id)) {
            $_SESSION['success_msg'] = "Program deleted successfully.";
        } else {
            $_SESSION['error_msg'] = "Cannot delete this program. It may have active students or courses attached to it.";
        }
        
        header("Location: ../index.php?page=programs");
        exit();
    }
}

$programs = [];
if (isset($conn) && $department_id) {
    $programs = getProgramsByDepartment($conn, $department_id);
}
?>