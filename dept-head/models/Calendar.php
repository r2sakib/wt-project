<?php
function getDepartmentIdByHead($conn, $head_id) {
    $department_id = null;
    $stmt = $conn->prepare("SELECT id FROM departments WHERE head_id = ? LIMIT 1");
    $stmt->bind_param("i", $head_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $department_id = $row['id'];
    }
    $stmt->close();
    return $department_id;
}

function getEventsForDepartmentHead($conn, $department_id) {
    $events = [];
    
    $query = "SELECT * FROM academic_calendar 
              WHERE visible_to = 'all' 
                 OR visible_to = 'head' 
                 OR visible_to = (SELECT code FROM departments WHERE id = ?)
              ORDER BY event_date ASC";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    $stmt->close();
    return $events;
}
?>