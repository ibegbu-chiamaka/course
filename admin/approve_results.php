<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../includes/config.php';

// Fetch unapproved results
$sql = "SELECT r.result_id, s.matric_no, s.full_name, c.course_code, r.score, r.grade, r.grade_point, r.approved 
        FROM results r 
        JOIN students s ON r.student_id = s.student_id 
        JOIN courses c ON r.course_id = c.course_id 
        WHERE r.approved = 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Results</title>
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
        .welcome-admin {
            background: #2c3e50;
            color: white;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
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
        button {
            background: #1abc9c;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
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
<h2>Aprove Results</h2>
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
    
    <div class="welcome-admin">
        Welcome, Admin!
    </div>
    
    <div class="container">
        <h2>Approve Results</h2>
        <form action="process_approval.php" method="POST">
            <table>
                <tr>
                    <th>Select</th>
                    <th>Matric No</th>
                    <th>Full Name</th>
                    <th>Course</th>
                    <th>Score</th>
                    <th>Grade</th>
                    <th>Grade Point</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><input type="checkbox" name="approve_results[]" value="<?php echo $row['result_id']; ?>"></td>
                        <td><?php echo $row['matric_no']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['course_code']; ?></td>
                        <td><?php echo $row['score']; ?></td>
                        <td><?php echo $row['grade']; ?></td>
                        <td><?php echo $row['grade_point']; ?></td>
                    </tr>
                <?php } ?>
            </table>
            <br>
            <button type="submit">Approve Selected Results</button>
        </form>
    </div>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aries Polytechnic Admin Dashboard</p>
    </footer>
</body>
</html>
