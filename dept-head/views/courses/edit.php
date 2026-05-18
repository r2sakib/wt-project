<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../controllers/CourseController.php';

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$c_data = getCourseById($conn, $course_id, $department_id);

if (!$c_data) {
    echo "<div style='padding: 3rem; color: red;'>Records unavailable.</div>";
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
    <title>Edit Course</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Edit Course Profile</h2>
                <p class="text-muted">Modify configurations for <?php echo htmlspecialchars($c_data['code']); ?></p>
            </div>
            <div class="header-actions">
                <button class="btn-outline" id="btn-back-courses">
                    <span class="icon">⬅️</span> Back to List
                </button>
            </div>
        </div>

        <div class="form-card">
            <form action="controllers/CourseController.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="course_id" value="<?php echo $c_data['id']; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="title">Course Title <span class="required">*</span></label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($c_data['title']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="code">Course Code <span class="required">*</span></label>
                        <input type="text" id="code" name="code" value="<?php echo htmlspecialchars($c_data['code']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="program_id">Degree Program <span class="required">*</span></label>
                        <select id="program_id" name="program_id" required>
                            <?php foreach($programs as $p): ?>
                                <option value="<?php echo $p['id']; ?>" <?php echo ($p['id'] == $c_data['program_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="semester_id">Academic Semester <span class="required">*</span></label>
                        <select id="semester_id" name="semester_id" required>
                            <?php foreach($semesters as $s): ?>
                                <option value="<?php echo $s['id']; ?>" <?php echo ($s['id'] == $c_data['semester_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="faculty_id">Assign Faculty</label>
                        <select id="faculty_id" name="faculty_id">
                            <option value="">Leave Unassigned</option>
                            <?php foreach($faculty_list as $f): ?>
                                <option value="<?php echo $f['id']; ?>" <?php echo ($f['id'] == $c_data['faculty_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($f['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="credit_hours">Credit Hours <span class="required">*</span></label>
                        <input type="number" id="credit_hours" name="credit_hours" value="<?php echo htmlspecialchars($c_data['credit_hours']); ?>" min="1" max="6" required>
                    </div>

                    <div class="form-group">
                        <label for="max_seats">Seat Capacity <span class="required">*</span></label>
                        <input type="number" id="max_seats" name="max_seats" value="<?php echo htmlspecialchars($c_data['max_seats']); ?>" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Initial Status <span class="required">*</span></label>
                        <select id="status" name="status" required>
                            <option value="open" <?php echo ($c_data['status'] === 'open') ? 'selected' : ''; ?>>Open</option>
                            <option value="closed" <?php echo ($c_data['status'] === 'closed') ? 'selected' : ''; ?>>Closed</option>
                            <option value="completed" <?php echo ($c_data['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" id="btn-cancel-courses">Cancel</button>
                    <button type="submit" class="btn-primary">Update Course</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>