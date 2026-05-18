<!DOCTYPE html>
<html>
<head>
    <title>Add Department</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container">
        <h2>Add New Department</h2>
        
        <form action="../controller/DepartmentController.php" method="POST">
            <input type="hidden" name="action" value="add_submit">
            
            <label>Department Name:</label>
            <input type="text" name="name" required placeholder="e.g. Computer Science">
            
            <label>Department Code:</label>
            <input type="text" name="code" required placeholder="e.g. CS">
            
            <label>Description:</label>
            <textarea name="description" rows="4" style="width: 100%; margin-bottom: 15px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
            
            <div class="justify-between">
                <a href="DepartmentController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Save Department" style="background: #28a745; color: white;">
            </div>
        </form>
    </div>
</body>
</html>