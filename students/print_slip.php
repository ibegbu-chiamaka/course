<?php
include '../includes/config.php';
session_start();

// Ensure the student is logged in
if (!isset($_SESSION['student_id'])) {
    die("Invalid request. Please <a href='../login.php'>login</a>.");
}

$student_id = $_SESSION['student_id'];

// Fetch student details
$student_sql = "SELECT full_name, matric_no, department, level, semester FROM students WHERE student_id = ?";
$stmt = $conn->prepare($student_sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    die("Error: Student details not found.");
}

$stmt->bind_result($full_name, $matric_no, $department, $level, $semester);
$stmt->fetch();
$stmt->close();

// Fetch registered courses
$course_sql = "SELECT c.course_code, c.course_name, c.credit_unit 
               FROM registrations r 
               JOIN courses c ON r.course_id = c.course_id 
               WHERE r.student_id = ?";
$stmt = $conn->prepare($course_sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("No registered courses found. Please <a href='register_courses.php'>register courses</a>.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration Slip</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn { display: block; width: 100px; margin: 20px auto; text-align: center; padding: 10px; background: blue; color: white; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <center><img src="aries.png" alt=""> </center>
    <h2> ARIES POLYTECHNIC, IBADAN</h2>
    <h2>Course Registration Slip</h2>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($full_name); ?></p>
    <p><strong>Matric No:</strong> <?php echo htmlspecialchars($matric_no); ?></p>
    <p><strong>Department:</strong> <?php echo htmlspecialchars($department); ?></p>
    <p><strong>Level:</strong> <?php echo htmlspecialchars($level); ?></p>
    <p><strong>Semester:</strong> <?php echo htmlspecialchars($semester); ?></p>

    <h3>Registered Courses</h3>
    <table>
        <tr>
            <th>Course Code</th>
            <th>Course Title</th>
            <th>Course Unit</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                <td><?php echo htmlspecialchars($row['credit_unit']); ?></td>
            </tr>
        <?php } ?>
    </table>

    <a href="javascript:window.print()" class="btn">Print</a>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
