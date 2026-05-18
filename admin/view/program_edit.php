<!DOCTYPE html>
<html>
<head>
    <title>Edit Programme</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Degree Programme</h2>
        
        <form action="../controller/ProgramController.php" method="POST">
            <input type="hidden" name="action" value="edit_submit">
            <input type="hidden" name="prog_id" value="<?php echo $program['id']; ?>">
            
            <label>Select Department</label>
            <select name="department_id" required>
                <?php foreach ($departments as $dept) { ?>
                    <option value="<?php echo $dept['id']; ?>" <?php if($program['department_id'] == $dept['id']) echo 'selected'; ?>>
                        <?php echo $dept['name']; ?> (<?php echo $dept['code']; ?>)
                    </option>
                <?php } ?>
            </select><br><br>

            <label>Programme Name</label>
            <input type="text" name="name" value="<?php echo $program['name']; ?>" required>
            
            <label>Programme Code</label>
            <input type="text" name="code" value="<?php echo $program['code']; ?>" required>
            
            <div class="row">
                <div class="col">
                    <label>Total Credit Hours</label>
                    <input type="number" name="total_credit_hours" value="<?php echo $program['total_credit_hours']; ?>" required min="1" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="col">
                    <label>Duration (Years)</label>
                    <input type="number" name="duration_years" value="<?php echo $program['duration_years']; ?>" required min="1" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div><br>

            <label>Description:</label>
            <textarea name="description" rows="4" style="width: 100%; margin-bottom: 15px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"><?php echo $program['description']; ?></textarea>
            
            <div class="justify-between">
                <a href="ProgramController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Update Programme" style="background: #007bff; color: white;">
            </div>
        </form>
    </div>
</body>
</html>