<?php 
require_once(__DIR__ . "/../controller/TranscriptController.php"); 
?>
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once(__DIR__ . "/../model/TranscriptModel.php"); 
$model = new TranscriptModel();
$currentUser = $_SESSION['user'];

// 1.  file download
if (isset($_GET['action']) && $_GET['action'] === 'download_material') {
    $materialId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $fileData = $model->getMaterialFile($materialId);

    if ($fileData && file_exists($fileData['file_path'])) {
        while (ob_get_level()) { ob_end_clean(); } 
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

//  load base data array
$studentProfile = $model->getStudentProfile($currentUser['id']);
$transcriptRows = [];

if ($studentProfile) {
    $dbRecords = $model->getTranscriptRecords($studentProfile['student_pk']);
    
    $totalEarnedPoints = 0;
    $totalEarnedCredits = 0;

    while ($row = $dbRecords->fetch_assoc()) {
        $transcriptRows[] = $row;
        
        
        if (!empty($row['is_published']) && $row['is_published'] == 1 && $row['gpa_point'] !== null) {
            $totalEarnedPoints += ($row['gpa_point'] * $row['credit_hours']);
            $totalEarnedCredits += $row['credit_hours'];
        }
    }

    
    $calculatedCgpa = ($totalEarnedCredits > 0) ? ($totalEarnedPoints / $totalEarnedCredits) : 0.00;
    if (round($calculatedCgpa, 2) != round($studentProfile['profile_cgpa'], 2)) {
        $model->updateStudentCGPA($studentProfile['student_pk'], $calculatedCgpa);
        $studentProfile['profile_cgpa'] = $calculatedCgpa; 
    }
}


?>