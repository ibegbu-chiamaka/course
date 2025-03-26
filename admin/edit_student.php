<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../includes/config.php';

// Check if student_id is set and numeric
if (!isset($_GET['student_id']) || !is_numeric($_GET['student_id'])) {
    die("Invalid request. Student ID missing or incorrect.");
}

$student_id = intval($_GET['student_id']); // Ensure it's an integer

// Fetch student data
$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $matric_no = $_POST['matric_no'];
    $department = $_POST['department'];
    $level = $_POST['level'];
    $semester = $_POST['semester'];

    $update_sql = "UPDATE students SET full_name=?, matric_no=?, department=?, level=?, semester=? WHERE student_id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssi", $full_name, $matric_no, $department, $level, $semester, $student_id);

    if ($stmt->execute()) {
        echo "<script>alert('Student updated successfully!'); window.location.href='manage_students.php';</script>";
        exit();
    } else {
        echo "Error updating student.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }

        /* Header Styling */
        h2 {
            background: #2c3e50;
            color: white;
            padding: 20px;
            margin: 0;
        }

        /* Navigation Menu Styling */
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

        /* Dropdown Menu */
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

        /* Form Styling */
        form {
            background: white;
            padding: 20px;
            margin: 20px auto;
            width: 50%;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            text-align: left;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        input:focus, select:focus {
            border-color: #1abc9c;
            outline: none;
            box-shadow: 0px 0px 5px rgba(26, 188, 156, 0.5);
        }
        button {
            background: #1abc9c;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            display: block;
            width: 100%;
            transition: 0.3s;
        }
        button:hover {
            background: #16a085;
        }

        /* Footer Styling */
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
<h2>Student Management</h2>

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
        <h2>Edit Student</h2>
        <form method="POST">
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
            <input type="text" name="matric_no" value="<?php echo htmlspecialchars($student['matric_no']); ?>" required>
            <select name="department" required>
                <option value="Computer Science" <?php if ($student['department'] == "Computer Science") echo "selected"; ?>>Computer Science</option>
                <option value="Mass Communication" <?php if ($student['department'] == "Mass Communication") echo "selected"; ?>>Mass Communication</option>
                <option value="Science Laboratory Technology" <?php if ($student['department'] == "Science Laboratory Technology") echo "selected"; ?>>Science Laboratory Technology</option>
                <option value="Business Administration" <?php if ($student['department'] == "Business Administration") echo "selected"; ?>>Business Administration</option>
                <option value="Accountancy" <?php if ($student['department'] == "Accountancy") echo "selected"; ?>>Accountancy</option>
                <option value="Public Administration" <?php if ($student['department'] == "Public Administration") echo "selected"; ?>>Public Administration</option>
            </select>
            <select name="level" required>
                <option value="ND1" <?php if ($student['level'] == "ND1") echo "selected"; ?>>ND1</option>
                <option value="ND2" <?php if ($student['level'] == "ND2") echo "selected"; ?>>ND2</option>
                <option value="HND1" <?php if ($student['level'] == "HND1") echo "selected"; ?>>HND1</option>
                <option value="HND2" <?php if ($student['level'] == "HND2") echo "selected"; ?>>HND2</option>
            </select>
            <select name="semester" required>
                <option value="First" <?php if ($student['semester'] == "First") echo "selected"; ?>>First Semester</option>
                <option value="Second" <?php if ($student['semester'] == "Second") echo "selected"; ?>>Second Semester</option>
            </select>
            <button type="submit">Update</button>
        </form>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aries Polytechnic. All rights reserved.</p>
    </footer>
</body>
</html>
