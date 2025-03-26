<?php
session_start();
include '../includes/config.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <!-- <link rel="stylesheet" href="../css/style.css"> -->
</head>
<body>
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

        /* Swiper Container */
        .swiper {
            width: 80%;
            max-width: 900px;
            height: 400px;
            margin: 20px auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            background: white;
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Swiper Navigation */
        .swiper-button-next, .swiper-button-prev {
            color: #fff;
        }
        .swiper-pagination {
            bottom: 10px;
        }

        /* Footer Styling */
        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 20px;
        }
        /* Table Styling */
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

/* Action Buttons */
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

    </style>
</head>
<body>
<h2> <img src="../includes/aries-logo.png" alt="" style="width: 20px; height: 20px;"> ARIES POLYTECHNIC, IBADAN</h2>
    <h2>Welcome, <?php echo $_SESSION['admin_name']; ?> (Admin)</h2>
    <h2>Student Management</h2>
    
    <!-- Navigation Menu -->
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
    
    <table border="1">
        <tr><th>Name</th><th>Matric No</th><th>Department</th><th>Level</th><th>Actions</th></tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['matric_no']; ?></td>
                <td><?php echo $row['department']; ?></td>
                <td><?php echo $row['level']; ?></td>
                <td>
                    <a href="edit_student.php?student_id=<?php echo $row['student_id']; ?>">Edit</a>
                    <a href="delete_student.php?student_id=<?php echo $row['student_id']; ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
<footer>
        <p>&copy; <?php echo date("Y"); ?> Aries Polytechnic Admin Dashboard</p>
    </footer>
</html>
