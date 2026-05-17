<?php
require_once 'db.php';

function verifyAdminLogin($email, $password) {
    $conn = getConnection();
    
    // Prepare statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT id, name, password_hash FROM users WHERE email = ? AND role = 'admin' AND is_active = 1");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password_hash'])) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $row; 
        }
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return false; 
}
?>