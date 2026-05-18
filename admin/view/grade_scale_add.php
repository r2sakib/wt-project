<!DOCTYPE html>
<html>
<head>
    <title>Add Grading Scale</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h2>Add Grading Scale Entry</h2>
        
        <form action="../controller/GradeScaleController.php" method="POST">
            <input type="hidden" name="action" value="add_submit">
            
            <div class="row">
                <div class="col">
                    <label>Min Marks:</label>
                    <input type="number" step="0.01" name="min_mark" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="col">
                    <label>Max Marks:</label>
                    <input type="number" step="0.01" name="max_mark" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div><br>

            <div class="row">
                <div class="col">
                    <label>Letter Grade:</label>
                    <input type="text" name="letter_grade" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="col">
                    <label>GPA Point:</label>
                    <input type="number" step="0.01" name="gpa_point" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div><br>
            
            <div class="justify-between">
                <a href="GradeScaleController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Save Entry" class="nav-btn" style="background: #28a745;">
            </div>
        </form>
    </div>
</body>
</html>