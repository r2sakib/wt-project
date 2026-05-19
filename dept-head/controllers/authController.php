<?php
session_start();
require_once '../config/Database.php';
require_once '../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['loginEmailInput']);
    $password = trim($_POST['loginPassInput']);

    if (isset($conn)) {
        $user = getUserByEmail($conn, $email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: ../index.php");
            exit();
        }

        $_SESSION['login_error'] = "Invalid institutional email or password.";
        header("Location: ../index.php");
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>