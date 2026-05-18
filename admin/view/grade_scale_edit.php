<!DOCTYPE html>
<html>
<head>
    <title>Edit Grading Scale</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h2>Edit Grading Scale Entry</h2>
        
        <form action="../controller/GradeScaleController.php" method="POST">
            <input type="hidden" name="action" value="edit_submit">
            <input type="hidden" name="scale_id" value="<?php echo $scale['id']; ?>">
            
            <div class="row">
                <div class="col">
                    <label>Min Marks:</label>
                    <input type="number" step="0.01" name="min_mark" value="<?php echo $scale['min_mark']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="col">
                    <label>Max Marks:</label>
                    <input type="number" step="0.01" name="max_mark" value="<?php echo $scale['max_mark']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div><br>

            <div class="row">
                <div class="col">
                    <label>Letter Grade:</label>
                    <input type="text" name="letter_grade" value="<?php echo $scale['letter_grade']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="col">
                    <label>GPA Point:</label>
                    <input type="number" step="0.01" name="gpa_point" value="<?php echo $scale['gpa_point']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div><br>
            
            <div class="justify-between">
                <a href="GradeScaleController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Update Entry" class="nav-btn" style="background: #007bff;">
            </div>
        </form>
    </div>
</body>
</html>