<?php 
// Transcript.php
require_once(__DIR__ . "/../controller/TranscriptController.php"); 
// DO NOT add header("Location: ...") loops here! Let the controller handle data logic.
?>
<?php
// TranscriptController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once(__DIR__ . "/../model/TranscriptModel.php"); // Steps out and goes to model folder
$model = new TranscriptModel();
$currentUser = $_SESSION['user'];

// 1. Process File Download Stream Execution
if (isset($_GET['action']) && $_GET['action'] === 'download_material') {
    $materialId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $fileData = $model->getMaterialFile($materialId);

    if ($fileData && file_exists($fileData['file_path'])) {
        while (ob_get_level()) { ob_end_clean(); } // Flush buffering fields cleanly
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fileData['file_path']) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileData['file_path']));
        readfile($fileData['file_path']);
        exit;
    } else {
        die("Error: The requested physical file does not exist on this server.");
    }
}

// 2. Load Base Data Arrays
$studentProfile = $model->getStudentProfile($currentUser['id']);
$transcriptRows = [];

if ($studentProfile) {
    $dbRecords = $model->getTranscriptRecords($studentProfile['student_pk']);
    
    $totalEarnedPoints = 0;
    $totalEarnedCredits = 0;

    while ($row = $dbRecords->fetch_assoc()) {
        $transcriptRows[] = $row;
        
        // Track published grades for live mathematical calculations
        if (!empty($row['is_published']) && $row['is_published'] == 1 && $row['gpa_point'] !== null) {
            $totalEarnedPoints += ($row['gpa_point'] * $row['credit_hours']);
            $totalEarnedCredits += $row['credit_hours'];
        }
    }

    // Auto-update student database state whenever live values mutate
    $calculatedCgpa = ($totalEarnedCredits > 0) ? ($totalEarnedPoints / $totalEarnedCredits) : 0.00;
    if (round($calculatedCgpa, 2) != round($studentProfile['profile_cgpa'], 2)) {
        $model->updateStudentCGPA($studentProfile['student_pk'], $calculatedCgpa);
        $studentProfile['profile_cgpa'] = $calculatedCgpa; // Keep the active page array fresh
    }
}


?>