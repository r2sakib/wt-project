<!DOCTYPE html>
<html>
<head>
    <title>Add Calendar Event</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 700px;">
        <h2>Add Institution-Wide Event</h2>
        
        <form action="../controller/CalendarController.php" method="POST">
            <input type="hidden" name="action" value="add_submit">
            
            <label>Semester:</label>
            <select name="semester_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;">
                <option value="">-- Select Semester --</option>
                <?php foreach ($semesters as $sem) { ?>
                    <option value="<?php echo $sem['id']; ?>"><?php echo $sem['name']; ?></option>
                <?php } ?>
            </select>

            <label>Event Name:</label>
            <input type="text" name="event_name" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;" placeholder="e.g. Midterm Exams Begin">
            
            <div class="row" style="margin-bottom: 15px;">
                <div class="col">
                    <label>Event Date:</label>
                    <input type="date" name="event_date" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="col">
                    <label>Visible To:</label>
                    <select name="visible_to" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="all">All Users</option>
                        <option value="students">Students Only</option>
                        <option value="faculty">Faculty Only</option>
                        <option value="staff">Staff Only</option>
                    </select>
                </div>
            </div>

            <label>Description (Optional):</label>
            <textarea name="description" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 20px;"></textarea>
            
            <div class="justify-between">
                <a href="CalendarController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Save Event" class="nav-btn" style="background: #28a745;">
            </div>
        </form>
    </div>
</body>
</html>