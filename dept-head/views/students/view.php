<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Student.php';

$department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = getStudentById($conn, $student_id, $department_id);

if (!$student) {
    echo "<div style='padding: 3rem; color: red;'>Student records are unavailable or access is restricted.</div>";
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
    <title>Student Profile</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Student Profile View</h2>
                <p class="text-muted">Detailed institutional and academic statement records.</p>
            </div>
            <div class="header-actions">
                <button class="btn-outline" id="btn-back-students">
                    <span class="icon">⬅️</span> Back to Directory
                </button>
            </div>
        </div>

        <div class="form-card" style="padding: 3rem;">
            <div style="display: flex; gap: 3rem; align-items: center; margin-bottom: 3rem; border-bottom: 1px solid #eee; padding-bottom: 2rem;">
                <div style="width: 100px; height: 100px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #475569;">
                    🎓
                </div>
                <div>
                    <h2 style="margin: 0 0 0.5rem 0; color: #1e293b;"><?php echo htmlspecialchars($student['student_name']); ?></h2>
                    <p style="margin: 0; color: #64748b; font-size: 1.4rem;">Student ID: <?php echo htmlspecialchars($student['id']); ?></p>
                </div>
            </div>

            <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 2.5rem;">
                <div class="form-group">
                    <label style="font-weight: 600; color: #475569;">Department</label>
                    <div style="padding: 1rem 0; font-size: 1.6rem; color: #1e293b; border-bottom: 1px solid #f1f5f9;"><?php echo htmlspecialchars($student['department_name']); ?></div>
                </div>

                <div class="form-group">
                    <label style="font-weight: 600; color: #475569;">Degree Programme</label>
                    <div style="padding: 1rem 0; font-size: 1.6rem; color: #1e293b; border-bottom: 1px solid #f1f5f9;"><?php echo htmlspecialchars($student['program_name']); ?> (<?php echo htmlspecialchars($student['program_code']); ?>)</div>
                </div>

                <div class="form-group">
                    <label style="font-weight: 600; color: #475569;">Cumulative GPA (CGPA)</label>
                    <div style="padding: 1rem 0; font-size: 1.8rem; font-weight: 700; color: #2563eb; border-bottom: 1px solid #f1f5f9;"><?php echo number_format($student['cgpa'], 2); ?></div>
                </div>

                <div class="form-group">
                    <label style="font-weight: 600; color: #475569;">Academic Standing Status</label>
                    <div style="padding: 1rem 0; border-bottom: 1px solid #f1f5f9;">
                        <?php 
                        $statusClass = 'badge-inactive';
                        if ($student['academic_status'] === 'good standing') {
                            $statusClass = 'badge-active';
                        } elseif ($student['academic_status'] === 'probation') {
                            $statusClass = 'badge-pending';
                        }
                        ?>
                        <span class="badge <?php echo $statusClass; ?>" style="font-size: 1.3rem; padding: 0.4rem 1.2rem;">
                            <?php echo ucwords(htmlspecialchars($student['academic_status'])); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>