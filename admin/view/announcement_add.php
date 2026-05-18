<!DOCTYPE html>
<html>
<head>
    <title>Post Announcement</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 700px;">
        <h2>Post New Announcement</h2>
        
        <form action="../controller/AnnouncementController.php" method="POST">
            <input type="hidden" name="action" value="add_submit">
            
            <label>Announcement Title:</label>
            <input type="text" name="title" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;" placeholder="e.g. Holiday Notice">
            
            <label>Target Scope:</label>
            <select name="scope" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;">
                <option value="all">All Users</option>
                <option value="student">Students Only</option>
                <option value="faculty">Faculty Only</option>
                <option value="head">Department Heads Only</option>
            </select>

            <label>Announcement Body:</label>
            <textarea name="body" rows="6" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 20px;" placeholder="Type your message here..."></textarea>
            
            <div class="justify-between">
                <a href="AnnouncementController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Post Announcement" class="nav-btn" style="background: #007bff;">
            </div>
        </form>
    </div>
</body>
</html>