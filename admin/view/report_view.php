<?php
if (!isset($enrollments)) {
    header("Location: ../controller/ReportController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Institution Reports</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 1200px;">
        <div class="justify-between">
            <h2>Institution-Wide Academic Reports</h2>
            <a href="../controller/DashboardController.php" style="text-decoration: none;">
                <button class="nav-btn" style="background: #6c757d;">Back to Dashboard</button>
            </a>
        </div>

        <div class="row">
            <div class="col card">
                <h3 class="text-center">Total Enrolments Per Programme</h3>
                <table>
                    <tr><th>Programme</th><th>Dept</th><th>Students</th></tr>
                    <?php foreach ($enrollments as $prog) { ?>
                        <tr>
                            <td><?php echo $prog['program_name'] . ' (' . $prog['program_code'] . ')'; ?></td>
                            <td><?php echo $prog['dept_code']; ?></td>
                            <td class="text-center"><strong><?php echo $prog['total_students']; ?></strong></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="col card">
                <h3 class="text-center">Average CGPA Per Department</h3>
                <table>
                    <tr><th>Department</th><th>Code</th><th>Avg CGPA</th></tr>
                    <?php foreach ($cgpaData as $dept) { ?>
                        <tr>
                            <td><?php echo $dept['dept_name']; ?></td>
                            <td><?php echo $dept['dept_code']; ?></td>
                            <td class="text-center">
                                <strong><?php echo number_format($dept['avg_cgpa'] ?? 0, 2); ?></strong>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col card">
                <h3 class="text-center">Pass Rate Per Course</h3>
                <table>
                    <tr><th>Course Code</th><th>Title</th><th>Enrolled</th><th>Pass Rate</th></tr>
                    <?php foreach ($passRates as $course) { ?>
                        <tr>
                            <td><?php echo $course['code']; ?></td>
                            <td><?php echo $course['title']; ?></td>
                            <td class="text-center"><?php echo $course['total_enrolled']; ?></td>
                            <td class="text-center">
                                <strong style="color: <?php echo $course['pass_rate'] >= 50 ? '#28a745' : '#dc3545'; ?>">
                                    <?php echo number_format($course['pass_rate'], 1); ?>%
                                </strong>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="col card" style="border: 1px solid #dc3545;">
                <h3 class="text-center" style="color: #dc3545;">Students on Academic Probation (< 2.00)</h3>
                <table>
                    <tr><th>Name</th><th>Email</th><th>Prog</th><th>CGPA</th></tr>
                    <?php foreach ($probationStudents as $student) { ?>
                        <tr>
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['email']; ?></td>
                            <td><?php echo $student['program_code']; ?></td>
                            <td class="text-center"><strong style="color: #dc3545;"><?php echo number_format($student['cgpa'], 2); ?></strong></td>
                        </tr>
                    <?php } ?>
                    <?php if(empty($probationStudents)) { echo "<tr><td colspan='4' class='text-center'>No students on probation.</td></tr>"; } ?>
                </table>
            </div>
        </div>

    </div>
</body>
</html>