<!DOCTYPE html>
<html>
<head>
    <title>Create Course</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container">
        <h2>Create New Course</h2>
        
        <form action="../controller/CourseController.php" method="POST">
            <input type="hidden" name="action" value="add_submit">
            
            <label>Course Name:</label>
            <input type="text" name="name" required placeholder="e.g. Web Technologies">
            
            <label>Course Code:</label>
            <input type="text" name="code" required placeholder="e.g. CSC4101">

            <div class="row">
                <div class="col">
                    <label>Semester:</label>
                    <select name="semester_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">-- Select Semester --</option>
                        <?php foreach ($formData['semesters'] as $sem) { ?>
                            <option value="<?php echo $sem['id']; ?>"><?php echo $sem['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col">
                    <label>Programme:</label>
                    <select name="program_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">-- Select Programme --</option>
                        <?php foreach ($formData['programs'] as $prog) { ?>
                            <option value="<?php echo $prog['id']; ?>"><?php echo $prog['name']; ?> (<?php echo $prog['code']; ?>)</option>
                        <?php } ?>
                    </select>
                </div>
            </div><br>

            <div class="row">
                <div class="col">
                    <label>Assign Faculty (Optional):</label>
                    <select name="faculty_id" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">-- To Be Announced --</option>
                        <?php foreach ($formData['faculty'] as $fac) { ?>
                            <option value="<?php echo $fac['id']; ?>"><?php echo $fac['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col">
                    <label>Max Seat Capacity:</label>
                    <input type="number" name="max_seats" required min="1" value="40" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div><br>
            
            <div class="justify-between">
                <a href="CourseController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Create Course" class="nav-btn" style="background: #28a745;">
            </div>
        </form>
    </div>
</body>
</html>