<h2>Worksheet Spreadsheet Ledger:</h2>
<a href="index.php?action=course_manage&course_id=<?php echo intval($_GET['course_id']); ?>" style="text-decoration:none; font-weight:600; color:#2563eb; font-size:14px;">← Back to Course Hub</a>

<div class="card" style="margin-top:15px;">
    <table class="grade-table">
        <thead>
            <tr>
                <th>Student Identity</th>
                <th>Student ID</th>
                <th>CT (Max 20)</th>
                <th>Midterm (Max 30)</th>
                <th>Final (Max 50)</th>
                <th>Attendance %</th>
                <th>Aggregated Score</th>
                <th>Derived Grade</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $row): $disabled = ($row['is_published'] == 1) ? 'disabled' : ''; ?>
            <tr id="row-<?php echo $row['enrollment_id']; ?>">
                <td><strong><?php echo htmlspecialchars($row['student_name']); ?></strong></td>
                <td><code><?php echo htmlspecialchars($row['student_id_number']); ?></code></td>
                <td><input type="number" id="ct-<?php echo $row['enrollment_id']; ?>" value="<?php echo $row['ct_mark']; ?>" max="20" min="0" step="0.01" <?php echo $disabled; ?> onchange="asyncMatrixAutoSave(<?php echo $row['enrollment_id']; ?>)"></td>
                <td><input type="number" id="mid-<?php echo $row['enrollment_id']; ?>" value="<?php echo $row['mid_mark']; ?>" max="30" min="0" step="0.01" <?php echo $disabled; ?> onchange="asyncMatrixAutoSave(<?php echo $row['enrollment_id']; ?>)"></td>
                <td><input type="number" id="final-<?php echo $row['enrollment_id']; ?>" value="<?php echo $row['final_mark']; ?>" max="50" min="0" step="0.01" <?php echo $disabled; ?> onchange="asyncMatrixAutoSave(<?php echo $row['enrollment_id']; ?>)"></td>
                <td><input type="number" id="attn-<?php echo $row['enrollment_id']; ?>" value="<?php echo $row['attendance_pct']; ?>" max="100" min="0" step="0.01" <?php echo $disabled; ?> onchange="asyncMatrixAutoSave(<?php echo $row['enrollment_id']; ?>)"></td>
                <td id="total-<?php echo $row['enrollment_id']; ?>" class="total-cell"><?php echo $row['total_mark']; ?></td>
                <td id="grade-<?php echo $row['enrollment_id']; ?>" class="letter-cell"><?php echo $row['letter_grade']; ?></td>
                <td id="status-<?php echo $row['enrollment_id']; ?>">
                    <?php echo ($row['is_published'] == 1) ? '<span class="status-locked">🔒 Locked</span>' : '<span class="status-draft">📝 Draft</span>'; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function asyncMatrixAutoSave(enrollmentId) {
    let ct = document.getElementById('ct-' + enrollmentId).value || 0;
    let mid = document.getElementById('mid-' + enrollmentId).value || 0;
    let final = document.getElementById('final-' + enrollmentId).value || 0;
    let attn = document.getElementById('attn-' + enrollmentId).value || 0;

    document.getElementById('status-' + enrollmentId).innerHTML = "⏳ Saving...";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "api/update_grade.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let res = JSON.parse(xhr.responseText);
            if(res.status === "success") {
                document.getElementById('total-' + enrollmentId).innerText = parseFloat(res.total).toFixed(2);
                document.getElementById('grade-' + enrollmentId).innerText = res.grade;
                document.getElementById('status-' + enrollmentId).innerHTML = "<span style='color:green;font-weight:600;'>✅ Saved</span>";
            } else {
                document.getElementById('status-' + enrollmentId).innerHTML = "<span style='color:red;'>❌ Error</span>";
                alert("Save error: " + res.message);
            }
        }
    };
    xhr.send("id=" + enrollmentId + "&ct=" + ct + "&mid=" + mid + "&final=" + final + "&attn=" + attn);
}
</script>