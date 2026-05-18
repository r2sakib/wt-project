<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Course Details</h2>
        
        <form action="../controller/CourseController.php" method="POST">
            <input type="hidden" name="action" value="edit_submit">
            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
            
            <label>Course Name:</label>
            <input type="text" name="name" value="<?php echo $course['name']; ?>" required>
            
            <label>Course Code:</label>
            <input type="text" name="code" value="<?php echo $course['code']; ?>" required>

            <div class="row">
                <div class="col">
                    <label>Semester:</label>
                    <select name="semester_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <?php foreach ($formData['semesters'] as $sem) { ?>
                            <option value="<?php echo $sem['id']; ?>" <?php if($course['semester_id'] == $sem['id']) echo 'selected'; ?>>
                                <?php echo $sem['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col">
                    <label>Programme:</label>
                    <select name="program_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <?php foreach ($formData['programs'] as $prog) { ?>
                            <option value="<?php echo $prog['id']; ?>" <?php if($course['program_id'] == $prog['id']) echo 'selected'; ?>>
                                <?php echo $prog['name']; ?> (<?php echo $prog['code']; ?>)
                            </option>
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
                            <option value="<?php echo $fac['id']; ?>" <?php if($course['faculty_id'] == $fac['id']) echo 'selected'; ?>>
                                <?php echo $fac['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col">
                    <label>Max Seat Capacity:</label>
                    <input type="number" name="max_seats" value="<?php echo $course['max_seats']; ?>" required min="1" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div><br>
            
            <div class="justify-between">
                <a href="CourseController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Update Course" class="nav-btn" style="background: #007bff;">
            </div>
        </form>
    </div>
</body>
</html>