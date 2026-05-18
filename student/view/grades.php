<?php 
require_once("../controller/AcademicController.php"); 

$selected_semester = isset($_POST['semester_filter']) ? $_POST['semester_filter'] : 'current';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Grades & Marks</title>
</head>
<body>
    <table width="100%">
        <tr>
            <td><h2>Grades, Marks & Quizzes</h2></td>
            <td align="right"><a href="dashboard.php"><h3><b>Back</b></h3></a></td>
        </tr>
    </table>
    <hr>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label><b>Select Semester: </b></label>
        <select name="semester_filter" onchange="this.form.submit()">
            <option value="current" <?php if($selected_semester == 'current') echo 'selected'; ?>>Spring 2025-2026</option>
            <option value="prev_spring" <?php if($selected_semester == 'prev_spring') echo 'selected'; ?>>Fall 2024-2025</option>
            <option value="prev_fall" <?php if($selected_semester == 'prev_fall') echo 'selected'; ?>>Summer 2024-2025</option>
        </select>
    </form>
    <br>

    <h3>Academic Semester Performance</h3>

    <?php if ($selected_semester == 'current'): ?>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="left">Course Code</th>
                    <th align="left">Course Title</th>
                    <th align="center">CT Mark (Max 20)</th>
                    <th align="center">Mid Mark (Max 40)</th>
                    <th align="center">Final Mark (Max 40)</th>
                    <th align="center">Attendance %</th>
                    <th align="center">Total Mark (100)</th>
                    <th align="center">Letter Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($enrolledCourses) && $enrolledCourses->num_rows > 0): ?>
                    <?php while($course = $enrolledCourses->fetch_assoc()): ?>
                        <tr>
                            <td><b><?php echo htmlspecialchars($course['code']); ?></b></td>
                            <td><?php echo htmlspecialchars($course['title']); ?></td>
                            
                            <?php if (isset($course['is_published']) && $course['is_published'] == 1): ?>
                                <td align="center"><?php echo htmlspecialchars($course['ct_mark'] ?? '0.00'); ?></td>
                                <td align="center"><?php echo htmlspecialchars($course['mid_mark'] ?? '0.00'); ?></td>
                                <td align="center"><?php echo htmlspecialchars($course['final_mark'] ?? '0.00'); ?></td>
                                <td align="center"><?php echo htmlspecialchars($course['attendance_pct'] ?? '0.00'); ?>%</td>
                                <td align="center"><b><?php echo htmlspecialchars($course['total_mark'] ?? '0.00'); ?></b></td>
                                <td align="center"><font color="green"><b><?php echo htmlspecialchars($course['letter_grade'] ?? 'N/A'); ?></b></font></td>
                            <?php else: ?>
                                <td colspan="6" align="center">
                                    <i>Result Pending (Waiting for Faculty Publication)</i>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" align="center">No enrolled courses registered for grade assessment tracking.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <?php elseif ($selected_semester == 'prev_spring'): ?>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="left">Course Code</th>
                    <th align="left">Course Title</th>
                    <th align="center">CT Mark (Max 20)</th>
                    <th align="center">Mid Mark (Max 40)</th>
                    <th align="center">Final Mark (Max 40)</th>
                    <th align="center">Attendance %</th>
                    <th align="center">Total Mark (100)</th>
                    <th align="center">Letter Grade</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>CSC2211</b></td>
                    <td>OBJECT ORIENTED PROGRAMMING 1</td>
                    <td align="center">18.00</td>
                    <td align="center">36.50</td>
                    <td align="center">37.00</td>
                    <td align="center">95.00%</td>
                    <td align="center"><b>91.50</b></td>
                    <td align="center"><font color="green"><b>A+</b></font></td>
                </tr>
                <tr>
                    <td><b>MAT1205</b></td>
                    <td>INTEGRAL CALCULUS & ORDINARY DIFFERENTIAL EQUATIONS</td>
                    <td align="center">15.00</td>
                    <td align="center">32.00</td>
                    <td align="center">34.00</td>
                    <td align="center">92.00%</td>
                    <td align="center"><b>83.00</b></td>
                    <td align="center"><font color="green"><b>B+</b></font></td>
                </tr>
            </tbody>
        </table>

    <?php elseif ($selected_semester == 'prev_fall'): ?>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="left">Course Code</th>
                    <th align="left">Course Title</th>
                    <th align="center">CT Mark (Max 20)</th>
                    <th align="center">Mid Mark (Max 40)</th>
                    <th align="center">Final Mark (Max 40)</th>
                    <th align="center">Attendance %</th>
                    <th align="center">Total Mark (100)</th>
                    <th align="center">Letter Grade</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><b>CSC1102</b></td>
                    <td>INTRODUCTION TO COMPUTER STUDIES</td>
                    <td align="center">19.00</td>
                    <td align="center">38.00</td>
                    <td align="center">39.00</td>
                    <td align="center">100.00%</td>
                    <td align="center"><b>96.00</b></td>
                    <td align="center"><font color="green"><b>A+</b></font></td>
                </tr>
                <tr>
                    <td><b>ENG1101</b></td>
                    <td>ENGLISH COMPOSITION</td>
                    <td align="center">16.00</td>
                    <td align="center">34.00</td>
                    <td align="center">35.00</td>
                    <td align="center">88.00%</td>
                    <td align="center"><b>85.00</b></td>
                    <td align="center"><font color="green"><b>A</b></font></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>