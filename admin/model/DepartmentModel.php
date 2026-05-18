<?php
require_once __DIR__ . '/db.php';

function getAllDepartments() {
    $conn = getConnection();
    $sql = "SELECT d.id, d.name, d.code, u.name as head_name 
            FROM departments d 
            LEFT JOIN users u ON d.head_id = u.id";
    $result = mysqli_query($conn, $sql);
    
    $departments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $departments[] = $row;
    }
    mysqli_close($conn);
    return $departments;
}

function deleteDepartment($id) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "DELETE FROM departments WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

function addDepartment($name, $code, $description) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO departments (name, code, description) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $name, $code, $description);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

function getDepartmentById($id) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM departments WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dept = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $dept;
}

function updateDepartment($id, $name, $code, $head_id, $description) {
    $conn = getConnection();
    $head_id = !empty($head_id) ? $head_id : NULL; 
    
    $stmt = mysqli_prepare($conn, "UPDATE departments SET name=?, code=?, head_id=?, description=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssisi", $name, $code, $head_id, $description, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}


function getEligibleHeads() {
    $conn = getConnection();
    $sql = "SELECT id, name, email FROM users WHERE role IN ('head', 'faculty') AND is_active = 1";
    $result = mysqli_query($conn, $sql);
    $heads = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $heads[] = $row;
    }
    mysqli_close($conn);
    return $heads;
}

?>