<?php
require_once __DIR__ . '/db.php';

function getAllSemesters() {
    $conn = getConnection();
    $sql = "SELECT * FROM semesters ORDER BY start_date DESC";
    $result = mysqli_query($conn, $sql);
    
    $semesters = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $semesters[] = $row;
    }
    mysqli_close($conn);
    return $semesters;
}


function addSemester($name, $start_date, $end_date, $drop_deadline, $grade_submission_deadline) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO semesters (name, start_date, end_date, drop_deadline, grade_submission_deadline) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $name, $start_date, $end_date, $drop_deadline, $grade_submission_deadline);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}
?>