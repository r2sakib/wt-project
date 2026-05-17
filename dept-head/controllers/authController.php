<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = htmlspecialchars(trim($_POST['loginEmailInput']));
    $password = trim($_POST['loginPassInput']);

    $valid_email = "fahim@aiub.edu";
    $valid_password = "admin";

    if ($email === $valid_email && $password === $valid_password) {
        $_SESSION['user_id'] = 1;
        $_SESSION['name'] = "Md. Fahim Muhtashim";
        $_SESSION['role'] = "Head of Department";
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid institutional email or password.";
        header("Location: ../index.php");
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: ../index.php");
    exit();
}