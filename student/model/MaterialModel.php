<?php
class MaterialModel {
    private $db;

    public function __construct() {
        $this->db = new mysqli("localhost", "root", "", "portal");
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    public function getMaterialsForStudent($userId) {
        $query = "SELECT 
                    cm.id AS material_id,
                    cm.title AS material_title,
                    cm.file_path,
                    c.code AS course_code,
                    c.title AS course_title,
                    u.name AS uploader_name
                  FROM course_materials cm
                  INNER JOIN courses c ON cm.course_id = c.id
                  INNER JOIN enrollments e ON e.course_id = c.id
                  INNER JOIN students s ON e.student_id = s.id
                  INNER JOIN users u ON cm.uploaded_by = u.id
                  WHERE s.user_id = ? AND e.status = 'active'";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

   
    public function getMaterialById($materialId) {
        $query = "SELECT * FROM course_materials WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $materialId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

   
    public function getNextImportantDate() {
        $query = "SELECT event_name, event_date, description 
                  FROM academic_calendar 
                  WHERE event_date >= CURDATE() 
                  AND visible_to IN ('all', 'student') 
                  ORDER BY event_date ASC 
                  LIMIT 1";
                  
        $result = $this->db->query($query);
        return $result ? $result->fetch_assoc() : null;
    }
}
?>