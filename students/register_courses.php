<?php
include '../includes/config.php';
session_start();

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

// Fetch compulsory courses for the student's department, level, and semester
$query = "SELECT * FROM courses WHERE department = ? AND level = ? AND semester = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $department, $level, $semester);
$stmt->execute();
$compulsory_courses = $stmt->get_result();

// Fetch general courses
$sql = "SELECT * FROM courses WHERE (department = ? OR department IS NULL) AND level = ? AND semester = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $department, $level, $semester);
$stmt->execute();
$result = $stmt->get_result();

// Fetch additional courses (for carryover or extra selection)
$query = "SELECT * FROM courses WHERE department = ? AND level = ? AND semester != ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $department, $level, $semester);
$stmt->execute();
$extra_courses = $stmt->get_result();

// Handle Course Registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_courses = $_POST['courses'] ?? [];

    foreach ($selected_courses as $course_id) {
        $sql = "INSERT INTO registrations (student_id, course_id, semester, academic_year) 
                VALUES (?, ?, ?, '2024/2025')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $student_id, $course_id, $semester);
        $stmt->execute();
    }

    echo "<script>alert('Courses registered successfully!'); window.location.href='print_slip.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Registration</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 60%; margin: auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .submit-btn { margin-top: 20px; padding: 10px 20px; background: blue; color: white; border: none; cursor: pointer; }
    </style>
</head>
<nav>
        <ul>
            <li><a href="dashboard.php">Course Registration</a></li>
            <li><a href="../admin/view_result.php">View Results</a></li>
            <li><a href="../login.php">Logout</a></li>
        </ul>
    </nav>
<body>

<div class="container">
    <h2>Course Registration</h2>
    <form method="POST">

        <h3>Compulsory Courses</h3>
        <table>
            <tr>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Course Unit</th>
            </tr>
            <?php while ($course = $compulsory_courses->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($course['credit_unit']); ?></td>
                </tr>
                <input type="hidden" name="courses[]" value="<?php echo $course['course_id']; ?>">
            <?php } ?>
        </table>

        <h3>Additional Courses (Carryover/Extras)</h3>
        <table>
            <tr>
                <th>Select</th>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Course Unit</th>
            </tr>
            <?php while ($course = $extra_courses->fetch_assoc()) { ?>
                <tr>
                    <td><input type="checkbox" name="courses[]" value="<?php echo $course['course_id']; ?>"></td>
                    <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($course['credit_unit']); ?></td>
                </tr>
            <?php } ?>
        </table>

        <button type="submit" class="submit-btn">Register Courses</button>
    </form>
</div>

</body>
</html>
