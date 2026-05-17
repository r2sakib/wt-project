<?php
require_once __DIR__ . '/../models/FacultyModel.php';

class FacultyController {
    private $model;
    private $faculty_id;

    public function __construct($conn) {
        $this->model = new FacultyModel($conn);
        $this->faculty_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    }

    public function showDashboard() {
        $profile = $this->model->getProfile($this->faculty_id);
        $courses = $this->model->getAssignedCourses($this->faculty_id);
        include __DIR__ . '/../views/templates/header.php';
        include __DIR__ . '/../views/dashboard.php';
        include __DIR__ . '/../views/templates/footer.php';
    }

    public function handleUpdateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $desig = trim($_POST['designation']);
            $room = trim($_POST['office_room']);
            $hours = trim($_POST['office_hours']);
            
            $current = $this->model->getProfile($this->faculty_id);
            $pic_path = $current['profile_pic'];

            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                $file_name = time() . '_prof.' . $ext;
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], __DIR__ . '/../uploads/profiles/' . $file_name)) {
                    $pic_path = 'uploads/profiles/' . $file_name;
                }
            }
            $this->model->updateProfile($this->faculty_id, $name, $desig, $room, $hours, $pic_path);
        }
        header("Location: index.php?action=faculty_dashboard");
        exit();
    }

    public function showCourseManagement() {
        $course_id = intval($_GET['course_id']);
        $course = $this->model->getCourseDetails($course_id, $this->faculty_id);
        if (!$course) die("Access Restricted.");

        $students = $this->model->getCourseStudents($course_id);
        $announcements = $this->model->getAnnouncements($course_id);
        $materials = $this->model->getMaterials($course_id);
        $appeals = $this->model->getAppeals($course_id);
        $distribution = $this->model->getGradeDistribution($course_id);

        include __DIR__ . '/../views/templates/header.php';
        include __DIR__ . '/../views/course_manage.php';
        include __DIR__ . '/../views/templates/footer.php';
    }

    public function showGradeEntry() {
        $course_id = intval($_GET['course_id']);
        $students = $this->model->getCourseStudents($course_id);
        include __DIR__ . '/../views/templates/header.php';
        include __DIR__ . '/../views/grades.php';
        include __DIR__ . '/../views/templates/footer.php';
    }

    public function handlePublishGrades() {
        $this->model->publishGrades(intval($_POST['course_id']));
        header("Location: index.php?action=course_manage&course_id=" . intval($_POST['course_id']));
        exit();
    }

    public function handleAddAnnouncement() {
        $this->model->addAnnouncement(intval($_POST['course_id']), $this->faculty_id, trim($_POST['title']), trim($_POST['body']));
        header("Location: index.php?action=course_manage&course_id=" . intval($_POST['course_id']));
        exit();
    }

    public function handleDeleteAnnouncement() {
        $this->model->deleteAnnouncement(intval($_GET['id']), $this->faculty_id);
        header("Location: index.php?action=course_manage&course_id=" . intval($_GET['course_id']));
        exit();
    }

    public function handleAddMaterial() {
        $course_id = intval($_POST['course_id']);
        $title = trim($_POST['title']);
        $type = $_POST['material_type'];
        $path = '';

        if (isset($_FILES['material_file']) && $_FILES['material_file']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['material_file']['name'], PATHINFO_EXTENSION);
            $file_name = time() . '_mat.' . $ext;
            if (move_uploaded_file($_FILES['material_file']['tmp_name'], __DIR__ . '/../uploads/materials/' . $file_name)) {
                $path = 'uploads/materials/' . $file_name;
            }
        }
        if (!empty($path)) {
            $this->model->addMaterial($course_id, $this->faculty_id, $title, $path, $type);
        }
        header("Location: index.php?action=course_manage&course_id=" . $course_id);
        exit();
    }

    public function handleDeleteMaterial() {
        $this->model->deleteMaterial(intval($_GET['id']), $this->faculty_id);
        header("Location: index.php?action=course_manage&course_id=" . intval($_GET['course_id']));
        exit();
    }

    public function handleRespondAppeal() {
        $this->model->submitAppealComment(intval($_POST['appeal_id']), trim($_POST['faculty_comment']));
        header("Location: index.php?action=course_manage&course_id=" . intval($_POST['course_id']));
        exit();
    }
}
?>