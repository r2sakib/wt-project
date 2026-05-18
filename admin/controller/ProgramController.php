<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: AuthController.php");
    exit();
}

require_once __DIR__ . '/../model/ProgramModel.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $programs = getAllPrograms();
        include __DIR__ . '/../view/manage_programs.php';
        break;

    case 'add_form':
        $departments = getActiveDepartments();
        include __DIR__ . '/../view/add_program.php';
        break;

    case 'add_submit':
        $department_id = $_POST['department_id'];
        $name = trim($_POST['name']);
        $code = trim($_POST['code']);
        $total_credit_hours = $_POST['total_credit_hours'];
        $duration_years = $_POST['duration_years'];
        $description = trim($_POST['description']);
        
        if (!empty($department_id) && !empty($name) && !empty($code)) {
            addProgram($department_id, $name, $code, $total_credit_hours, $duration_years, $description);
        }
        header("Location: ProgramController.php?action=list");
        exit();
        break;
    
    default:
        header("Location: ProgramController.php?action=list");
        exit();
}
?>