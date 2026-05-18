<?php
if (!isset($courses)) {
    header("Location: ../controller/CourseController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Courses</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 1100px;">
        <div class="justify-between">
            <h2>Manage Course Offerings</h2>
            <div>
                <a href="../controller/CourseController.php?action=add_form" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #28a745;">+ Create Course</button>
                </a>
                <a href="../controller/DashboardController.php" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #6c757d;">Back to Dashboard</button>
                </a>
            </div>
        </div>

        <table>
            <tr>
                <th>Code</th>
                <th>Course Name</th>
                <th>Semester</th>
                <th>Programme</th>
                <th>Faculty</th>
                <th>Capacity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($courses as $course) { ?>
            <tr>
                <td><strong><?php echo $course['code']; ?></strong></td>
                <td><?php echo $course['title']; ?></td>
                <td><?php echo $course['semester_name']; ?></td>
                <td><?php echo $course['program_code']; ?></td>
                <td><?php echo $course['faculty_name'] ?? '<em>TBA</em>'; ?></td>
                <td><?php echo $course['max_seats']; ?> Seats</td>
                <td>
                    <?php if ($course['status'] == 'open') { ?>
                        <span style="color: #28a745; font-weight: bold;">Open</span>
                    <?php } else { ?>
                        <span style="color: #dc3545; font-weight: bold;">Closed</span>
                    <?php } ?>
                </td>
                <td>
                    <form action="../controller/CourseController.php" method="GET" style="display:inline;">
                        <input type="hidden" name="action" value="edit_form">
                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                        <input type="submit" value="Edit" style="background: #007bff; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">
                    </form>

                    <form action="../controller/CourseController.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="toggle_status">
                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                        <input type="hidden" name="current_status" value="<?php echo $course['status']; ?>">
                        <?php if ($course['status'] == 'open') { ?>
                            <input type="submit" value="Close" style="background: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;" onclick="return confirm('Close this course to new enrolments?');">
                        <?php } else { ?>
                            <input type="submit" value="Open" style="background: #28a745; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">
                        <?php } ?>
                    </form>
                </td>
            </tr>
            <?php } ?>
            <?php if (empty($courses)) { echo "<tr><td colspan='8' class='text-center'>No courses found.</td></tr>"; } ?>
        </table>
    </div>
</body>
</html>