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

function getProgramById($id) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM programs WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $program = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $program;
}

function updateProgram($id, $department_id, $name, $code, $total_credit_hours, $duration_years, $description) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE programs SET department_id=?, name=?, code=?, total_credit_hours=?, duration_years=?, description=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "issiisi", $department_id, $name, $code, $total_credit_hours, $duration_years, $description, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}
?>