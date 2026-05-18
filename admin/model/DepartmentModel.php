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
?>