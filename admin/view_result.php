<?php
// View Student Result Page (admin/view_result.php)
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

// Fetch student details
$student_query = "SELECT * FROM students WHERE student_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();

if (!$student) {
    echo "Student not found.";
    exit();
}

// Fetch student results
$result_query = "SELECT r.score, c.course_code, c.course_name, c.credit_unit, r.semester
                 FROM results r 
                 JOIN courses c ON r.course_id = c.course_id 
                 WHERE r.student_id = ?";
$stmt = $conn->prepare($result_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$results = $stmt->get_result();

// Initialize totals for CGPA calculation
$total_credit_units = 0;
$total_grade_points = 0;
?>

<link rel="stylesheet" href="../css/style.css">

<div class="result-container" id="printableArea">
<div class="print-container">
    <center><img src="aries.png" alt=""></center>
    <h2 class="title"> ARIES POLYTECHNIC, IBADAN</h2>
    <h2 class="title">STUDENT RESULT SLIP</h2>

    <div class="student-info">
        <p><strong>Name:</strong> <?php echo $student['full_name']; ?></p>
        <p><strong>Matric Number:</strong> <?php echo $student['matric_no']; ?></p>
        <p><strong>Department:</strong> <?php echo $student['department']; ?></p>
        <p><strong>Level:</strong> <?php echo $student['level']; ?></p>
        <p><strong>Semester:</strong> <?php echo $results->num_rows > 0 ? $results->fetch_assoc()['semester'] : "N/A"; ?></p>
    </div>

    <table class="styled-table">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Course Units</th>
                <th>Score</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Reset result set pointer
            $results->data_seek(0);
            
            while ($row = $results->fetch_assoc()) { 
                // Generate grade
                $score = $row['score'];
                if ($score >= 70) { $grade = "A"; }
                elseif ($score >= 60) { $grade = "B"; }
                elseif ($score >= 50) { $grade = "C"; }
                elseif ($score >= 45) { $grade = "D"; }
                elseif ($score >= 40) { $grade = "E"; }
                else { $grade = "F"; }

                // Accumulate totals for CGPA calculation
                $credit_unit = $row['credit_unit'];
                $total_credit_units += $credit_unit;
                $grade_point = ($score >= 70) ? 5 :
                               ($score >= 60) ? 4 :
                               ($score >= 50) ? 3 :
                               ($score >= 45) ? 2 :
                               ($score >= 40) ? 1 : 0;
                $total_grade_points += ($grade_point * $credit_unit);
            ?>
                <tr>
                    <td><?php echo $row['course_code']; ?></td>
                    <td><?php echo $row['course_name']; ?></td>
                    <td><?php echo $credit_unit; ?></td>
                    <td><?php echo $score; ?></td>
                    <td><?php echo $grade; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php 
    // Calculate CGPA and overall percentage
    $cgpa = ($total_credit_units > 0) ? round($total_grade_points / $total_credit_units, 2) : 0;
    $percentage = ($cgpa / 5) * 100; // Assuming 5.0 CGPA scale

    // Classification
    if ($cgpa >= 3.50) {
        $classification = "Distinction";
    } elseif ($cgpa >= 3.00) {
        $classification = "Upper Credit";
    } elseif ($cgpa >= 2.50) {
        $classification = "Lower Credit";
    } elseif ($cgpa >= 2.00) {
        $classification = "Pass";
    } else {
        $classification = "Advice to Withdraw";
    }
    ?>

    <div class="summary">
        <p><strong>Total Course Units:</strong> <?php echo $total_credit_units; ?></p>
        <p><strong>Total Grade Points:</strong> <?php echo $total_grade_points; ?></p>
        <p><strong>Cumulative Grade Point Average (CGPA):</strong> <?php echo $cgpa; ?></p>
        <p><strong>Overall Percentage:</strong> <?php echo round($percentage, 2); ?>%</p>
        <p><strong>Classification:</strong> <?php echo $classification; ?></p>
    </div>
    <div class="no-print">
    <button onclick="window.print()" class="print-btn" >Print Result</button>
    </div>
</div>

<style>
    .result-container {
        width: 80%;
        margin: auto;
        text-align: center;
        border: 2px solid #000;
        padding: 20px;
        background: #fff;
    }

    .title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .student-info {
        text-align: left;
        margin-bottom: 20px;
    }

    .styled-table {
        width: 100%;
        border-collapse: collapse;
    }

    .styled-table th, .styled-table td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
    }

    .styled-table th {
        background: #ddd;
    }

    .summary {
        text-align: left;
        margin-top: 20px;
        font-size: 16px;
    }

    .print-btn {
        margin-top: 20px;
        padding: 10px 15px;
        font-size: 16px;
        cursor: pointer;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
    }

    .print-btn:hover {
        background: #0056b3;
    }
</style>
