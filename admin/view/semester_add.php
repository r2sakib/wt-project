<!DOCTYPE html>
<html>
<head>
    <title>Add Semester</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container">
        <h2>Create New Semester</h2>
        
        <form action="../controller/SemesterController.php" method="POST">
            <input type="hidden" name="action" value="add_submit">
            
            <label>Semester Name:</label>
            <input type="text" name="name" required placeholder="e.g. Spring 2025">
            
            <div class="row">
                <div class="col">
                    <label>Start Date:</label>
                    <input type="date" name="start_date" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="col">
                    <label>End Date:</label>
                    <input type="date" name="end_date" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div><br>

            <div class="row">
                <div class="col">
                    <label>Drop Deadline:</label>
                    <input type="date" name="drop_deadline" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="col">
                    <label>Grade Submission Deadline:</label>
                    <input type="date" name="grade_submission_deadline" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div><br>
            
            <div class="justify-between">
                <a href="SemesterController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Save Semester" class="nav-btn" style="background: #28a745;">
            </div>
        </form>
    </div>
</body>
</html>