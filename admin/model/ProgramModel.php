<?php
require_once __DIR__ . '/db.php';

function getAllPrograms() {
    $conn = getConnection();
    $sql = "SELECT p.*, d.name as department_name 
            FROM programs p 
            JOIN departments d ON p.department_id = d.id
            ORDER BY d.name, p.name";
    $result = mysqli_query($conn, $sql);
    
    $programs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $programs[] = $row;
    }
    mysqli_close($conn);
    return $programs;
}

function getActiveDepartments() {
    $conn = getConnection();
    $sql = "SELECT id, name, code FROM departments";
    $result = mysqli_query($conn, $sql);
    
    $departments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $departments[] = $row;
    }
    mysqli_close($conn);
    return $departments;
}

function addProgram($department_id, $name, $code, $total_credit_hours, $duration_years, $description) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO programs (department_id, name, code, total_credit_hours, duration_years, description) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issiis", $department_id, $name, $code, $total_credit_hours, $duration_years, $description);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}
?>