<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Results</title>
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
<h2>Manage Results</h2>
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
                    <a href="student_results.php">Manage Results</a>
                    <a href="approve_results.php">Approve Results</a>
                </div>
            </li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
    
    <div class="container">
        <h2>Result Management</h2>
        <table>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT * FROM courses";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['course_code'] . "</td>";
                echo "<td>" . $row['course_name'] . "</td>";
                echo "<td><a href='add_result.php?course_id=" . $row['course_id'] . "'>Add</a> | ";
                echo "<a href='edit_result.php?course_id=" . $row['course_id'] . "'>Edit</a> | ";
                echo "<a href='delete_result.php?course_id=" . $row['course_id'] . "'>Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aries Polytechnic Admin Dashboard</p>
    </footer>
</body>
</html>
