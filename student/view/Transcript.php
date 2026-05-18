<?php 
// transcript_view.php
require_once(__DIR__ . "/../controller/TranscriptController.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Academic Transcript & Materials</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-family: "Times New Roman", Times, Georgia, serif; color: #000; background: #fff; margin: 20px; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #000 !important; padding: 8px; text-align: left; }
        }
    </style>
</head>
<body>

    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td><h1>Unofficial Academic Transcript</h1></td>
            <td align="right" class="no-print">
                <button type="button" onclick="window.print();" style="padding: 8px 16px; cursor: pointer;">Print Transcript</button>
            </td>
            <td align="right"><a href="dashboard.php"><h3><b>Back</b></h3></a></td>
        </tr>
    </table>
    <hr>

    <?php if ($studentProfile): ?>
        <table width="100%" cellpadding="6" cellspacing="0" border="0">
            <tr>
                <td width="20%"><b>Student Name:</b></td>
                <td width="30%"><?php echo htmlspecialchars($studentProfile['student_name']); ?></td>
                <td width="20%"><b>Program:</b></td>
                <td width="30%"><?php echo htmlspecialchars($studentProfile['program_name']); ?></td>
            </tr>
            <tr>
                <td><b>Student ID:</b></td>
                <td><?php echo htmlspecialchars($studentProfile['student_id_number']); ?></td>
                <td><b>Institutional Cumulative CGPA:</b></td>
                <td><b><?php echo number_format($studentProfile['profile_cgpa'], 2); ?></b></td>
            </tr>
        </table>

        <br>
        <h3>Academic Performance Records</h3>

        <?php
        $currentSemesterHeader = "";
        $termCredits = 0;
        $termPoints = 0;
        $isOpenTable = false;

        foreach ($transcriptRows as $course):
            if ($course['semester_name'] !== $currentSemesterHeader):
                if ($isOpenTable): 
                    $termGpa = ($termCredits > 0) ? ($termPoints / $termCredits) : 0.00;
                ?>
                    </tbody>
                    </table>
                    <p><b>Term GPA:</b> <?php echo number_format($termGpa, 2); ?> | <b>Credits Completed:</b> <?php echo $termCredits; ?></p>
                    <br>
                <?php 
                    $termCredits = 0; 
                    $termPoints = 0; 
                endif;

                $currentSemesterHeader = $course['semester_name'];
                $isOpenTable = true;
                ?>
                
                <h4>Semester: <?php echo htmlspecialchars($currentSemesterHeader); ?></h4>
                <table border="1" cellpadding="6" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th align="left">Course Code</th>
                            <th align="left">Course Title</th>
                            <th align="center" width="10%">Credits</th>
                            <th align="center" width="10%">Grade</th>
                            <th align="center" width="10%">Points</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php 
            endif; 
            
            $gradeLabel = "In Progress";
            $pointLabel = "—";
            if (!empty($course['is_published']) && $course['is_published'] == 1) {
                $gradeLabel = !empty($course['letter_grade']) ? $course['letter_grade'] : "—";
                $pointLabel = ($course['gpa_point'] !== null) ? number_format($course['gpa_point'], 2) : "—";
                
                $termCredits += intval($course['credit_hours']);
                $termPoints += (floatval($course['gpa_point']) * intval($course['credit_hours']));
            }
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($course['course_title']); ?></td>
                    <td align="center"><?php echo intval($course['credit_hours']); ?></td>
                    <td align="center"><?php echo htmlspecialchars($gradeLabel); ?></td>
                    <td align="center"><?php echo htmlspecialchars($pointLabel); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php 
            if ($isOpenTable): 
                $termGpa = ($termCredits > 0) ? ($termPoints / $termCredits) : 0.00;
            ?>
                </tbody>
                </table>
                <p><b>Term GPA:</b> <?php echo number_format($termGpa, 2); ?> | <b>Credits Completed:</b> <?php echo $termCredits; ?></p>
            <?php endif; ?>

    <?php else: ?>
        <p>No verified student profile history records loaded.</p>
    <?php endif; ?>

    <br class="no-print"><hr class="no-print"><br class="no-print">

    <div class="no-print">
        

</body>
</html>