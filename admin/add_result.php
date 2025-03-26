<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../includes/config.php';

if (!isset($_GET['course_id'])) {
    echo "Invalid request.";
    exit();
}

$course_id = $_GET['course_id'];
$course_query = "SELECT course_code, course_name FROM courses WHERE course_id = ?";
$stmt = $conn->prepare($course_query);
if (!$stmt) {
    die("Query Error: " . $conn->error);
}
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course_result = $stmt->get_result();
$course = $course_result->fetch_assoc();

if (!$course) {
    echo "Course not found.";
    exit();
}

// Fetch students who registered for this course & check if they already have scores
$student_query = "SELECT s.student_id, s.matric_no, s.full_name, res.score 
                  FROM registrations r 
                  JOIN students s ON r.student_id = s.student_id 
                  LEFT JOIN results res ON r.student_id = res.student_id 
                  AND r.course_id = res.course_id 
                  WHERE r.course_id = ? ";

$stmt = $conn->prepare($student_query);
if (!$stmt) {
    die("Query Error: " . $conn->error);
}
$stmt->bind_param("i", $course_id);
$stmt->execute();
$students = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }

        h2 {
            background: #2c3e50;
            color: white;
            padding: 20px;
            margin: 0;
        }

        nav {
            background: #34495e;
            padding: 10px 0;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        nav ul li {
            position: relative;
            margin: 0 15px;
        }
        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 15px;
            display: block;
            transition: 0.3s;
        }
        nav ul li a:hover {
            background: #1abc9c;
            border-radius: 5px;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #2c3e50;
            min-width: 200px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }
        .dropdown-menu a {
            padding: 10px 15px;
            color: white;
            text-align: left;
        }
        .dropdown-menu a:hover {
            background: #1abc9c;
        }
        nav ul li:hover .dropdown-menu {
            display: block;
        }

        .container {
            width: 80%;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #34495e;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        tr:hover {
            background: #ddd;
        }
        .submit-btn {
            background: #1abc9c;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #16a085;
        }

        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<h2> <img src="../includes/aries-logo.png" alt="" style="width: 20px; height: 20px;"> ARIES POLYTECHNIC, IBADAN</h2>
<h2>Welcome, <?php echo $_SESSION['admin_name']; ?> (Admin)</h2>
<h2>Add Results</h2>
    <nav>
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li>
                <a href="#">Students ▾</a>
                <div class="dropdown-menu">
                    <a href="manage_students.php">Manage Students</a>
                    <a href="view_registration.php">View Course Registrations</a>
                </div>
            </li>
            <li>
                <a href="#">Courses ▾</a>
                <div class="dropdown-menu">
                    <a href="manage_courses.php">Manage Courses</a>
                </div>
            </li>
            <li>
                <a href="#">Results ▾</a>
                <div class="dropdown-menu">
                <a href="student_results.php">View Results</a>
                    <a href="manage_result.php">Manage Results</a>
                    <a href="approve_results.php">Approve Results</a>
                </div>
            </li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
    
    <div class="container">
        <h2>Add Results for <?php echo htmlspecialchars($course['course_code'] . " - " . $course['course_name']); ?></h2>

        <?php if ($students->num_rows > 0) { ?>
            <form action="save_results.php" method="POST">
                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                <table>
                    <tr>
                        <th>Matric Number</th>
                        <th>Full Name</th>
                        <th>Score</th>
                    </tr>
                    <?php while ($row = $students->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['matric_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td>
                                <input type="number" name="scores[<?php echo $row['matric_no']; ?>]" min="0" max="100" value="<?php echo isset($row['score']) ? $row['score'] : ''; ?>" required>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <button type="submit" class="submit-btn">Save Results</button>
            </form>
        <?php } else { ?>
            <p>No students have registered for this course yet.</p>
        <?php } ?>
    </div>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aries Polytechnic Admin Dashboard</p>
    </footer>
</body>
</html>
