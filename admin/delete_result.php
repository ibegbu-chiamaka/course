<?php
// Delete Student Results (admin/delete_result.php)
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../includes/config.php';


if (!isset($_GET['student_id'])) {
    echo "Invalid request.";
    exit();
}

$student_id = $_GET['student_id'];

// Delete student results
$delete_query = "DELETE FROM results WHERE student_id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $student_id);
if ($stmt->execute()) {
    echo "Results deleted successfully.";
} else {
    echo "Error deleting results.";
}

header("Location: student_results.php");
exit();
?>


