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

function getCoursePerformanceReport($conn, $department_id) {
    $report = [];
    $query = "SELECT c.code, c.title, c.max_seats,
                     COALESCE(AVG(s.cgpa), 0.0) AS calculated_avg 
              FROM courses c
              JOIN programs p ON c.program_id = p.id
              LEFT JOIN students s ON s.program_id = p.id
              WHERE p.department_id = ?
              GROUP BY c.id 
              ORDER BY c.code ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $report[] = $row;
    }
    $stmt->close();
    return $report;
}

function getCGPADistributionReport($conn, $department_id) {
    $query = "SELECT 
                COUNT(CASE WHEN s.cgpa >= 3.75 THEN 1 END) AS excellent,
                COUNT(CASE WHEN s.cgpa >= 3.50 AND s.cgpa < 3.75 THEN 1 END) AS very_good,
                COUNT(CASE WHEN s.cgpa >= 3.00 AND s.cgpa < 3.50 THEN 1 END) AS good,
                COUNT(CASE WHEN s.cgpa >= 2.20 AND s.cgpa < 3.00 THEN 1 END) AS passing,
                COUNT(CASE WHEN s.cgpa < 2.20 THEN 1 END) AS probation
              FROM students s
              JOIN programs p ON s.program_id = p.id
              WHERE p.department_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result;
}

function getFacultyWorkloadReport($conn, $department_id) {
    $report = [];
    $query = "SELECT u.name AS faculty_name, 
                     COUNT(c.id) AS total_courses, 
                     COALESCE(SUM(c.credit_hours), 0) AS total_credits
              FROM faculty f
              JOIN users u ON f.user_id = u.id
              LEFT JOIN courses c ON f.id = c.faculty_id
              WHERE f.department_id = ?
              GROUP BY f.id
              ORDER BY total_courses DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $report[] = $row;
    }
    $stmt->close();
    return $report;
}
?>