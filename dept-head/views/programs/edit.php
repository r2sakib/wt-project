<?php 
session_start();
if(!isset($_SESSION['user_id'])) exit; 

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Program.php';

$department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);
$program_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$prog = getProgramById($conn, $program_id, $department_id);

if (!$prog) {
    echo "<div style='padding: 3rem; color: red;'>Program not found or access denied.</div>";
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
    <title>Edit Programme</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Edit Programme</h2>
                <p class="text-muted">Update details for <?php echo htmlspecialchars($prog['code']); ?></p>
            </div>
            <div class="header-actions">
                <button class="btn-outline" id="btn-back-programs">
                    <span class="icon">⬅️</span> Back to List
                </button>
            </div>
        </div>

        <div class="form-card">
            <form action="controllers/ProgramController.php" method="POST" id="program-form">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="program_id" value="<?php echo $prog['id']; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Programme Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($prog['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="code">Programme Code <span class="required">*</span></label>
                        <input type="text" id="code" name="code" value="<?php echo htmlspecialchars($prog['code']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="total_credit_hours">Total Credit Hours <span class="required">*</span></label>
                        <input type="number" id="total_credit_hours" name="total_credit_hours" value="<?php echo htmlspecialchars($prog['total_credit_hours']); ?>" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="duration_years">Duration (Years) <span class="required">*</span></label>
                        <select id="duration_years" name="duration_years" required>
                            <option value="1" <?php if($prog['duration_years'] == 1) echo 'selected'; ?>>1 Year</option>
                            <option value="2" <?php if($prog['duration_years'] == 2) echo 'selected'; ?>>2 Years</option>
                            <option value="3" <?php if($prog['duration_years'] == 3) echo 'selected'; ?>>3 Years</option>
                            <option value="4" <?php if($prog['duration_years'] == 4) echo 'selected'; ?>>4 Years</option>
                            <option value="5" <?php if($prog['duration_years'] == 5) echo 'selected'; ?>>5 Years</option>
                        </select>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="description">Programme Description</label>
                    <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($prog['description']); ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" id="btn-cancel-programs">Cancel</button>
                    <button type="submit" class="btn-primary">Update Programme</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>