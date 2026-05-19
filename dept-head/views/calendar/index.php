<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require_once __DIR__ . '/../../controllers/CalendarController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/programIndex.css">
    <title>Department Calendar</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Department Calendar & Schedules</h2>
                <p class="text-muted">Track upcoming institutional deadlines, exams, and semester event structures.</p>
            </div>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Event Name</th>
                        <th>Audience / Visibility</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 3rem; color: #777;">
                                No scheduled events found in the academic calendar.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td class="font-medium" style="white-space: nowrap;">
                                    <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                                </td>
                                <td style="font-weight: 600; color: #1e293b;">
                                    <?php echo htmlspecialchars($event['event_name']); ?>
                                </td>
                                <td>
                                    <?php 
                                    $visibility = strtolower($event['visible_to']);
                                    $badgeClass = 'badge-active';
                                    if ($visibility === 'all') $badgeClass = 'badge-active';
                                    if ($visibility === 'head') $badgeClass = 'badge-pending';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo strtoupper(htmlspecialchars($event['visible_to'])); ?>
                                    </span>
                                </td>
                                <td style="max-width: 400px; white-space: normal; word-wrap: break-word; color: #475569;">
                                    <?php echo htmlspecialchars($event['description']); ?>
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