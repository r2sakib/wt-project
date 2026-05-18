<?php
require_once __DIR__ . '/db.php';

function getAllUsers() {
    $conn = getConnection();
    $sql = "SELECT id, name, email, role, is_active FROM users ORDER BY role, name";
    $result = mysqli_query($conn, $sql);
    
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    mysqli_close($conn);
    return $users;
}


function createUser($name, $email, $password, $role) {
    $conn = getConnection();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password_hash, role, is_active) VALUES (?, ?, ?, ?, 1)");
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $role);
    $success = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

function updateUserStatus($id, $new_status) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE users SET is_active = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $new_status, $id);
    $success = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

function updateUserRole($id, $new_role) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE users SET role = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $new_role, $id);
    $success = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

function searchUsers($keyword) {
    $conn = getConnection();
    $searchTerm = "%" . $keyword . "%";
    
    $sql = "SELECT * FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY role ASC, name ASC";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $users;
}
?>