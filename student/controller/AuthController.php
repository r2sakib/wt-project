<?php
ini_set('display_errors', 1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . "/../model/AcademicModel.php");
$academicModel = new AcademicModel();


$emailErr = ""; 
$nameErr = "";
$idErr = "";
$roleErr = "";
$passErr = "";
$cpassErr = "";


$user = $_SESSION['user'] ?? null;
$studentData = null;
$enrolledCourses = [];

if ($user && isset($user['id'])) {
    $studentData     = $academicModel->getStudentInfo($user['id']);
    $enrolledCourses = $academicModel->getEnrolledCourses($user['id']);
}


// regiser logic

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_btn'])) { 
    $name = isset($_POST['name']) ? trim($_POST['name']) : ''; 
    $email = isset($_POST['email']) ? trim($_POST['email']) : ''; 
    $id = isset($_POST['student_id']) ? trim($_POST['student_id']) : ''; 
    $role = isset($_POST['role']) ? trim($_POST['role']) : ''; 
    $password = $_POST['password'] ?? ''; 
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($name)) $nameErr = "Name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $emailErr = "Invalid Email";
    if (empty($id)) $idErr = "Student ID required";
    if (empty($role)) $roleErr = "Select Role";
    if (strlen($password) < 6) $passErr = "Password must be 6 characters";
    if ($password !== $confirm_password) $cpassErr = "Passwords do not match";

    // form validations 
    if (empty($nameErr) && empty($emailErr) && empty($idErr) && empty($roleErr) && empty($passErr) && empty($cpassErr)) {
        
        if ($academicModel->checkDuplicateEmail($email)) {
            $emailErr = "This email address is already registered!";
        } elseif ($academicModel->checkDuplicateStudentId($id)) {
            $idErr = "This Student ID number is already registered!";
        } else {

            $data = [
                "name"       => $name,
                "email"      => $email,
                "role"       => $role, 
                "student_id" => $id,
                "program_id" => isset($_POST['program_id']) ? intval($_POST['program_id']) : 1, // Default fallback
                "password"   => password_hash($password, PASSWORD_DEFAULT)
            ];
            
            if ($academicModel->register($data)) {
                header("Location: login.php");
                exit;
            } else {
                $idErr = "Registration failed due to a structural system issue.";
            }
        }
    }
}


// login logic 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && !isset($_POST['role']) && !isset($_POST['action'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email)) $emailErr = "Email required";
    if (empty($password)) $passErr = "Password required";
    
    if (empty($emailErr) && empty($passErr)) {
        $fetchedUser = $academicModel->login($email);
        
        if ($fetchedUser && password_verify($password, $fetchedUser['password_hash'])) {
            $_SESSION['user'] = $fetchedUser; 
            header("Location: dashboard.php");
            exit;
        } else {
            $passErr = "Invalid Email or Password";
        }
    }
}

// enrollement form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'enroll_course') {
    $courseId = intval($_POST['course_id']);
    $userId   = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 1;

    $result = $academicModel->enrollInCourse($userId, $courseId);
    $_SESSION['enroll_msg'] = $result['message'];
    
    if (ob_get_length()) ob_clean();
    header("Location: ../view/academic.php");
    exit;
}

// drop course action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'drop_course') {
    $courseId = intval($_POST['course_id']);
    $userId   = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 1;

    $academicModel->dropCourseWithGrades($userId, $courseId);
    
    if (ob_get_length()) ob_clean();
    header("Location: ../view/academic.php");
    exit;
}

// live cgpa credit
if (isset($_GET['action']) && $_GET['action'] == 'get_live_cgpa') {
    header('Content-Type: application/json');
    $data = $academicModel->getLiveCGPA($user['id'] ?? 0);

    $cgpa    = $data ? floatval($data['live_cgpa'])    : 0.00;
    $credits = $data ? intval($data['total_credits'])  : 0;

    $standing = ($cgpa >= 2.00 || $credits == 0) ? "Good Standing" : "Probation";

    echo json_encode([
        'cgpa'     => number_format($cgpa, 2),
        'credits'  => $credits,
        'standing' => $standing,
    ]);
    exit;
}

// =live course search
if (isset($_GET['action']) && $_GET['action'] == 'search_courses') {
    $search    = isset($_GET['query']) ? trim($_GET['query']) : '';
    $available = $academicModel->searchAvailableCourses($user['id'] ?? 0, $search);
    
    $rows = '';
    if ($available && $available->num_rows > 0) {
        while ($course = $available->fetch_assoc()) {
            $remaining   = $course['max_seats'] - $course['filled_seats'];
            $seatDisplay = $remaining . " / " . $course['max_seats'];

            if ($remaining <= 0 || $course['status'] == 'closed') {
                $actionButton = "<input type='submit' value='Closed' disabled>";
            } else {
                $actionButton = "<input type='submit' value='Enroll'>";
            }

            $rows .= "<tr>
                        <td>" . htmlspecialchars($course['code']) . "</td>
                        <td>" . htmlspecialchars($course['title']) . "</td>
                        <td align='center'>" . htmlspecialchars($course['credit_hours']) . "</td>
                        <td align='center'><b>" . $seatDisplay . "</b></td>
                        <td align='center'>
                            <form method='POST' action='../controller/AcademicController.php' style='margin:0;' class='enroll-form'>
                                <input type='hidden' name='action' value='enroll_course'>
                                <input type='hidden' name='course_id' value='" . $course['course_id'] . "'>
                                " . $actionButton . " 
                            </form>
                        </td>
                      </tr>";
        }
    } else {
        $rows .= "<tr><td colspan='5' align='center'>No matching courses available for enrollment.</td></tr>";
    }
    
    echo $rows;
    exit; 
}
?>