<?php 
session_start();
if(!isset($_SESSION['user_id'])) exit; 
require_once __DIR__ . '/../../controllers/ProgramController.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/programIndex.css">
    <title>Programs</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Degree Programmes</h2>
                <p class="text-muted">Manage academic degree programs offered by your department.</p>
            </div>
            <div class="header-actions">
                <button class="btn-primary" id="btn-add-program">
                    <span class="icon">➕</span> Add New Program
                </button>
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
                        <th>Code</th>
                        <th>Programme Name</th>
                        <th>Duration</th>
                        <th>Credits</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($programs)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: #777;">
                                No programs found for your department yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($programs as $prog): ?>
                            <tr>
                                <td class="font-medium"><?php echo htmlspecialchars($prog['code']); ?></td>
                                <td><?php echo htmlspecialchars($prog['name']); ?></td>
                                <td><?php echo htmlspecialchars($prog['duration_years']); ?> Years</td>
                                <td><?php echo htmlspecialchars($prog['total_credit_hours']); ?></td>
                                <td><span class="badge badge-active">Active</span></td>
                                <td class="text-right" style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                    <button class="btn-icon btn-edit" title="Edit" data-id="<?php echo $prog['id']; ?>">✏️</button>
                                    <form action="controllers/ProgramController.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this program?');" style="margin: 0;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="program_id" value="<?php echo $prog['id']; ?>">
                                        <button type="submit" class="btn-icon btn-danger" title="Deactivate" style="border: none; background: none;">🛑</button>
                                    </form>
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