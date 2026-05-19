<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../controllers/StudentController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/programIndex.css">
    <title>Student Directory</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Students Directory</h2>
                <p class="text-muted">Browse and manage students enrolled under your academic department.</p>
            </div>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Full Name</th>
                        <th>Program</th>
                        <th>CGPA</th>
                        <th>Academic Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: #777;">
                                No students found registered in your department.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="font-medium"><?php echo htmlspecialchars($student['id']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['program_code']); ?></td>
                                <td><?php echo number_format($student['cgpa'], 2); ?></td>
                                <td>
                                    <?php 
                                    $statusClass = 'badge-inactive';
                                    if ($student['academic_status'] === 'good standing') {
                                        $statusClass = 'badge-active';
                                    } elseif ($student['academic_status'] === 'probation') {
                                        $statusClass = 'badge-pending';
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo ucwords(htmlspecialchars($student['academic_status'])); ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <button class="btn-icon btn-view-student" title="View Profile" data-id="<?php echo $student['id']; ?>">👁️ View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>