<?php
require_once __DIR__ . '/db.php';

function getAllAnnouncements() {
    $conn = getConnection();
    $sql = "SELECT a.*, u.name as author_name 
            FROM system_announcements a 
            LEFT JOIN users u ON a.author_id = u.id 
            ORDER BY a.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $announcements = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $announcements[] = $row;
    }
    mysqli_close($conn);
    return $announcements;
}

function addAnnouncement($author_id, $scope, $title, $body) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO system_announcements (author_id, scope, title, body) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isss", $author_id, $scope, $title, $body);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

function deleteAnnouncement($id) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "DELETE FROM system_announcements WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}
?>