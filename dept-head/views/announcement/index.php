<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/announcement.css">

    <title>Announcement</title>
</head>

<body>
    <div class="announcements-container">

        <div class="page-header">
            <div class="header-info">
                <h2>Department Announcements</h2>
                <p class="text-muted">Broadcast official notices, deadlines, and news to students and faculty.</p>
            </div>
            <div class="header-actions">
                <button class="btn-primary">
                    <span class="icon">📢</span> New Announcement
                </button>
            </div>
        </div>

        <div class="filter-card">
            <form class="filter-form" id="announcements-filter-form">

                <div class="filter-group" style="flex: 2; min-width: 30rem;">
                    <label for="search-announcement">Search Notices</label>
                    <input type="text" id="search-announcement" name="search" placeholder="Search keywords..." class="form-input">
                </div>

                <div class="filter-group">
                    <label for="filter-audience">Target Audience</label>
                    <select id="filter-audience" name="audience">
                        <option value="all">Everyone</option>
                        <option value="students">Students Only</option>
                        <option value="faculty">Faculty Only</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-primary btn-sm">Filter</button>
                </div>
            </form>
        </div>

        <div class="announcement-feed">

            <div class="announcement-card pinned">
                <div class="announcement-header">
                    <div class="title-wrapper">
                        <span class="pin-icon" title="Pinned Announcement">📌</span>
                        <h3 class="announcement-title">Mandatory Curriculum Review Meeting</h3>
                    </div>
                    <div class="announcement-actions">
                        <button class="btn-icon" title="Edit">✏️</button>
                        <button class="btn-icon text-danger" title="Delete">🗑️</button>
                    </div>
                </div>

                <div class="announcement-meta">
                    <span class="meta-item text-muted">Posted by: <strong>You</strong></span>
                    <span class="meta-separator">&bull;</span>
                    <span class="meta-item text-muted">May 17, 2026</span>
                    <span class="meta-separator">&bull;</span>
                    <span class="badge badge-purple">Faculty Only</span>
                </div>

                <div class="announcement-body">
                    <p>Dear Faculty, please remember that our end-of-year curriculum review is scheduled for next Thursday. All course coordinators must submit their syllabus revision proposals by Tuesday end of day. Attendance is mandatory.</p>
                </div>
            </div>

            <div class="announcement-card">
                <div class="announcement-header">
                    <h3 class="announcement-title">Fall 2026 Registration Opens Tomorrow</h3>
                    <div class="announcement-actions">
                        <button class="btn-icon" title="Edit">✏️</button>
                        <button class="btn-icon text-danger" title="Delete">🗑️</button>
                    </div>
                </div>

                <div class="announcement-meta">
                    <span class="meta-item text-muted">Posted by: <strong>Registrar's Office</strong></span>
                    <span class="meta-separator">&bull;</span>
                    <span class="meta-item text-muted">May 16, 2026</span>
                    <span class="meta-separator">&bull;</span>
                    <span class="badge badge-active">Students Only</span>
                </div>

                <div class="announcement-body">
                    <p>Registration for the Fall 2026 semester officially opens tomorrow at 8:00 AM. Please ensure you have met with your academic advisor and cleared any outstanding financial holds on your account before attempting to register.</p>
                </div>

                <div class="announcement-attachments">
                    <a href="#" class="attachment-link">📄 Fall_Course_Catalog.pdf</a>
                </div>
            </div>

        </div>

    </div>
</body>

</html>