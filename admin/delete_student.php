<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../includes/config.php';

// Check if student_id is set and valid
if (!isset($_GET['student_id']) || !is_numeric($_GET['student_id'])) {
    die("Invalid request. Student ID missing or incorrect.");
}

$student_id = intval($_GET['student_id']); // Ensure it's an integer

// First, delete student's course registrations to avoid foreign key issues
$delete_registrations = "DELETE FROM registrations WHERE student_id = ?";
$stmt = $conn->prepare($delete_registrations);
$stmt->bind_param("i", $student_id);
$stmt->execute();

// Delete student's results if applicable
$delete_results = "DELETE FROM results WHERE student_id = ?";
$stmt = $conn->prepare($delete_results);
$stmt->bind_param("i", $student_id);
$stmt->execute();

// Delete student record
$delete_student = "DELETE FROM students WHERE student_id = ?";
$stmt = $conn->prepare($delete_student);
$stmt->bind_param("i", $student_id);

if ($stmt->execute()) {
    echo "<script>alert('Student deleted successfully!'); window.location.href='manage_students.php';</script>";
    exit();
} else {
    echo "Error deleting student.";
}
?>
