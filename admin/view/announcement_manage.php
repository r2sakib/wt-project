<?php
if (!isset($announcements)) {
    header("Location: ../controller/AnnouncementController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Announcements</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 1000px;">
        <div class="justify-between">
            <h2>System Announcements</h2>
            <div>
                <a href="../controller/AnnouncementController.php?action=add_form" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #28a745;">+ New Announcement</button>
                </a>
                <a href="../controller/DashboardController.php" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #6c757d;">Back to Dashboard</button>
                </a>
            </div>
        </div>

        <table>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Scope</th>
                <th>Author</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($announcements as $ann) { ?>
            <tr>
                <td style="white-space: nowrap;"><?php echo date('M d, Y', strtotime($ann['created_at'])); ?></td>
                <td><strong><?php echo $ann['title']; ?></strong></td>
                <td style="text-transform: capitalize;"><?php echo $ann['scope']; ?></td>
                <td><?php echo $ann['author_name'] ?? 'System'; ?></td>
                <td style="white-space: nowrap;">
                    <form action="../controller/AnnouncementController.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="announcement_id" value="<?php echo $ann['id']; ?>">
                        <input type="submit" value="Delete" style="background: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;" onclick="return confirm('Are you sure you want to delete this announcement?');">
                    </form>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="background: #f8f9fa; padding: 10px; font-size: 0.9em; border-bottom: 2px solid #ddd;">
                    <?php echo nl2br(htmlspecialchars($ann['body'])); ?>
                </td>
            </tr>
            <?php } ?>
            <?php if (empty($announcements)) { echo "<tr><td colspan='5' class='text-center'>No announcements found.</td></tr>"; } ?>
        </table>
    </div>
</body>
</html>