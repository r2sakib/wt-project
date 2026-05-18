<?php
require_once __DIR__ . '/db.php';

function getAllEvents() {
    $conn = getConnection();
    $sql = "SELECT ac.*, s.name as semester_name 
            FROM academic_calendar ac 
            LEFT JOIN semesters s ON ac.semester_id = s.id 
            ORDER BY ac.event_date ASC";
    $result = mysqli_query($conn, $sql);
    
    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
    mysqli_close($conn);
    return $events;
}

function getSemestersForCalendar() {
    $conn = getConnection();
    $sql = "SELECT id, name FROM semesters ORDER BY start_date DESC";
    $result = mysqli_query($conn, $sql);
    
    $semesters = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $semesters[] = $row;
    }
    mysqli_close($conn);
    return $semesters;
}

function addEvent($semester_id, $event_name, $event_date, $description, $visible_to) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO academic_calendar (semester_id, event_name, event_date, description, visible_to) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issss", $semester_id, $event_name, $event_date, $description, $visible_to);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

function deleteEvent($id) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "DELETE FROM academic_calendar WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}
?>