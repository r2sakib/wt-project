<?php
function getUserByEmail($conn, $email) {
    $query = "SELECT id, name, password_hash, role FROM users WHERE email = ? AND is_active = TRUE LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = null;
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    }
    
    $stmt->close();
    return $user;
}
?>