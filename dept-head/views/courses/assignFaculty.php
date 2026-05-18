<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../controllers/CourseController.php';

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$c_data = getCourseById($conn, $course_id, $department_id);

if (!$c_data) {
    echo "<div style='padding: 3rem; color: red;'>Course parameters missing.</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/programForm.css">
    <link rel="stylesheet" href="../../assets/css/programIndex.css">
    <title>Assign Faculty</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Assign Faculty Resource</h2>
                <p class="text-muted">Allocate a designated lecturer for <?php echo htmlspecialchars($c_data['code']); ?> - <?php echo htmlspecialchars($c_data['title']); ?></p>
            </div>
            <div class="header-actions">
                <button class="btn-outline" id="btn-back-courses">
                    <span class="icon">⬅️</span> Back to List
                </button>
            </div>
        </div>

        <div class="form-card">
            <form action="controllers/CourseController.php" method="POST">
                <input type="hidden" name="action" value="assign_faculty">
                <input type="hidden" name="course_id" value="<?php echo $c_data['id']; ?>">

                <div class="form-grid" style="grid-template-columns: 1fr;">
                    <div class="form-group">
                        <label for="faculty_id">Select Faculty Member <span class="required">*</span></label>
                        <select id="faculty_id" name="faculty_id" required style="width: 100%; padding: 0.8rem; border: 1px solid #ccc; border-radius: 4px;">
                            <option value="" disabled selected>Choose lecturer from department resources...</option>
                            <?php foreach($faculty_list as $f): ?>
                                <option value="<?php echo $f['id']; ?>" <?php echo ($f['id'] == $c_data['faculty_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($f['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 2rem;">
                    <button type="button" class="btn-secondary" id="btn-cancel-courses">Cancel</button>
                    <button type="submit" class="btn-primary">Save Assignment</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>