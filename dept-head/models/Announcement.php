<?php
function getDepartmentIdByHead($conn, $head_id) {
    $department_id = null;
    $stmt = $conn->prepare("SELECT id FROM departments WHERE head_id = ? LIMIT 1");
    $stmt->bind_param("i", $head_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $department_id = $row['id'];
    }
    $stmt->close();
    return $department_id;
}

function getAnnouncementsByDepartment($conn, $department_id) {
    $announcements = [];
    $query = "SELECT a.*, u.name AS author_name 
              FROM system_announcements a
              JOIN users u ON a.author_id = u.id
              WHERE a.department_id = ? OR a.scope = 'all'
              ORDER BY a.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
    $stmt->close();
    return $announcements;
}

function addAnnouncement($conn, $department_id, $author_id, $scope, $title, $body) {
    $stmt = $conn->prepare("INSERT INTO system_announcements (department_id, author_id, scope, title, body, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iisss", $department_id, $author_id, $scope, $title, $body);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function deleteAnnouncement($conn, $id, $department_id) {
    $stmt = $conn->prepare("DELETE FROM system_announcements WHERE id = ? AND department_id = ?");
    $stmt->bind_param("ii", $id, $department_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}
?>