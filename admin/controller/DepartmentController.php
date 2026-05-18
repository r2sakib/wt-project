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
        include __DIR__ . '/../view/dept_manage.php';
        break;

    case 'delete':
        $id = $_POST['dept_id'];
        deleteDepartment($id);
        header("Location: DepartmentController.php?action=list");
        exit();
        break;
    
    case 'add_form':
        include __DIR__ . '/../view/dept_add.php';
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

    case 'edit_form':
        $dept_id = $_GET['dept_id'];
        $department = getDepartmentById($dept_id);
        $eligible_heads = getEligibleHeads(); // Fetch users to populate the dropdown
        include __DIR__ . '/../view/dept_edit.php';
        break;

    case 'edit_submit':
        $id = $_POST['dept_id'];
        $name = trim($_POST['name']);
        $code = trim($_POST['code']);
        $head_id = $_POST['head_id'];
        $description = trim($_POST['description']);
        
        if (!empty($name) && !empty($code)) {
            updateDepartment($id, $name, $code, $head_id, $description);
        }
        header("Location: DepartmentController.php?action=list");
        exit();
        break;
    
    default:
        header("Location: DepartmentController.php?action=list");
        exit();
}
?>