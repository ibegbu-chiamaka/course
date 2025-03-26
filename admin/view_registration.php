<?php
include '../includes/config.php';
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

// Search filtering
$department_filter = isset($_GET['department']) ? $_GET['department'] : '';
$level_filter = isset($_GET['level']) ? $_GET['level'] : '';

$sql = "SELECT DISTINCT s.student_id, s.full_name, s.matric_no, s.department, s.level 
        FROM students s 
        JOIN registrations r ON s.student_id = r.student_id";

if (!empty($department_filter) || !empty($level_filter)) {
    $sql .= " WHERE";
    if (!empty($department_filter)) {
        $sql .= " s.department = '$department_filter'";
    }
    if (!empty($department_filter) && !empty($level_filter)) {
        $sql .= " AND";
    }
    if (!empty($level_filter)) {
        $sql .= " s.level = '$level_filter'";
    }
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../css/style.css"> -->
    <title>View Course Registrations</title>
    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }

        /* Header */
        h2 {
            background: #2c3e50;
            color: white;
            padding: 20px;
            margin: 0;
        }

        /* Navigation */
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

        /* Table Styling */
        .container {
            width: 90%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        /* Action Buttons */
        .view-btn {
            text-decoration: none;
            background: #3498db;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            display: inline-block;
        }
        .view-btn:hover {
            opacity: 0.8;
        }

        /* Search Bar */
        .search-bar {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        select, button {
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background: #1abc9c;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #16a085;
        }

        /* Footer */
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
<h2>View Course Registrations</h2>

<!-- Navigation -->
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
    <h2>Registered Students</h2>

    <!-- Search Bar -->
    <form method="GET" class="search-bar">
        <select name="department">
            <option value="">Filter by Department</option>
            <option value="Computer Science" <?php if ($department_filter == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
            <option value="Mass Communication" <?php if ($department_filter == 'Mass Communication') echo 'selected'; ?>>Mass Communication</option>
            <option value="Science Laboratory Technology" <?php if ($department_filter == 'Science Laboratory Technology') echo 'selected'; ?>>Science Laboratory Technology</option>
            <option value="Business Administration" <?php if ($department_filter == 'Business Administration') echo 'selected'; ?>>Business Administration</option>
            <option value="Accountancy" <?php if ($department_filter == 'Accountancy') echo 'selected'; ?>>Accountancy</option>
            <option value="Public Administration" <?php if ($department_filter == 'Public Administration') echo 'selected'; ?>>Public Administration</option>
        </select>

        <select name="level">
            <option value="">Filter by Level</option>
            <option value="ND1" <?php if ($level_filter == 'ND1') echo 'selected'; ?>>ND1</option>
            <option value="ND2" <?php if ($level_filter == 'ND2') echo 'selected'; ?>>ND2</option>
            <option value="HND1" <?php if ($level_filter == 'HND1') echo 'selected'; ?>>HND1</option>
            <option value="HND2" <?php if ($level_filter == 'HND2') echo 'selected'; ?>>HND2</option>
        </select>

        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>Name</th>
            <th>Matric No</th>
            <th>Department</th>
            <th>Level</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['matric_no']); ?></td>
                <td><?php echo htmlspecialchars($row['department']); ?></td>
                <td><?php echo htmlspecialchars($row['level']); ?></td>
                <td><a href="../students/print_slip.php?student_id=<?php echo $row['student_id']; ?>" class="view-btn">View</a></td>
            </tr>
        <?php } ?>
    </table>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Aries Polytechnic Admin Dashboard</p>
</footer>

</body>
</html>

<?php $conn->close(); ?>
