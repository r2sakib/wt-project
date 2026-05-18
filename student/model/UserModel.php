<?php
require_once dirname(__DIR__) . '/config/db.php';

class UserModel {
    private $db;

    public function __construct() {
        global $conn;
        $this->db = $conn;
    }

    public function register($data) {

        $stmt1 = $this->db->prepare("INSERT INTO users (name, email, role, password_hash) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("ssss", $data['name'], $data['email'], $data['role'], $data['password']);
        
        if ($stmt1->execute()) {
           
            $new_user_id = $this->db->insert_id;
            
           
            $stmt2 = $this->db->prepare("INSERT INTO students (user_id, student_id_number, program_id) VALUES (?, ?, ?)");
            $stmt2->bind_param("isi", $new_user_id, $data['student_id'], $data['program_id']);
            
            return $stmt2->execute();
        }
        return false;
    }
    public function dropCourseWithGrades($userId, $courseId) {
        
        $stmt = $this->db->prepare("SELECT id FROM students WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $student = $stmt->get_result()->fetch_assoc();
        
        
        $studentId = $student ? $student['id'] : 1;

        $this->db->begin_transaction();
        try {
            
            $stmt1 = $this->db->prepare("DELETE FROM grades WHERE student_id = ? AND course_id = ?");
            $stmt1->bind_param("ii", $studentId, $courseId);
            $stmt1->execute();

            
            $stmt2 = $this->db->prepare("DELETE FROM enrollments WHERE student_id = ? AND course_id = ?");
            $stmt2->bind_param("ii", $studentId, $courseId);
            $stmt2->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
        public function login($email) {
        $stmt = $this->db->prepare("SELECT users.*, students.student_id_number FROM users LEFT JOIN students ON users.id = students.user_id WHERE users.email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    
    public function updateProfile($id, $name, $email, $phone) {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
        return $stmt->execute();
    }

   
    public function updatePicture($id, $filename) {
        $stmt = $this->db->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
        $stmt->bind_param("si", $filename, $id);
        return $stmt->execute();
    }

    
    public function changePassword($id, $hashedPassword) {
        $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $id);
        return $stmt->execute();
    }
    
    public function checkDuplicateId($student_id) {
        $stmt = $this->db->prepare("SELECT id FROM students WHERE student_id_number = ?");
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0; 
    }


    public function checkDuplicateEmail($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

}
?>