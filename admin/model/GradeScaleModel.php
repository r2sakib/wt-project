<?php
require_once __DIR__ . '/db.php';

function getAllGradeScales() {
    $conn = getConnection();
    $sql = "SELECT * FROM grading_scales ORDER BY min_mark DESC";
    $result = mysqli_query($conn, $sql);
    
    $scales = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $scales[] = $row;
    }
    mysqli_close($conn);
    return $scales;
}

function getGradeScaleById($id) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM grading_scales WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $scale = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $scale;
}

function addGradeScale($min_mark, $max_mark, $letter_grade, $gpa_point) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO grading_scales (min_mark, max_mark, letter_grade, gpa_point) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ddsd", $min_mark, $max_mark, $letter_grade, $gpa_point);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

function updateGradeScale($id, $min_mark, $max_mark, $letter_grade, $gpa_point) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE grading_scales SET min_mark=?, max_mark=?, letter_grade=?, gpa_point=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ddsdi", $min_mark, $max_mark, $letter_grade, $gpa_point, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

function deleteGradeScale($id) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "DELETE FROM grading_scales WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}
?>