<?php
session_start();

require_once '../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = trim($_POST['loginEmailInput']);
    $password = trim($_POST['loginPassInput']);

    if (isset($conn)) {
        $query = "SELECT id, name, password_hash, role FROM users WHERE email = ? AND is_active = TRUE LIMIT 1";
        $stmt = $conn->prepare($query);
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password_hash'])) {
                
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                
                header("Location: ../index.php");
                exit();
            }
        }

        $_SESSION['login_error'] = "Invalid institutional email or password.";
        header("Location: ../index.php");
        exit();

        $stmt->close();
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