<?php
// AcademicController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
/*
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}*/
 
require_once(__DIR__ . "/../model/AcademicModel.php");
$academicModel = new AcademicModel();
$user = $_SESSION['user'];
 
$studentData     = $academicModel->getStudentInfo($user['id']);
$enrolledCourses = $academicModel->getEnrolledCourses($user['id']);
 
// Detect if the request came through JavaScript (AJAX)
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// ACTION A: ENROLL IN COURSE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'enroll_course') {
    $courseId = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
    
    $result = $academicModel->enrollCourse($user['id'], $courseId);
 
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
 
    $_SESSION['enroll_msg'] = $result['message'];
    header("Location: ../view/academic.php");
    exit;
}
 
// ACTION B: DROP A COURSE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'drop_course') {
    $courseId = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
 
    $dropResult = $academicModel->dropCourse($user['id'], $courseId);
 
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode($dropResult);
        exit;
    }
 
    $_SESSION['drop_msg'] = $dropResult['message'];
    header("Location: ../view/academic.php");
    exit;
}
 
// ACTION C: LIVE CGPA BOX REFRESH
if (isset($_GET['action']) && $_GET['action'] == 'get_live_cgpa') {
    header('Content-Type: application/json');
    $data = $academicModel->getLiveCGPA($user['id']);
 
    $cgpa     = $data ? floatval($data['live_cgpa']) : 0.00;
    $credits  = $data ? intval($data['total_credits']) : 0;
    
    $standing = "Good Standing";
    if ($cgpa < 2.00 && $credits > 0) {
        $standing = "Probation";
    }
 
    echo json_encode([
        'cgpa'     => number_format($cgpa, 2),
        'credits'  => $credits,
        'standing' => $standing,
    ]);
    exit;
}
 
// ACTION D: LIVE SEARCH COURSE CATALOG
if (isset($_GET['action']) && $_GET['action'] == 'search_courses') {
    $search    = isset($_GET['query']) ? trim($_GET['query']) : '';
    $available = $academicModel->searchAvailableCourses($user['id'], $search);
    
    $rows = '';
    if ($available && $available->num_rows > 0) {
        while ($course = $available->fetch_assoc()) {
            $remaining   = $course['max_seats'] - $course['filled_seats'];
            $seatDisplay = $remaining . " / " . $course['max_seats'];
            $isEnrolled  = intval($course['is_enrolled']) > 0;
 
            // Choose button visual state
            if ($isEnrolled) {
                $actionButton = "<input type='submit' value='Enrolled' disabled>";
            } else if ($remaining <= 0 || $course['status'] === 'closed') {
                $actionButton = "<input type='submit' value='Closed' disabled>";
            } else {
                $actionButton = "<input type='submit' value='Enroll'>";
            }
 
            $rows .= "<tr>
                        <td>" . htmlspecialchars($course['code']) . "</td>
                        <td>" . htmlspecialchars($course['title']) . "</td>
                        <td align='center'>" . htmlspecialchars($course['credit_hours']) . "</td>
                        <td align='center'><b>" . htmlspecialchars($seatDisplay) . "</b></td>
                        <td align='center'>
                            <form method='POST' action='../controller/AcademicController.php' style='margin:0;' class='enroll-form'>
                                <input type='hidden' name='action' value='enroll_course'>
                                <input type='hidden' name='course_id' value='" . htmlspecialchars($course['course_id']) . "'>
                                " . $actionButton . "
                            </form>
                        </td>
                      </tr>";
        }
    } else {
        $rows .= "<tr><td colspan='5' align='center'>No courses found for this semester.</td></tr>";
    }
    echo $rows;
    exit; 
}
 
// ACTION E: LIVE REFRESH ENROLLED COURSES TABLE ROWS
if (isset($_GET['action']) && $_GET['action'] == 'get_enrolled_rows') {
    $result = $academicModel->getEnrolledCourses($user['id']);
    $rows   = '';
 
    if ($result && $result->num_rows > 0) {
        while ($course = $result->fetch_assoc()) {
            $max       = $course['max_seats'] ?? 40;
            $remaining = $course['remaining_seats'] ?? $max;
 
            $rows .= "<tr>
                        <td>" . htmlspecialchars($course['code']) . "</td>
                        <td>" . htmlspecialchars($course['title']) . "</td>
                        <td align='center'>" . htmlspecialchars($course['credit_hours']) . "</td>
                        <td align='center'><b>" . htmlspecialchars($remaining) . " / " . htmlspecialchars($max) . "</b></td>
                        <td align='center'>" . htmlspecialchars(ucfirst($course['status'])) . "</td>
                        <td align='center'>
                            <form method='POST' action='../controller/AcademicController.php' style='margin:0;' class='drop-form'>
                                <input type='hidden' name='action' value='drop_course'>
                                <input type='hidden' name='course_id' value='" . htmlspecialchars($course['course_id']) . "'>
                                <input type='submit' value='Drop Course'>
                            </form>
                        </td>
                      </tr>";
        }
    } else {
        $rows = "<tr><td colspan='6' align='center'>No enrolled courses found for this semester.</td></tr>";
    }
 
    echo $rows;
    exit;
}
?>