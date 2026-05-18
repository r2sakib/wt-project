<?php 
require_once(__DIR__ . "/../controller/MaterialController.php");
// Removed the duplicate session_start() from here!
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <table width="95%">
        <tr>
            <td>
               <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Student'); ?>!</h1>
            </td>
            <td align="right">
                <h3>
                    <b>
                        <a href="ManageProfile.php">Profile</a> | 
                        <a href="logout.php">Logout</a>
                    </b>
                </h3>
            </td>
        </tr>
    </table>
    <br><br>
    <table width="50%" align="center">
        <tr>
            <td align="center"> <a href="academic.php">Academics</a></td> 
            <td align="center"> <a href="grades.php">Grades</a></td> 
            <td align="center"> <a href="Transcript.php">Transcript</a></td>
        </tr>
    </table>
    <br>
    <hr>

    <h3>Next Important Date: </h3>
        <?php if (!empty($nextEvent)): ?>
            <span>
                <?php echo htmlspecialchars($nextEvent['event_name']); ?> 
                (<?php echo date('F d, Y', strtotime($nextEvent['event_date'])); ?>)
            </span>
            <?php if (!empty($nextEvent['description'])): ?>
                
                    - <?php echo htmlspecialchars($nextEvent['description']); ?>
                
            <?php endif; ?>
        <?php else: ?>
            <span>No upcoming events scheduled.</span>
        <?php endif; ?>
    </h3>

    <h3>Access Course Materials:</h3>

        <table border="1" cellpadding="6" cellspacing="0" width="90%">
            <thead>
                <tr>
                    <th align="left">Associated Course</th>
                    <th align="left">Document Title</th>
                    <th align="left">Uploaded By</th>
                    <th align="center" width="15%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($materialsList)): ?>
                    <?php foreach ($materialsList as $material): ?>
                        <tr>
                            <td><b><?php echo htmlspecialchars($material['course_code']); ?></b> - <?php echo htmlspecialchars($material['course_title']); ?></td>
                            <td><?php echo htmlspecialchars($material['material_title']); ?></td>
                            <td><?php echo htmlspecialchars($material['uploader_name']); ?></td>
                            <td align="center">
                                <a href="../controller/MaterialController.php?action=download_material&id=<?php echo $material['material_id']; ?>">
                                    <b>Download Document</b>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" align="center">No learning documents are currently uploaded to this repository.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        </div>
</body>
</html>