<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $academic_year = "2024/2025"; // Update dynamically if needed
    $semester = "First"; // Update dynamically if needed

    if (isset($_POST['scores']) && is_array($_POST['scores'])) {
        foreach ($_POST['scores'] as $matric_no => $score) {
            $score = intval($score);

            // Fetch student ID using matric number
            $query = "SELECT student_id FROM students WHERE matric_no = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $matric_no);
            $stmt->execute();
            $result = $stmt->get_result();
            $student = $result->fetch_assoc();

            if ($student) {
                $student_id = $student['student_id'];

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

                // Insert or update result in the database
                $sql = "INSERT INTO results (student_id, course_id, score, grade, grade_point, academic_year, semester, approved)
                        VALUES (?, ?, ?, ?, ?, ?, ?, 0)
                        ON DUPLICATE KEY UPDATE score = ?, grade = ?, grade_point = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiisdssdsd", $student_id, $course_id, $score, $grade, $grade_point, $academic_year, $semester, $score, $grade, $grade_point);
                $stmt->execute();
            }
        }
    }

    echo "Results saved successfully. <a href='manage_result.php'>Go Back</a>";
} else {
    echo "Invalid request.";
}


