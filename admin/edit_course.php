<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}


// ✅ Ensure a course ID is provided
if (!isset($_GET['course_id']) || empty($_GET['course_id'])) {
    echo "SQL Error: " . $conn->error;
    exit();
    
}

$course_id = $_GET['course_id'];

// ✅ Fetch course details from the database
$stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    echo "Course not found.";
    exit();
}

// ✅ Fetch assigned departments for the course
$dept_stmt = $conn->prepare("SELECT department FROM course_departments WHERE course_id = ?");
$dept_stmt->bind_param("i", $course_id);
$dept_stmt->execute();
$dept_result = $dept_stmt->get_result();

$assigned_departments = [];
while ($row = $dept_result->fetch_assoc()) {
    $assigned_departments[] = $row['department'];
}

// ✅ Handle course update submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id']; // Get course ID from hidden field
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $credit_unit = $_POST['credit_unit'];
    $departments = $_POST['departments']; // Array of selected departments
    $level = $_POST['level'];
    $semester = $_POST['semester'];

    // ✅ Update course in the `courses` table
    $sql = "UPDATE courses SET course_code = ?, course_name = ?, credit_unit = ?, level = ?, semester = ? WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissi", $course_code, $course_name, $credit_unit, $level, $semester, $course_id);

    if ($stmt->execute()) {
        // ✅ Clear existing department assignments
        $delete_stmt = $conn->prepare("DELETE FROM course_departments WHERE course_id = ?");
        $delete_stmt->bind_param("i", $course_id);
        $delete_stmt->execute();

        // ✅ Re-insert updated departments
        if (in_array("All", $departments)) {
            $departments = ["Computer Science", "Mass Communication", "Science Laboratory Technology", "Business Administration", "Accountancy", "Public Administration"];
        }

        $insert_stmt = $conn->prepare("INSERT INTO course_departments (course_id, department) VALUES (?, ?)");
        foreach ($departments as $department) {
            $insert_stmt->bind_param("is", $course_id, $department);
            $insert_stmt->execute();
        }

        // ✅ Redirect back to manage_courses.php after successful update
        header("Location: manage_courses.php?success=updated");
        exit();
    } else {
        echo "Error updating course: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Course</title>

    <script>
        function handleDepartmentSelection() {
            let departmentSelect = document.getElementById("departments");
            let selectedOptions = Array.from(departmentSelect.selectedOptions).map(option => option.value);
            
            if (selectedOptions.includes("All")) {
                for (let option of departmentSelect.options) {
                    option.selected = option.value === "All";
                }
            }
        }
    </script>
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
    <h2>Edit Course</h2>

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

<form action="" method="POST">
    <label>Course Code:</label>
    <input type="text" name="course_code" value="<?php echo htmlspecialchars($course['course_code']); ?>" required><br>

    <label>Course Name:</label>
    <input type="text" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required><br>

    <label>Course Units:</label>
    <input type="number" name="credit_unit" value="<?php echo htmlspecialchars($course['credit_unit']); ?>" required><br>

    <label>Department:</label>
    <select name="departments[]" id="departments" multiple required onchange="handleDepartmentSelection()">
        <option value="Computer Science" <?php echo (in_array("Computer Science", $assigned_departments) ? "selected" : ""); ?>>Computer Science</option>
        <option value="Mass Communication" <?php echo (in_array("Mass Communication", $assigned_departments) ? "selected" : ""); ?>>Mass Communication</option>
        <option value="Science Laboratory Technology" <?php echo (in_array("Science Laboratory Technology", $assigned_departments) ? "selected" : ""); ?>>Science Laboratory Technology</option>
        <option value="Business Administration" <?php echo (in_array("Business Administration", $assigned_departments) ? "selected" : ""); ?>>Business Administration</option>
        <option value="Accountancy" <?php echo (in_array("Accountancy", $assigned_departments) ? "selected" : ""); ?>>Accountancy</option>
        <option value="Public Administration" <?php echo (in_array("Public Administration", $assigned_departments) ? "selected" : ""); ?>>Public Administration</option>
    </select>
    <small>Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small><br>

    <label>Level:</label>
    <select name="level" required>
        <option value="ND1" <?php echo ($course['level'] == 'ND1') ? 'selected' : ''; ?>>ND1</option>
        <option value="ND2" <?php echo ($course['level'] == 'ND2') ? 'selected' : ''; ?>>ND2</option>
        <option value="HND1" <?php echo ($course['level'] == 'HND1') ? 'selected' : ''; ?>>HND1</option>
        <option value="HND2" <?php echo ($course['level'] == 'HND2') ? 'selected' : ''; ?>>HND2</option>
    </select><br>

    <label>Semester:</label>
    <select name="semester" required>
        <option value="First" <?php echo ($course['semester'] == 'First') ? 'selected' : ''; ?>>First Semester</option>
        <option value="Second" <?php echo ($course['semester'] == 'Second') ? 'selected' : ''; ?>>Second Semester</option>
    </select><br>

    <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($_GET['course_id']); ?>">


    <button type="submit">Update Course</button>
</form>
</body>
<footer>
        <p>&copy; <?php echo date("Y"); ?> Aries Polytechnic Admin Dashboard</p>
    </footer>
</html>


