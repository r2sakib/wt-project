<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Student.php';

$department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$student = getStudentById($conn, $student_id, $department_id);
if (!$student) {
    echo "<div style='padding:2rem; color:red;'>Access Denied: Record not found inside your department footprint.</div>";
    exit;
}

$past_notes = getAdvisorNotesByStudent($conn, $student_id);
?>
<div class="programs-container">
    <div class="page-header">
        <div class="header-info">
            <h2>Academic Track Profile: <?php echo htmlspecialchars($student['student_name']); ?></h2>
            <p class="text-muted">Review counseling timelines and update administrative tracking flags.</p>
        </div>
        <button id="btn-back-students" class="btn-secondary">⬅️ Back to Directory</button>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem; align-items: start;">
        
        <div style="display:flex; flex-direction:column; gap:2rem;">
            <div class="form-card" style="background: #fff; padding: 2rem; border-radius: 8px;">
                <h3 style="border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; margin-bottom: 1rem;">Registration Details</h3>
                <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id_number']); ?></p>
                <p><strong>Institutional Email:</strong> <?php echo htmlspecialchars($student['student_email']); ?></p>
                <p><strong>Current Curriculum Track:</strong> <?php echo htmlspecialchars($student['program_name']); ?></p>
                <p><strong>CGPA:</strong> <span style="font-size:1.6rem; font-weight:700; color:#2563eb;"><?php echo number_format($student['cgpa'], 2); ?></span></p>
            </div>

            <div class="form-card" style="background: #fff; padding: 2rem; border-radius: 8px;">
                <h3 style="border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; margin-bottom: 1.2rem; color:#ef4444;">Update Academic Standing</h3>
                <form action="controllers/StudentController.php" method="POST">
                    <input type="hidden" name="action" value="change_status">
                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                    
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Select Status Standing Matrix:</label>
                        <select name="academic_status" style="width:100%; padding:0.8rem; margin-top:0.5rem;">
                            <option value="good_standing" <?php echo ($student['academic_status'] === 'good_standing') ? 'selected' : ''; ?>>Good Standing</option>
                            <option value="probation" <?php echo ($student['academic_status'] === 'probation') ? 'selected' : ''; ?>>Probation Track</option>
                            <option value="dismissed" <?php echo ($student['academic_status'] === 'dismissed') ? 'selected' : ''; ?>>Dismissed Status</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%;">💾 Apply Status Change</button>
                </form>
            </div>
        </div>

        <div class="form-card" style="background: #fff; padding: 2rem; border-radius: 8px;">
            <h3 style="border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; margin-bottom: 1rem; color:#475569;">Private Advisor Notes Log</h3>
            
            <form action="controllers/StudentController.php" method="POST" style="margin-bottom:2rem; padding-bottom:1.5rem; border-bottom:1px solid #e2e8f0;">
                <input type="hidden" name="action" value="add_note">
                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                <div class="form-group" style="margin-bottom:1rem;">
                    <label>Append Counseling Entry Note:</label>
                    <textarea name="note_content" rows="3" placeholder="Type notes regarding performance evaluations, academic tracking conditions, or meeting outcomes here..." required style="width:100%; font-family:inherit; padding:0.5rem; margin-top:0.5rem;"></textarea>
                </div>
                <button type="submit" class="btn-secondary" style="background:#f1f5f9; border:1px solid #cbd5e1;">🖋️ Append Evaluation Entry</button>
            </form>

            <div style="max-height: 280px; overflow-y: auto; padding-right: 0.5rem;">
                <?php if(empty($past_notes)): ?>
                    <p class="text-muted" style="font-style:italic; text-align:center; padding:1rem;">No tracking advisor notes logged for this student yet.</p>
                <?php else: ?>
                    <?php foreach($past_notes as $noteRow): ?>
                        <div style="background:#f8fafc; padding:1rem; border-radius:6px; border-left:4px solid #64748b; margin-bottom:1rem;">
                            <p style="margin:0 0 0.4rem 0; color:#334155; font-size:1.3rem; line-height:1.4;"><?php echo htmlspecialchars($noteRow['note']); ?></p>
                            <small style="color:#94a3b8; font-weight:500;">Logged by: <?php echo htmlspecialchars($noteRow['head_name']); ?> • <?php echo date('M d, Y (h:i A)', strtotime($noteRow['created_at'])); ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>