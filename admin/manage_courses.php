<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Course Deletion
if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];
    $conn->query("DELETE FROM courses WHERE course_id='$course_id'");
    header("Location: manage_courses.php");
    exit();
}

// Fetch all courses
$result = $conn->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses</title>
</head>
<body>
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
    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }
    th {
        background: #34495e;
        color: white;
        font-size: 18px;
    }
    tr:nth-child(even) {
        background: #f2f2f2;
    }
    tr:hover {
        background: #ddd;
        transition: 0.3s;
    }
    td a {
        text-decoration: none;
        padding: 8px 12px;
        margin: 5px;
        border-radius: 5px;
        font-size: 14px;
        display: inline-block;
    }
    td a:first-child {
        background: #3498db;
        color: white;
    }
    td a:last-child {
        background: #e74c3c;
        color: white;
    }
    td a:hover {
        opacity: 0.8;
    }
    footer {
        background: #2c3e50;
        color: white;
        text-align: center;
        padding: 15px;
        margin-top: 20px;
    }
</style>
<h2> <img src="../includes/aries-logo.png" alt="" style="width: 20px; height: 20px;"> ARIES POLYTECHNIC, IBADAN</h2>
<h2>Welcome, <?php echo $_SESSION['admin_name']; ?> (Admin)</h2>
<h2>Course Management</h2>

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

<a href="add_course.php" style="display: inline-block; margin: 20px auto; padding: 10px 15px; background: #2ecc71; color: white; text-decoration: none; border-radius: 5px;">Add New Course</a>

<table border="1">
    <tr>
        <th>Course Code</th>
        <th>Course Name</th>
        <th>Course Units</th>
        <th>Department</th>
        <th>Level</th>
        <th>Semester</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['course_code']; ?></td>
            <td><?php echo $row['course_name']; ?></td>
            <td><?php echo $row['credit_unit']; ?></td>
            <td><?php echo $row['department']; ?></td>
            <td><?php echo $row['level']; ?></td>
            <td><?php echo $row['semester']; ?></td>
            <td>
                <a href="edit_course.php?course_id=<?php echo $row['course_id']; ?>">Edit</a>
                <a href="manage_courses.php?delete=<?php echo $row['course_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Aries Polytechnic Admin Dashboard</p>
</footer>
</body>
</html>
