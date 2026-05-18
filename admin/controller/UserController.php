<?php
session_start();

// Security check
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: AuthController.php");
    exit();
}

require_once __DIR__ . '/../model/UserModel.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $users = getAllUsers();
        include __DIR__ . '/../view/user_manage.php';
        break;

    case 'add_form':
        include __DIR__ . '/../view/user_add.php';
        break;

    case 'add_submit':
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $role = $_POST['role'];
        
        if (!empty($name) && !empty($email) && !empty($password) && !empty($role)) {
            createUser($name, $email, $password, $role);
        }
        header("Location: UserController.php?action=list");
        exit();
        break;

    case 'toggle_status':
        $id = $_POST['user_id'];
        $current_status = $_POST['current_status'];
        $new_status = ($current_status == 1) ? 0 : 1; 
        
        updateUserStatus($id, $new_status);
        header("Location: UserController.php?action=list");
        exit();
        break;

    case 'change_role':
        $id = $_POST['user_id'];
        $new_role = $_POST['new_role'];
        
        updateUserRole($id, $new_role);
        header("Location: UserController.php?action=list");
        exit();
        break;
        
    case 'ajax_search':
        $keyword = trim($_GET['keyword'] ?? '');
        
        if (empty($keyword)) {
            $users = getAllUsers();
        } else {
            $users = searchUsers($keyword);
        }
        
        header('Content-Type: application/json');
        echo json_encode($users);
        exit();
        break;

    default:
        header("Location: UserController.php?action=list");
        exit();
}
?>