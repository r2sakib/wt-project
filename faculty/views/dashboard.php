<div class="profile-layout">
    <div class="card avatar-card">
       <img src="<?php echo !empty($profile['profile_pic']) ? $profile['profile_pic'] : 'uploads/profiles/default.png'; ?>" 
             alt="Avatar" 
             class="avatar-image" 
             onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($profile['name']); ?>&background=random';">
        <p style="color:#64748b; margin:0; font-size:13px;"><?php echo htmlspecialchars($profile['designation']); ?></p>
        <div class="meta-list">
            <p><strong>Room:</strong> <?php echo htmlspecialchars($profile['office_room']); ?></p>
            <p><strong>Hours:</strong> <?php echo htmlspecialchars($profile['office_hours']); ?></p>
        </div>
    </div>

    <div class="card form-card">
        <h3 style="margin-top:0;">Edit Faculty Profile Details</h3>
        <form action="index.php?action=update_profile" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-row">
                    <label>Full Profile Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($profile['name']); ?>" required>
                </div>
                <div class="form-row">
                    <label>Designation</label>
                    <input type="text" name="designation" value="<?php echo htmlspecialchars($profile['designation']); ?>" required>
                </div>
                <div class="form-row">
                    <label>Office Room Location</label>
                    <input type="text" name="office_room" value="<?php echo htmlspecialchars($profile['office_room']); ?>" required>
                </div>
                <div class="form-row">
                    <label>Office Consultation Hours</label>
                    <input type="text" name="office_hours" value="<?php echo htmlspecialchars($profile['office_hours']); ?>" required>
                </div>
            </div>
            <div style="margin-bottom:15px;">
                <label style="font-size:12px; font-weight:600; display:block; margin-bottom:4px; color:#2563eb;">Upload Profile Picture Asset</label>
                <input type="file" name="profile_pic" accept="image/*">
            </div>
            <button type="submit" class="btn">Change Profile</button>
        </form>
    </div>
</div>

<div class="card">
    <h3>Assigned Semester Courses Dashboard</h3>
    <table>
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Active Semester</th>
                <th>Enrolled Counts</th>
                <th>Submission Deadline</th>
                <th>Grade Status</th>
                <th>Operations</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $c): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($c['code']); ?></strong></td>
                <td><?php echo htmlspecialchars($c['title']); ?></td>
                <td><?php echo htmlspecialchars($c['semester']); ?></td>
                <td><?php echo $c['student_count']; ?> Students</td>
                <td><span style="color:#b91c1c; font-weight:600;"><?php echo htmlspecialchars($c['grade_submission_deadline']); ?></span></td>
                <td><?php echo ($c['grade_status'] == 1) ? '<span style="color:green; font-weight:600;">Published</span>' : '<span style="color:orange; font-weight:600;">Draft Mode</span>'; ?></td>
                <td><a class="btn" href="index.php?action=course_manage&course_id=<?php echo $c['id']; ?>">Manage Course</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>