<?php
class FacultyModel {
    private $db;
    public function __construct($conn) { $this->db = $conn; }

    public function getProfile($user_id) {
        $stmt = $this->db->prepare("SELECT f.*, u.name, u.profile_pic, u.email FROM faculty f JOIN users u ON f.user_id = u.id WHERE f.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateProfile($user_id, $name, $desig, $room, $hours, $pic_path) {
        $this->db->begin_transaction();
        try {
            $stmt1 = $this->db->prepare("UPDATE users SET name = ?, profile_pic = ? WHERE id = ?");
            $stmt1->bind_param("ssi", $name, $pic_path, $user_id);
            $stmt1->execute();
            $stmt2 = $this->db->prepare("UPDATE faculty SET designation = ?, office_room = ?, office_hours = ? WHERE user_id = ?");
            $stmt2->bind_param("sssi", $desig, $room, $hours, $user_id);
            $stmt2->execute();
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function getAssignedCourses($user_id) {
        $stmt = $this->db->prepare("SELECT c.*, s.name as semester, s.grade_submission_deadline, COUNT(e.id) as student_count, IFNULL(MAX(ge.is_published), 0) as grade_status 
            FROM courses c 
            JOIN faculty f ON c.faculty_id = f.id 
            JOIN semesters s ON c.semester_id = s.id
            LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'active'
            LEFT JOIN grade_entries ge ON e.id = ge.enrollment_id
            WHERE f.user_id = ? GROUP BY c.id");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getCourseDetails($course_id, $user_id) {
        $stmt = $this->db->prepare("SELECT c.*, s.grade_submission_deadline FROM courses c JOIN faculty f ON c.faculty_id = f.id JOIN semesters s ON c.semester_id = s.id WHERE c.id = ? AND f.user_id = ?");
        $stmt->bind_param("ii", $course_id, $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getCourseStudents($course_id) {
        $stmt = $this->db->prepare("SELECT e.id as enrollment_id, u.name as student_name, s.student_id_number, IFNULL(ge.ct_mark,0) as ct_mark, IFNULL(ge.mid_mark,0) as mid_mark, IFNULL(ge.final_mark,0) as final_mark, IFNULL(ge.attendance_pct,0) as attendance_pct, IFNULL(ge.total_mark,0) as total_mark, IFNULL(ge.letter_grade,'-') as letter_grade, IFNULL(ge.is_published,0) as is_published 
            FROM enrollments e 
            JOIN students s ON e.student_id = s.id 
            JOIN users u ON s.user_id = u.id 
            LEFT JOIN grade_entries ge ON e.id = ge.enrollment_id
            WHERE e.course_id = ? AND e.status = 'active'");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function publishGrades($course_id) {
        $stmt = $this->db->prepare("UPDATE grade_entries ge JOIN enrollments e ON ge.enrollment_id = e.id SET ge.is_published = 1 WHERE e.course_id = ?");
        $stmt->bind_param("i", $course_id);
        return $stmt->execute();
    }

    public function getGradeDistribution($course_id) {
        $stmt = $this->db->prepare("SELECT AVG(ge.total_mark) as avg_mark, MAX(ge.total_mark) as max_mark, MIN(ge.total_mark) as min_mark,
            SUM(CASE WHEN ge.letter_grade != 'F' THEN 1 ELSE 0 END) * 100 / COUNT(e.id) as pass_rate,
            SUM(CASE WHEN ge.letter_grade IN ('A+', 'A') THEN 1 ELSE 0 END) as count_A,
            SUM(CASE WHEN ge.letter_grade LIKE 'B%' THEN 1 ELSE 0 END) as count_B,
            SUM(CASE WHEN ge.letter_grade LIKE 'C%' THEN 1 ELSE 0 END) as count_C,
            SUM(CASE WHEN ge.letter_grade LIKE 'D%' THEN 1 ELSE 0 END) as count_D,
            SUM(CASE WHEN ge.letter_grade = 'F' THEN 1 ELSE 0 END) as count_F
            FROM enrollments e JOIN grade_entries ge ON e.id = ge.enrollment_id WHERE e.course_id = ? AND e.status = 'active'");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAnnouncements($course_id) {
        $stmt = $this->db->prepare("SELECT * FROM course_announcements WHERE course_id = ? ORDER BY id DESC");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addAnnouncement($course_id, $user_id, $title, $body) {
        $stmt = $this->db->prepare("INSERT INTO course_announcements (course_id, author_id, title, body) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $course_id, $user_id, $title, $body);
        return $stmt->execute();
    }

    public function deleteAnnouncement($id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM course_announcements WHERE id = ? AND author_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        return $stmt->execute();
    }

    public function getMaterials($course_id) {
        $stmt = $this->db->prepare("SELECT * FROM course_materials WHERE course_id = ? ORDER BY id DESC");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addMaterial($course_id, $user_id, $title, $path, $type) {
        $stmt = $this->db->prepare("INSERT INTO course_materials (course_id, uploaded_by, title, file_path, material_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $course_id, $user_id, $title, $path, $type);
        return $stmt->execute();
    }

    public function deleteMaterial($id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM course_materials WHERE id = ? AND uploaded_by = ?");
        $stmt->bind_param("ii", $id, $user_id);
        return $stmt->execute();
    }

    public function getAppeals($course_id) {
        $stmt = $this->db->prepare("SELECT aa.*, u.name as student_name, ge.letter_grade FROM academic_appeals aa JOIN enrollments e ON aa.enrollment_id = e.id JOIN students s ON e.student_id = s.id JOIN users u ON s.user_id = u.id LEFT JOIN grade_entries ge ON e.id = ge.enrollment_id WHERE e.course_id = ?");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function submitAppealComment($appeal_id, $comment) {
        $stmt = $this->db->prepare("UPDATE academic_appeals SET faculty_comment = ?, status = 'under_review' WHERE id = ?");
        $stmt->bind_param("si", $comment, $appeal_id);
        return $stmt->execute();
    }
}
?>