<!DOCTYPE html>
<html>
<head>
    <title>Edit Department</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Department</h2>
        
        <form action="../controller/DepartmentController.php" method="POST">
            <input type="hidden" name="action" value="edit_submit">
            <input type="hidden" name="dept_id" value="<?php echo $department['id']; ?>">
            
            <label>Department Name</label>
            <input type="text" name="name" value="<?php echo $department['name']; ?>" required>
            
            <label>Department Code</label>
            <input type="text" name="code" value="<?php echo $department['code']; ?>" required>
            
            <label>Assign Department Head</label>
            <select name="head_id">
                <option value="">-- No Head Assigned --</option>
                <?php foreach ($eligible_heads as $head) { ?>
                    <option value="<?php echo $head['id']; ?>" <?php if($department['head_id'] == $head['id']) echo 'selected'; ?>>
                        <?php echo $head['name']; ?> (<?php echo $head['email']; ?>)
                    </option>
                <?php } ?>
            </select><br><br>

            <label>Description:</label>
            <textarea name="description" rows="4" style="width: 100%; margin-bottom: 15px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"><?php echo $department['description']; ?></textarea>
            
            <div class="justify-between">
                <a href="DepartmentController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Update Department" style="background: #007bff; color: white;">
            </div>
        </form>
    </div>
</body>
</html>