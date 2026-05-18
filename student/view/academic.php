<?php 

require_once("../controller/AcademicController.php");
 
$loop_total_points  = 0.00;
$loop_total_credits = 0;
$loop_live_cgpa     = 0.00;
 
$coursesArray = [];
if (isset($enrolledCourses) && $enrolledCourses->num_rows > 0) {
    while ($course = $enrolledCourses->fetch_assoc()) {
        $coursesArray[] = $course;
 
        
        if (isset($course['is_published']) && $course['is_published'] == 1) {
            if (strtoupper($course['letter_grade'] ?? '') !== 'F' && ($course['gpa_point'] ?? 0) > 0) {
                $loop_total_points += ($course['gpa_point'] * $course['credit_hours']);
                $loop_total_credits += $course['credit_hours'];
            }
        }
    }
}
 
if ($loop_total_credits > 0) {
    $loop_live_cgpa = $loop_total_points / $loop_total_credits;
}
 
$initial_standing = "Good Standing";
if ($loop_live_cgpa < 2.00 && $loop_total_credits > 0) {
    $initial_standing = "Probation";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Academic Portal</title>
</head>
<body>

    <table width="100%">
        <tr>
            <td><h2>Academic Dashboard</h2></td>
            <td align="right"><a href="dashboard.php"><h3><b>Back</b></h3></a></td>
        </tr>
    </table>
    <hr>
 
   
    <table>
        <tr>
            <td><b>Current Semester:</b></td>
            <td><?php echo htmlspecialchars($studentData['current_semester'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <td><b>Live CGPA:</b></td>
            <td><b id="live-cgpa"><?php echo number_format($loop_live_cgpa, 2); ?></b></td>
        </tr>
        <tr>
            <td><b>Credits:</b></td>
            <td><b id="live-credits"><?php echo $loop_total_credits; ?></b> Credits</td>
        </tr>
        <tr>
            <td><b>Academic Standing:</b></td>
            <td><b id="live-standing"><?php echo htmlspecialchars($initial_standing); ?></b></td>
        </tr>
        <tr>
            <td><b>Next Calendar Date:</b></td>
            <td><?php echo htmlspecialchars($studentData['drop_deadline'] ?? 'N/A'); ?></td>
        </tr>
    </table>
 
    <br><hr>
 
   
    <h3>Enrolled Courses</h3>
 
    <table border="1" cellpadding="6" cellspacing="0" width="60%" id="enrolled-table">
        <thead>
            <tr>
                <th align="left">Code</th>
                <th align="left">Title</th>
                <th align="center">Credits</th>
                <th align="center">Remaining Seats</th>
                <th align="center">Enrollment Status</th>
                <th align="center">Action</th>
            </tr>
        </thead>
        <tbody id="enrolled-table-body">
            <?php if (!empty($coursesArray)): ?>
                <?php foreach ($coursesArray as $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['code']); ?></td>
                        <td><?php echo htmlspecialchars($course['title']); ?></td>
                        <td align="center"><?php echo htmlspecialchars($course['credit_hours']); ?></td>
                        <td align="center">
                            <b><?php 
                                $max = $course['max_seats'] ?? 40;
                                $remaining = $course['remaining_seats'] ?? $max;
                                echo htmlspecialchars($remaining . " / " . $max);
                            ?></b>
                        </td>
                        <td><?php echo htmlspecialchars(ucfirst($course['status'] ?? 'Active')); ?></td>
                        <td align="center">
                            <form method="POST" action="../controller/AcademicController.php" class="drop-form" style="margin:0;">
                                <input type="hidden" name="action" value="drop_course">
                                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars(!empty($course['course_id']) ? $course['course_id'] : $course['id']); ?>">
                                <input type="submit" value="Drop Course">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" align="center">No enrolled courses found for this semester.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
 
    <br><hr>
 
   
    <h3>Browse & Enroll in Courses</h3>
    <p>
        Search Available Courses (live filter)<br>
        <input type="text" id="courseSearch" onkeyup="fetchCourses()" placeholder="Type course code or title..." autocomplete="off" style="width: 250px;">
    </p>
 
    <table border="1" cellpadding="6" cellspacing="0" width="60%">
        <thead>
            <tr>
                <th align="left">Course Code</th>
                <th align="left">Course Title</th>
                <th align="center">Credit Hours</th>
                <th align="center">Seats Remaining</th>
                <th align="center">Action</th>
            </tr>
        </thead>
        <tbody id="courseTableBody">
           
        </tbody>
    </table>
 
   
    <script>
    
    function fetchLiveCGPA() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../controller/AcademicController.php?action=get_live_cgpa', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var d = JSON.parse(xhr.responseText);
                document.getElementById('live-cgpa').textContent = d.cgpa;
                document.getElementById('live-credits').textContent = d.credits;
                document.getElementById('live-standing').textContent = d.standing;
            }
        };
        xhr.send();
    }
 
   
    function fetchEnrolledRows() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../controller/AcademicController.php?action=get_enrolled_rows', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('enrolled-table-body').innerHTML = xhr.responseText;
                attachDropListeners(); 
            }
        };
        xhr.send();
    }
 
    
    function fetchCourses() {
        var query = document.getElementById('courseSearch').value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../controller/AcademicController.php?action=search_courses&query=' + encodeURIComponent(query), true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('courseTableBody').innerHTML = xhr.responseText;
                attachEnrollListeners(); 
            }
        };
        xhr.send();
    }
 

    function attachEnrollListeners() {
        var forms = document.querySelectorAll('#courseTableBody .enroll-form');
        forms.forEach(function (form) {
            var btn = form.querySelector('input[type="submit"]');
            if (btn && btn.disabled) return; 
 
            var newForm = form.cloneNode(true);
            form.parentNode.replaceChild(newForm, form);
 
            newForm.addEventListener('submit', function (e) {
                e.preventDefault();
                
                var currentBtn = newForm.querySelector('input[type="submit"]');
                if (currentBtn) { 
                    currentBtn.value = 'Enrolling...'; 
                    currentBtn.disabled = true; 
                }
 
                var data = new FormData(newForm);
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../controller/AcademicController.php', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            var resp = JSON.parse(xhr.responseText);
                            if (resp.success === false) {
                                alert(resp.message);
                            }
                        }
                        fetchCourses();      
                        fetchEnrolledRows(); 
                        fetchLiveCGPA();     
                    }
                };
                xhr.send(data);
            });
        });
    }
 
   
    function attachDropListeners() {
        var forms = document.querySelectorAll('#enrolled-table-body .drop-form');
        forms.forEach(function (form) {
            var newForm = form.cloneNode(true);
            form.parentNode.replaceChild(newForm, form);
 
            newForm.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!confirm('Are you sure you want to drop this course?')) return;
 
                var btn = newForm.querySelector('input[type="submit"]');
                if (btn) { btn.value = 'Dropping...'; btn.disabled = true; }
 
                var data = new FormData(newForm);
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../controller/AcademicController.php', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
 
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            var resp = JSON.parse(xhr.responseText);
                            if (resp.success === false) {
                                alert(resp.message);
                            }
                        }
                        fetchEnrolledRows(); 
                        fetchCourses();      
                        fetchLiveCGPA();     
                    }
                };
                xhr.send(data);
            });
        });
    }
 
  
    window.onload = function () {
        fetchCourses();
        fetchEnrolledRows();
        fetchLiveCGPA();
    };
    </script>
</body>
</html>