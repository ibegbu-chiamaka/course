<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student details
$query = "SELECT department, level, semester FROM students WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

$department = $student['department'];
$level = $student['level'];
$semester = $student['semester'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['courses']) || isset($_POST['extra_courses'])) {
        $courses = $_POST['courses'] ?? [];
        $extra_courses = $_POST['extra_courses'] ?? [];

        // ✅ Prevent duplicate registrations
        $stmt = $conn->prepare("SELECT course_id FROM registrations WHERE student_id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $existing_courses = [];

        while ($row = $result->fetch_assoc()) {
            $existing_courses[] = $row['course_id'];
        }

        // ✅ Register regular courses
        $stmt = $conn->prepare("INSERT INTO registrations (student_id, course_id, semester, academic_year) VALUES (?, ?, ?, '2024/2025')");
        foreach ($courses as $course_id) {
            if (!in_array($course_id, $existing_courses)) {
                $stmt->bind_param("iis", $student_id, $course_id, $semester);
                $stmt->execute();
            }
        }

        // ✅ Register extra courses (carryover)
        foreach ($extra_courses as $extra) {
            list($code, $name, $units) = explode("|", $extra);
            $stmt = $conn->prepare("INSERT INTO registrations (student_id, course_id, semester, academic_year, type) VALUES (?, ?, ?, '2024/2025', 'carryover')");
            $stmt->bind_param("iis", $student_id, $code, $semester);
            $stmt->execute();
        }

        header("Location: print_slip.php?success=1");
        exit();
    } else {
        header("Location: print_slip.php?error=1");
        exit();
    }
}
?>
