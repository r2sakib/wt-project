<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollment_id = intval($_POST['id']);
    $ct = floatval($_POST['ct']);
    $mid = floatval($_POST['mid']);
    $final = floatval($_POST['final']);
    $attn = floatval($_POST['attn']);

    $total = $ct + $mid + $final;
    $grade = "F";
    $gpa = 0.00;

    // Secure database lookup for dynamic scaling borders matching your matrix schema bounds
    $scale_stmt = $conn->prepare("SELECT letter_grade, gpa_point FROM grading_scales WHERE ? BETWEEN min_mark AND max_mark");
    $scale_stmt->bind_param("d", $total);
    $scale_stmt->execute();
    $scale_result = $scale_stmt->get_result()->fetch_assoc();

    if ($scale_result) {
        $grade = $scale_result['letter_grade'];
        $gpa = $scale_result['gpa_point'];
    }
    $scale_stmt->close();

    $stmt = $conn->prepare("UPDATE grade_entries SET ct_mark = ?, mid_mark = ?, final_mark = ?, attendance_pct = ?, total_mark = ?, letter_grade = ?, gpa_point = ? WHERE enrollment_id = ? AND is_published = 0");
    $stmt->bind_param("ddddssdi", $ct, $mid, $final, $attn, $total, $grade, $gpa, $enrollment_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "total" => $total, "grade" => $grade, "gpa" => $gpa]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database serialization engine error or row lock failure"]);
    }
    $stmt->close();
}
?>
