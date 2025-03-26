<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result_id = $_POST['result_id'];
    $score = intval($_POST['score']);

    // Determine grade and grade point
    $grade = "";
    $grade_point = 0.0;

    if ($score >= 70) {
        $grade = "A";
        $grade_point = 5.0;
    } elseif ($score >= 60) {
        $grade = "B";
        $grade_point = 4.0;
    } elseif ($score >= 50) {
        $grade = "C";
        $grade_point = 3.0;
    } elseif ($score >= 45) {
        $grade = "D";
        $grade_point = 2.0;
    } elseif ($score >= 40) {
        $grade = "E";
        $grade_point = 1.0;
    } else {
        $grade = "F";
        $grade_point = 0.0;
    }

    // Update the result in the database
    $sql = "UPDATE results SET score = ?, grade = ?, grade_point = ? WHERE result_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dsdi", $score, $grade, $grade_point, $result_id);

    if ($stmt->execute()) {
        echo "Result updated successfully. <a href='manage_result.php'>Go Back</a>";
    } else {
        echo "Error updating result.";
    }
} else {
    echo "Invalid request.";
}
?>


