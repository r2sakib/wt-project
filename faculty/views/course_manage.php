<h2>Course Hub: <?php echo htmlspecialchars($course['code']); ?> - <?php echo htmlspecialchars($course['title']); ?></h2>
<a href="index.php?action=faculty_dashboard" class="hub-header">← Return to Root Dashboard</a>

<div class="grid-half">
    <div class="card">
        <h3>Class Grading Management</h3>
        <p class="stat-sub">Locking finalizes spreadsheets away from instant modifications.</p>
        <a class="btn" href="index.php?action=faculty_grades&course_id=<?php echo $course['id']; ?>" style="margin-bottom:15px;">Open Excel Grading Worksheet Grid</a>
        
        <form action="index.php?action=publish_grades" method="POST" onsubmit="return confirm('Lock spreadsheet records? Edits post-publishing require Board approval.');">
            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
            <button type="submit" class="btn" style="background:#dc2626; width:100%;">🔒 Finalize & Publish Final Grades Matrix</button>
        </form>

        <h4 style="margin-top:20px; border-top:1px solid #e2e8f0; padding-top:15px;">📊 Performance Analysis Summary</h4>
        <p style="font-size:13px;">Class Average: <strong><?php echo round($distribution['avg_mark'] ?? 0, 2); ?></strong> | Top Mark: <strong><?php echo $distribution['max_mark'] ?? 0; ?></strong> | Lowest Mark: <strong><?php echo $distribution['min_mark'] ?? 0; ?></strong></p>
        <p style="font-size:13px;">Overall Pass Percentage Rate: <strong style="color:green;"><?php echo round($distribution['pass_rate'] ?? 0, 1); ?>%</strong></p>
        
        <table class="distribution-table">
            <thead>
                <tr style="background:#f8fafc;">
                    <th>A Tier (A+/A)</th>
                    <th>B Tier (B+/B)</th>
                    <th>C Tier (C+/C)</th>
                    <th>D Tier (D+/D)</th>
                    <th>Failures (F)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $distribution['count_A'] ?? 0; ?></td>
                    <td><?php echo $distribution['count_B'] ?? 0; ?></td>
                    <td><?php echo $distribution['count_C'] ?? 0; ?></td>
                    <td><?php echo $distribution['count_D'] ?? 0; ?></td>
                    <td style="color:red; font-weight:bold;"><?php echo $distribution['count_F'] ?? 0; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3>🚨 Class Attendance Tracker & Flags</h3>
        <p class="stat-sub">Highlights records in red when values drop below the 75% boundary restriction.</p>
        <div class="attendance-scroll">
            <table style="margin-top:0;">
                <thead>
                    <tr><th>Student Entity</th><th>Student ID</th><th>Attendance Log %</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                    <tr class="<?php echo ($s['attendance_pct'] < 75) ? 'flagged-row' : ''; ?>">
                        <td><?php echo htmlspecialchars($s['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($s['student_id_number']); ?></td>
                        <td>
                            <?php echo $s['attendance_pct']; ?>% 
                            <?php echo ($s['attendance_pct'] < 75) ? '⚠️ [FLAGGED LOW]' : ''; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card" style="margin-top:20px;">
    <h3>📢 Classroom Board Notifications & Announcements</h3>
    <form action="index.php?action=add_announcement" method="POST" class="flex-form">
        <select name="course_id" required style="padding:8px; border:1px solid #cbd5e1; border-radius:4px; font-size:13px; font-weight:600; color:#334155; background:#fff;">
            <?php foreach ($all_courses as $ac): ?>
                <option value="<?php echo $ac['id']; ?>" <?php echo ($ac['id'] == $course['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($ac['code'] . ' (' . $ac['semester'] . ')'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="title" placeholder="Announcement Header Title Text" required>
        <textarea name="body" placeholder="Message content payload parameters go here..." required></textarea>
        <button type="submit" class="btn">Post Notice</button>
    </form>
    
    <h5>Active Notification Logs:</h5>
    <?php foreach ($announcements as $a): ?>
        <div style="background:#f8fafc; padding:12px; border:1px solid #e2e8f0; margin-bottom:8px; border-radius:4px;">
            <a href="index.php?action=delete_announcement&id=<?php echo $a['id']; ?>&course_id=<?php echo $course['id']; ?>" onclick="return confirm('Remove announcement logs?');" style="color:#ef4444; float:right; font-size:12px; text-decoration:none; font-weight:600;">Delete</a>
            <strong><?php echo htmlspecialchars($a['title']); ?></strong> - <small style="color:#64748b;"><?php echo $a['created_at']; ?></small>
            <p style="margin:6px 0 0 0; font-size:13px; color:#475569;"><?php echo htmlspecialchars($a['body']); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <h3>📁 Shared Resources & Course Materials Device Manager</h3>
    <form action="index.php?action=add_material" method="POST" enctype="multipart/form-data" class="flex-form">
        <select name="course_id" required style="padding:8px; border:1px solid #cbd5e1; border-radius:4px; font-size:13px; font-weight:600; color:#334155; background:#fff;">
            <?php foreach ($all_courses as $ac): ?>
                <option value="<?php echo $ac['id']; ?>" <?php echo ($ac['id'] == $course['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($ac['code']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="title" placeholder="Material Description Topic Name" required style="flex:1;">
        <input type="file" name="material_file" required style="flex:1; font-size:12px;">
        <select name="material_type" style="padding:8px; border:1px solid #cbd5e1; border-radius:4px;">
            <option value="PDF">Adobe PDF</option>
            <option value="DOCX">Microsoft Word</option>
            <option value="PPTX">PowerPoint Slides</option>
            <option value="Link">Web Resource</option>
        </select>
        <button type="submit" class="btn">Upload Resource</button>
    </form>

    <h5>Resource Directories Log:</h5>
    <?php foreach ($materials as $m): ?>
        <div style="padding:10px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
            <span>Doc: [<?php echo $m['material_type']; ?>] <strong><?php echo htmlspecialchars($m['title']); ?></strong> - 
                <a href="<?php echo htmlspecialchars($m['file_path']); ?>" target="_blank" style="color:#2563eb; font-weight:600; text-decoration:none;">Download/Open ↗</a>
            </span>
            <a href="index.php?action=delete_material&id=<?php echo $m['id']; ?>&course_id=<?php echo $course['id']; ?>" onclick="return confirm('Wipe file from project drive?');" style="color:#ef4444; font-size:12px; text-decoration:none; font-weight:600;">Remove File</a>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <h3>⚖️ Grade Dispute Review & Appeals Board</h3>
    <?php if (empty($appeals)): ?><p style="font-size:13px; color:#64748b; font-style:italic;">No active grade review exceptions logged by students.</p><?php endif; ?>
    <?php foreach ($appeals as $ap): ?>
        <div class="appeal-box">
            <p style="margin:0; font-size:14px;">Student: <strong><?php echo htmlspecialchars($ap['student_name']); ?></strong> | Course Grade: <strong style="color:#1e3a8a;"><?php echo $ap['letter_grade']; ?></strong> | Status: <strong><?php echo htmlspecialchars($ap['status']); ?></strong></p>
            <p class="appeal-reason">"<?php echo htmlspecialchars($ap['reason']); ?>"</p>
            <p style="margin:5px 0; font-size:13px; color:#475569;"><strong>Faculty Counter Comment:</strong> <?php echo htmlspecialchars($ap['faculty_comment'] ?: 'No counter response registered yet.'); ?></p>
            
            <form action="index.php?action=respond_appeal" method="POST" style="margin-top:10px; display:flex; gap:10px;">
                <input type="hidden" name="appeal_id" value="<?php echo $ap['id']; ?>">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                <input type="text" name="faculty_comment" placeholder="Attach formal review comment narrative..." required style="flex:1; padding:6px;">
                <button type="submit" class="btn" style="font-size:12px;">Submit Comment</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>