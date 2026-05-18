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
    
    case 'add_form':
        include __DIR__ . '/../view/add_department.php';
        break;

    case 'add_submit':
        $name = trim($_POST['name']);
        $code = trim($_POST['code']);
        $description = trim($_POST['description']);
        
        if (!empty($name) && !empty($code)) {
            addDepartment($name, $code, $description);
        }
        header("Location: DepartmentController.php?action=list");
        exit();
        break;
    
    default:
        header("Location: DepartmentController.php?action=list");
        exit();
}
?>