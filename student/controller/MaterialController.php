<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . "/../model/MaterialModel.php");
$materialModel = new MaterialModel();



// file download
if (isset($_GET['action']) && $_GET['action'] == 'download_material' && isset($_GET['id'])) {
    if (!isset($_SESSION['user'])) {
        die("Access Denied. Please log in first.");
    }

    $materialId = intval($_GET['id']);
    $material = $materialModel->getMaterialById($materialId);

    if ($material) {
        $filePath = __DIR__ . "/../uploads/" . $material['file_path'];

        if (file_exists($filePath)) {
    
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            
    
            flush();
            readfile($filePath);
            exit;
        } else {
            die("Error: The requested physical file does not exist on the server.");
        }
    } else {
        die("Error: Document record not found in system repository.");
    }
}

// load matts 
$materialsList = [];
if (isset($_SESSION['user']['id'])) {
    $materialsList = $materialModel->getMaterialsForStudent($_SESSION['user']['id']);
}

$nextEvent = $materialModel->getNextImportantDate();
?>
