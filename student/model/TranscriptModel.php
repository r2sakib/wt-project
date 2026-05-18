<?php

class TranscriptModel {
    private $db;

    public function __construct() {
        $this->db = new mysqli("localhost", "root", "", "portal");
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

  
    public function getStudentProfile($userId) {
        $query = "SELECT s.id AS student_pk, s.student_id_number, s.cgpa AS profile_cgpa, 
                         u.name AS student_name, p.name AS program_name 
                  FROM students s
                  INNER JOIN users u ON s.user_id = u.id
                  INNER JOIN programs p ON s.program_id = p.id
                  WHERE u.id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


    public function updateStudentCGPA($studentPk, $newCgpa) {
        $query = "UPDATE students SET cgpa = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("di", $newCgpa, $studentPk);
        $stmt->execute();
    }

   
    public function getTranscriptRecords($studentPk) {
        $query = "SELECT sem.name AS semester_name, c.code AS course_code, c.title AS course_title, 
                         c.credit_hours, ge.letter_grade, ge.gpa_point, ge.is_published
                  FROM enrollments e
                  INNER JOIN courses c ON e.course_id = c.id
                  INNER JOIN semesters sem ON c.semester_id = sem.id
                  LEFT JOIN grade_entries ge ON ge.enrollment_id = e.id
                  WHERE e.student_id = ? AND e.status != 'dropped'
                  ORDER BY sem.start_date ASC, c.code ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $studentPk);
        $stmt->execute();
        return $stmt->get_result();
    }

    
}
?>