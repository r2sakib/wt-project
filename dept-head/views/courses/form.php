<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../controllers/CourseController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/programForm.css">
    <link rel="stylesheet" href="../../assets/css/programIndex.css">
    <title>Add Course</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Add New Course</h2>
                <p class="text-muted">Create a new course profile and map resources.</p>
            </div>
            <div class="header-actions">
                <button class="btn-outline" id="btn-back-courses">
                    <span class="icon">⬅️</span> Back to List
                </button>
            </div>
        </div>

        <div class="form-card">
            <form action="controllers/CourseController.php" method="POST">
                <input type="hidden" name="action" value="add">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="title">Course Title <span class="required">*</span></label>
                        <input type="text" id="title" name="title" placeholder="e.g., Object Oriented Programming" required>
                    </div>

                    <div class="form-group">
                        <label for="code">Course Code <span class="required">*</span></label>
                        <input type="text" id="code" name="code" placeholder="e.g., CSC-2103" required>
                    </div>

                    <div class="form-group">
                        <label for="program_id">Degree Program <span class="required">*</span></label>
                        <select id="program_id" name="program_id" required>
                            <option value="" disabled selected>Select program...</option>
                            <?php foreach($programs as $p): ?>
                                <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="semester_id">Academic Semester <span class="required">*</span></label>
                        <select id="semester_id" name="semester_id" required>
                            <option value="" disabled selected>Select semester...</option>
                            <?php foreach($semesters as $s): ?>
                                <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="faculty_id">Assign Faculty</label>
                        <select id="faculty_id" name="faculty_id">
                            <option value="">Leave Unassigned</option>
                            <?php foreach($faculty_list as $f): ?>
                                <option value="<?php echo $f['id']; ?>"><?php echo htmlspecialchars($f['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="credit_hours">Credit Hours <span class="required">*</span></label>
                        <input type="number" id="credit_hours" name="credit_hours" min="1" max="6" required>
                    </div>

                    <div class="form-group">
                        <label interpreter="max_seats">Seat Capacity <span class="required">*</span></label>
                        <input type="number" id="max_seats" name="max_seats" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Initial Status <span class="required">*</span></label>
                        <select id="status" name="status" required>
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" id="btn-cancel-courses">Cancel</button>
                    <button type="submit" class="btn-primary">Save Course</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>