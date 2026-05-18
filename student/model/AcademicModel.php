<?php
class AcademicModel {
    private $db;
 
    public function __construct() {
        $this->db = new mysqli("localhost", "root", "", "portal"); 
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }
 
    public function getStudentInfo($userId) {
        $query = "SELECT s.id AS student_id, s.current_semester, s.cgpa, s.academic_status, sem.drop_deadline 
                  FROM students s
                  LEFT JOIN semesters sem ON sem.is_current = 1
                  WHERE s.user_id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
 
    public function getEnrolledCourses($userId) {
        $query = "SELECT c.id AS course_id, c.code, c.title, c.credit_hours, c.max_seats, e.status,
                         ge.is_published, ge.letter_grade, ge.gpa_point,
                         (c.max_seats - (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id)) AS remaining_seats
                  FROM enrollments e
                  INNER JOIN courses c ON e.course_id = c.id
                  INNER JOIN students s ON e.student_id = s.id
                  LEFT JOIN grade_entries ge ON ge.enrollment_id = e.id
                  WHERE s.user_id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }
 
  
    public function searchAvailableCourses($userId, $searchTerm = '') {
        $studentInfo = $this->getStudentInfo($userId);
        if (!$studentInfo) return null;
 
        $studentId = $studentInfo['student_id'];
        $likeTerm = "%" . $searchTerm . "%";
 
      
        $query = "SELECT c.id AS course_id, c.code, c.title, c.credit_hours, c.max_seats, c.status,
                         (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) AS filled_seats,
                         (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND student_id = ?) AS is_enrolled 
                  FROM courses c";
 
        
        if (!empty($searchTerm)) {
            $query .= " WHERE (c.code LIKE ? OR c.title LIKE ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iss", $studentId, $likeTerm, $likeTerm);
        } else {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $studentId);
        }
 
        $stmt->execute();
        return $stmt->get_result();
    }
 
    // Enrollcourse 
    public function enrollCourse($userId, $courseId) {
       
        $stmt = $this->db->prepare("SELECT id FROM students WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $student = $stmt->get_result()->fetch_assoc();
        if (!$student) {
            return ["success" => false, "message" => "Student profile not found."];
        }
        $studentId = $student['id'];
 
        
        $stmt = $this->db->prepare("SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $studentId, $courseId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            return ["success" => false, "message" => "You are already enrolled in this course."];
        }
 
        
        $stmt = $this->db->prepare("SELECT max_seats, status FROM courses WHERE id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $course = $stmt->get_result()->fetch_assoc();
        if (($course['status'] ?? 'closed') !== 'open') {
            return ["success" => false, "message" => "This course section is currently closed."];
        }
 
        
        $stmt = $this->db->prepare("SELECT COUNT(*) AS filled FROM enrollments WHERE course_id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $countData = $stmt->get_result()->fetch_assoc();
        if ($countData['filled'] >= $course['max_seats']) {
            return ["success" => false, "message" => "No seats remaining! Enrollment failed."];
        }
 
       
        $stmt = $this->db->prepare("INSERT INTO enrollments (student_id, course_id, status) VALUES (?, ?, 'active')");
        $stmt->bind_param("ii", $studentId, $courseId);
        
        if ($stmt->execute()) {
            return ["success" => true, "message" => "Enrolled successfully!"];
        } else {
            return ["success" => false, "message" => "Enrollment failed due to a database issue."];
        }
    }
 
    // Drop a course and remove 
    public function dropCourse($userId, $courseId) {
        $studentInfo = $this->getStudentInfo($userId);
        
        
        if ($studentInfo && !empty($studentInfo['drop_deadline'])) {
            if (strtotime(date('Y-m-d')) > strtotime($studentInfo['drop_deadline'])) {
                return ["success" => false, "message" => "Cannot drop course. The drop deadline has passed!"];
            }
        }
 
        $studentId = $studentInfo['student_id'] ?? null;
        if (!$studentId) {
            return ["success" => false, "message" => "Student session missing."];
        }
 
        
        $stmt = $this->db->prepare("SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $studentId, $courseId);
        $stmt->execute();
        $enrollment = $stmt->get_result()->fetch_assoc();
 
        if ($enrollment) {
            $enrollmentId = $enrollment['id'];
            $delGrades = $this->db->prepare("DELETE FROM grade_entries WHERE enrollment_id = ?");
            $delGrades->bind_param("i", $enrollmentId);
            $delGrades->execute();
        }
 
        
        $delEnroll = $this->db->prepare("DELETE FROM enrollments WHERE student_id = ? AND course_id = ?");
        $delEnroll->bind_param("ii", $studentId, $courseId);
        
        if ($delEnroll->execute()) {
            return ["success" => true, "message" => "Course dropped completely."];
        } else {
            return ["success" => false, "message" => "Failed to drop the course."];
        }
    }
 
    // Fetch live CGPA 
    public function getLiveCGPA($userId) {
        $query = "SELECT COALESCE(SUM(ge.gpa_point * c.credit_hours) / NULLIF(SUM(c.credit_hours), 0), 0.00) AS live_cgpa,
                         COALESCE(SUM(c.credit_hours), 0) AS total_credits
                  FROM enrollments e
                  INNER JOIN students s ON e.student_id = s.id
                  INNER JOIN courses c ON e.course_id = c.id
                  INNER JOIN grade_entries ge ON ge.enrollment_id = e.id
                  WHERE s.user_id = ? AND ge.is_published = 1 AND UPPER(ge.letter_grade) != 'F' AND ge.gpa_point > 0";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // registration and logic=

    
    public function checkDuplicateEmail($email) {
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query); 
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

   
    public function checkDuplicateStudentId($studentIdNum) {
        $query = "SELECT id FROM students WHERE student_id_number = ?";
        $stmt = $this->db->prepare($query); 
        $stmt->bind_param("s", $studentIdNum);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    
    public function register($data) {
        
        $this->db->begin_transaction();

        try {
            
            $query1 = "INSERT INTO users (name, email, role, password_hash) VALUES (?, ?, ?, ?)";
            $stmt1 = $this->db->prepare($query1);
            $stmt1->bind_param("ssss", $data['name'], $data['email'], $data['role'], $data['password']);
            $stmt1->execute();

            
            $newUserId = $this->db->insert_id;

            
            $query2 = "INSERT INTO students (user_id, student_id_number, program_id, current_semester) VALUES (?, ?, ?, 1)";
            $stmt2 = $this->db->prepare($query2);
            $stmt2->bind_param("isi", $newUserId, $data['student_id'], $data['program_id']);
            $stmt2->execute();

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            
            $this->db->rollback();
            return false;
        }
    }

    public function login($email) {
  
            $query = "SELECT u.*, s.student_id_number 
                    FROM users u
                    LEFT JOIN students s ON u.id = s.user_id 
                    WHERE u.email = ?";
            $stmt = $this->db->prepare($query); 
            $stmt->bind_param("s", $email);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
}
?>