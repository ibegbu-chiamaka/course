<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// ✅ Fetch student details
$stmt = $conn->prepare("SELECT full_name, department, level FROM students WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "Student not found.";
    exit();
}

$full_name = $student['full_name'];
$department = $student['department'];
$level = $student['level'];

// ✅ Fetch courses based on department & level
$sql = "SELECT DISTINCT c.* 
        FROM courses c 
        LEFT JOIN course_departments cd ON c.course_id = cd.course_id 
        WHERE (cd.department = ? OR cd.department = 'All' OR cd.department IS NULL) 
        AND c.level = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $department, $level);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Registration</title>
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
    <script>
        function addExtraCourse() {
            let courseCode = document.getElementById('extra_course_code').value.trim();
            let courseName = document.getElementById('extra_course_name').value.trim();
            let courseUnits = document.getElementById('extra_course_units').value.trim();

            if (courseCode === '' || courseName === '' || courseUnits === '') {
                alert("Please fill in all fields for the extra course.");
                return;
            }

            let table = document.getElementById('courseTable');
            let newRow = table.insertRow();
            
            let checkboxCell = newRow.insertCell(0);
            let courseCodeCell = newRow.insertCell(1);
            let courseNameCell = newRow.insertCell(2);
            let courseUnitsCell = newRow.insertCell(3);

            checkboxCell.innerHTML = `<input type="checkbox" name="extra_courses[]" value="${courseCode}|${courseName}|${courseUnits}" checked>`;
            courseCodeCell.textContent = courseCode;
            courseNameCell.textContent = courseName;
            courseUnitsCell.textContent = courseUnits;

            document.getElementById('extra_course_code').value = '';
            document.getElementById('extra_course_name').value = '';
            document.getElementById('extra_course_units').value = '';
        }
    </script>
</head>
<body>
<h2> <img src="../includes/aries-logo.png" alt="" style="width: 20px; height: 20px;"> ARIES POLYTECHNIC, IBADAN</h2>
<h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>(Student)</h2>

    <!-- Navigation Menu -->
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>

            <li>
                <a href="#">Courses ▾</a>
                <div class="dropdown-menu">
                <a href="print_slip.php">View Course Slip</a>
                <a href="dashboard.php">Course Registration</a>
                </div>
            </li>

            <li>
                <a href="#">Results ▾</a>
                <div class="dropdown-menu">
                <a href="view.php">Check Results</a>
                </div>
            </li>

            <li><a href="../login.php">Logout</a></li>
        </ul>
    </nav>

<div class="container">
<form action="save_courses.php" method="POST">
<table id="courseTable">
    <h2>Course Registration for <?php echo htmlspecialchars($department); ?> (Level: <?php echo htmlspecialchars($level); ?>)</h2>


            <tr>
                <th>Select</th>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Course Units</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><input type="checkbox" name="courses[]" value="<?php echo $row['course_id']; ?>" checked></td>
                    <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['credit_unit']); ?></td>
                </tr>
            <?php } ?>
        </table>

        <h4>Add Extra Course:</h4>
        <input type="text" id="extra_course_code" placeholder="Course Code">
        <input type="text" id="extra_course_name" placeholder="Course Name">
        <input type="number" id="extra_course_units" placeholder="Course Units">
        <button type="button" class="add-btn" onclick="addExtraCourse()">Add Course</button>
        <br><br>
        <button type="submit" class="submit-btn">Register Courses</button>
    </form>
</div>
</body>

<footer>
        <p>&copy; <?php echo date("Y"); ?> Aries Polytechnic Admin Dashboard</p>
</footer>
</html>


