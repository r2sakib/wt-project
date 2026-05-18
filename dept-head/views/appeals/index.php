<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../controllers/AppealController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/programIndex.css">
    <title>Grade Appeals</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Grade Appeals Management</h2>
                <p class="text-muted">Review and resolve official student grade re-evaluation requests.</p>
            </div>
        </div>

        <?php if(isset($_SESSION['success_msg'])): ?>
            <div style="background: #d1fae5; color: #065f46; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error_msg'])): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                <?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
            </div>
        <?php endif; ?>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Reason Statement</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($appeals)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem; color: #777;">
                                No grade appeals found for your department.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($appeals as $appeal): ?>
                            <tr>
                                <td class="font-medium">
                                    <?php echo htmlspecialchars($appeal['student_name']); ?><br>
                                    <span class="text-muted" style="font-size: 1.2rem;"><?php echo htmlspecialchars($appeal['student_id_number']); ?></span>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($appeal['course_code']); ?><br>
                                    <span class="text-muted" style="font-size: 1.2rem;"><?php echo htmlspecialchars($appeal['course_title']); ?></span>
                                </td>
                                <td style="max-width: 300px; white-space: normal; word-wrap: break-word;">
                                    <?php echo htmlspecialchars($appeal['reason']); ?>
                                </td>
                                <td>
                                    <?php 
                                    $statusClass = 'badge-pending';
                                    if ($appeal['status'] === 'approved') {
                                        $statusClass = 'badge-active';
                                    } elseif ($appeal['status'] === 'rejected') {
                                        $statusClass = 'badge-inactive';
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo ucfirst(htmlspecialchars($appeal['status'])); ?>
                                    </span>
                                </td>
                                <td class="text-right" style="vertical-align: middle;">
                                    <?php if ($appeal['status'] === 'pending'): ?>
                                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                            <form action="controllers/AppealController.php" method="POST" onsubmit="return confirm('Approve this grade appeal?');" style="margin: 0;">
                                                <input type="hidden" name="action" value="approve">
                                                <input type="hidden" name="appeal_id" value="<?php echo $appeal['id']; ?>">
                                                <button type="submit" class="btn-icon" style="background: #d1fae5; color: #065f46; border: none; padding: 0.6rem 1.2rem; border-radius: 4px; cursor: pointer;">✔ Approve</button>
                                            </form>
                                            <form action="controllers/AppealController.php" method="POST" onsubmit="return confirm('Reject this grade appeal?');" style="margin: 0;">
                                                <input type="hidden" name="action" value="reject">
                                                <input type="hidden" name="appeal_id" value="<?php echo $appeal['id']; ?>">
                                                <button type="submit" class="btn-icon" style="background: #fee2e2; color: #991b1b; border: none; padding: 0.6rem 1.2rem; border-radius: 4px; cursor: pointer;">❌ Reject</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 1.3rem;">Resolved</span>
                                    <?php endif; ?>
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