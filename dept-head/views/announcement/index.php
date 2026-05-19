<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../controllers/AnnouncementController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/programForm.css">
    <link rel="stylesheet" href="../../assets/css/programIndex.css">
    <title>Announcements</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Department Announcements</h2>
                <p class="text-muted">Broadcast official notices, guidelines, and urgent updates to students and staff.</p>
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

        <div class="form-card" style="margin-bottom: 3rem;">
            <h3 style="margin-bottom: 1.5rem; color: #1e293b;">Publish New Notice</h3>
            <form action="controllers/AnnouncementController.php" method="POST">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="title">Notice Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" placeholder="e.g., Midterm Conflict Exam Guidelines" required style="width:100%;">
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="body">Announcement Content <span class="required">*</span></label>
                    <textarea id="body" name="body" rows="4" placeholder="Type announcement details here..." required style="width:100%; font-family:inherit; padding:1rem; border:1px solid #ccc; border-radius:4px;"></textarea>
                </div>

                <div style="display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn-primary" style="padding: 1rem 2.5rem;">🚀 Broadcast Notice</button>
                </div>
            </form>
        </div>

        <h3 style="margin-bottom: 1.5rem; color: #1e293b;">Active Broadcasts History</h3>
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date Published</th>
                        <th>Notice Headline</th>
                        <th>Content Details</th>
                        <th>Scope</th>
                        <th>Publisher</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($announcements)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: #777;">
                                No active announcements recorded.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($announcements as $ann): ?>
                            <tr>
                                <td class="font-medium" style="white-space: nowrap;">
                                    <?php echo date('M d, Y H:i', strtotime($ann['created_at'])); ?>
                                </td>
                                <td style="font-weight: 600; color: #1e293b; max-width: 200px; word-wrap: break-word;">
                                    <?php echo htmlspecialchars($ann['title']); ?>
                                </td>
                                <td style="max-width: 350px; white-space: normal; word-wrap: break-word; color: #475569;">
                                    <?php echo htmlspecialchars($ann['body']); ?>
                                </td>
                                <td>
                                    <span class="badge <?php echo ($ann['scope'] === 'all') ? 'badge-active' : 'badge-pending'; ?>">
                                        <?php echo strtoupper(htmlspecialchars($ann['scope'])); ?>
                                    </span>
                                <td>
                                    <?php echo htmlspecialchars($ann['author_name']); ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($ann['scope'] !== 'all' || $_SESSION['role'] === 'admin'): ?>
                                        <form action="controllers/AnnouncementController.php" method="POST" onsubmit="return confirm('Are you sure you want to take down this notice?');" style="margin: 0;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="announcement_id" value="<?php echo $ann['id']; ?>">
                                            <button type="submit" class="btn-icon btn-danger" style="border: none; background: none; color: #dc2626; cursor: pointer;">🛑 Remove</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 1.2rem;">Read-Only</span>
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