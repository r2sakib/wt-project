<!DOCTYPE html>
<html>
<head>
    <title>Add Programme</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container">
        <h2>Add New Degree Programme</h2>
        
        <form action="../controller/ProgramController.php" method="POST">
            <input type="hidden" name="action" value="add_submit">
            
            <label>Select Department</label>
            <select name="department_id" required>
                <option value="">-- Select a Department --</option>
                <?php foreach ($departments as $dept) { ?>
                    <option value="<?php echo $dept['id']; ?>"><?php echo $dept['name']; ?> (<?php echo $dept['code']; ?>)</option>
                <?php } ?>
            </select><br><br>

            <label>Programme Name</label>
            <input type="text" name="name" required placeholder="e.g. Bachelor of Science in Computer Science and Engineering">
            
            <label>Programme Code</label>
            <input type="text" name="code" required placeholder="e.g. BSc CSE">
            
            <div class="row">
                <div class="col">
                    <label>Total Credit Hours</label>
                    <input type="number" name="total_credit_hours" required min="1" placeholder="e.g. 148" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="col">
                    <label>Duration (Years)</label>
                    <input type="number" name="duration_years" required min="1" placeholder="e.g. 4" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div><br>

            <label>Description</label>
            <textarea name="description" rows="4" style="width: 100%; margin-bottom: 15px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
            
            <div class="justify-between">
                <a href="ProgramController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Save Programme" style="background: #28a745; color: white;">
            </div>
        </form>
    </div>
</body>
</html>