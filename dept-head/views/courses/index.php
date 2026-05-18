<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../controllers/CourseController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/programIndex.css">
    <title>Courses</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Courses & Faculty Management</h2>
                <p class="text-muted">Manage academic courses, section rules, and faculty allocations.</p>
            </div>
            <div class="header-actions">
                <button class="btn-primary" id="btn-add-course">
                    <span class="icon">➕</span> Add New Course
                </button>
            </div>
        </div>

        <?php if(isset($_SESSION['success_msg'])): ?>
            <div style="background: #d1fae5; color: #065f46; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error_msg'])): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                <?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
            </div>
        <?php endif; ?>

        <div class="table-card" style="margin-bottom: 2rem; padding: 2rem;">
            <form id="course-filter-form" style="display: flex; gap: 1.5rem; align-items: flex-end;">
                <div style="flex: 1;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Filter by Program</label>
                    <select id="filter-program" style="width: 100%; padding: 0.8rem; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">All Programs</option>
                        <?php foreach($programs as $p): ?>
                            <option value="<?php echo htmlspecialchars($p['name']); ?>"><?php echo htmlspecialchars($p['code']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Filter by Status</label>
                    <select id="filter-status" style="width: 100%; padding: 0.8rem; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">All Statuses</option>
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary" style="height: fit-content; padding: 0.8rem 2rem;">Apply Filter</button>
            </form>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Course Title</th>
                        <th>Program</th>
                        <th>Faculty Assigned</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($courses)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: #777;">
                                No courses found for your department.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($courses as $c): ?>
                            <tr>
                                <td class="font-medium"><?php echo htmlspecialchars($c['code']); ?></td>
                                <td><?php echo htmlspecialchars($c['title']); ?></td>
                                <td><?php echo htmlspecialchars($c['program_name']); ?></td>
                                <td><?php echo htmlspecialchars($c['faculty_name'] ?? 'Unassigned'); ?></td>
                                <td>
                                    <span class="badge <?php echo ($c['status'] === 'open') ? 'badge-active' : 'badge-inactive'; ?>">
                                        <?php echo ucfirst(htmlspecialchars($c['status'])); ?>
                                    </span>
                                end
                                <td class="text-right" style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                    <button class="btn-icon btn-assign-faculty" title="Assign Faculty" data-id="<?php echo $c['id']; ?>">👤</button>
                                    <button class="btn-icon btn-edit-course" title="Edit" data-id="<?php echo $c['id']; ?>">✏️</button>
                                    <form action="controllers/CourseController.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" style="margin: 0;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="course_id" value="<?php echo $c['id']; ?>">
                                        <button type="submit" class="btn-icon btn-danger" style="border: none; background: none;">🛑</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>