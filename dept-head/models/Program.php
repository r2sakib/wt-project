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

function getProgramsByDepartment($conn, $department_id) {
    $programs = [];
    $stmt = $conn->prepare("SELECT * FROM programs WHERE department_id = ? ORDER BY name ASC");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $programs[] = $row;
    }
    
    $stmt->close();
    return $programs;
}

function addProgram($conn, $department_id, $name, $code, $credits, $duration, $description) {
    $stmt = $conn->prepare("INSERT INTO programs (department_id, name, code, total_credit_hours, duration_years, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issiis", $department_id, $name, $code, $credits, $duration, $description);
    
    $success = $stmt->execute();
    $stmt->close();
    
    return $success;
}

function deleteProgram($conn, $id, $department_id) {
    $stmt = $conn->prepare("DELETE FROM programs WHERE id = ? AND department_id = ?");
    $stmt->bind_param("ii", $id, $department_id);
    
    $success = $stmt->execute();
    $stmt->close();
    
    return $success;
}

function getProgramById($conn, $id, $department_id) {
    $stmt = $conn->prepare("SELECT * FROM programs WHERE id = ? AND department_id = ? LIMIT 1");
    $stmt->bind_param("ii", $id, $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $program = null;
    if ($row = $result->fetch_assoc()) {
        $program = $row;
    }
    
    $stmt->close();
    return $program;
}

function updateProgram($conn, $id, $department_id, $name, $code, $credits, $duration, $description) {
    $stmt = $conn->prepare("UPDATE programs SET name=?, code=?, total_credit_hours=?, duration_years=?, description=? WHERE id=? AND department_id=?");
    $stmt->bind_param("ssiisii", $name, $code, $credits, $duration, $description, $id, $department_id);
    
    $success = $stmt->execute();
    $stmt->close();
    
    return $success;
}
?>