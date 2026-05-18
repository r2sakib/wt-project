<?php
if (!isset($events)) {
    header("Location: ../controller/CalendarController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Academic Calendar</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 1000px;">
        <div class="justify-between">
            <h2>Academic Calendar</h2>
            <div>
                <a href="../controller/CalendarController.php?action=add_form" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #28a745;">+ Add Event</button>
                </a>
                <a href="../controller/DashboardController.php" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #6c757d;">Back to Dashboard</button>
                </a>
            </div>
        </div>

        <table>
            <tr>
                <th>Date</th>
                <th>Semester</th>
                <th>Event Name</th>
                <th>Description</th>
                <th>Visible To</th>
                <th>Action</th>
            </tr>
            <?php foreach ($events as $event) { ?>
            <tr>
                <td style="white-space: nowrap;">
                    <strong><?php echo date('M d, Y', strtotime($event['event_date'])); ?></strong>
                </td>
                <td><?php echo $event['semester_name']; ?></td>
                <td><strong><?php echo $event['event_name']; ?></strong></td>
                <td><?php echo $event['description']; ?></td>
                <td style="text-transform: capitalize;"><?php echo $event['visible_to']; ?></td>
                <td>
                    <form action="../controller/CalendarController.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                        <input type="submit" value="Delete" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                    </form>
                </td>
            </tr>
            <?php } ?>
            <?php if (empty($events)) { echo "<tr><td colspan='6' class='text-center'>No events found in the calendar.</td></tr>"; } ?>
        </table>
    </div>
</body>
</html>