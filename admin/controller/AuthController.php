<?php
session_start();
require_once '../model/AuthModel.php';

// Check if form was submitted and an action was provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    
    $action = $_POST['action'];

    switch ($action) {
        case 'login':
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Basic validation
            if (empty($email) || empty($password)) {
                $_SESSION['login_error'] = "Please fill in all fields.";
                header("Location: ../view/login.php");
                exit();
            }

            $admin = verifyAdminLogin($email, $password);

            if ($admin) {
                // Login successful
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['role'] = 'admin';
                header("Location: DashboardController.php");
                exit();
            } else {
                // Login failed
                $_SESSION['login_error'] = "Invalid email, password, or insufficient permissions.";
                header("Location: ../view/login.php");
                exit();
            }
            break;

        case 'logout':
            // Destroy the session variables and the session itself
            session_unset();
            session_destroy(); 
            header("Location: ../view/login.php");
            exit();
            break;

        default:
            // Invalid action fallback
            header("Location: ../view/login.php");
            exit();
    }
} else {
    // Redirect if accessed directly without a POST request
    header("Location: ../view/login.php");
    exit();
}
?>