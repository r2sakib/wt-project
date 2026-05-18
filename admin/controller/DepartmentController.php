<?php
session_start();

// Security check
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: AuthController.php");
    exit();
}

require_once __DIR__ . '/../model/DepartmentModel.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $departments = getAllDepartments();
        include __DIR__ . '/../view/manage_departments.php';
        break;

    case 'delete':
        $id = $_POST['dept_id'];
        deleteDepartment($id);
        header("Location: DepartmentController.php?action=list");
        exit();
        break;
        
    
    default:
        header("Location: DepartmentController.php?action=list");
        exit();
}
?>