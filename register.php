<?php
session_start();
include 'includes/config.php';
include '';
// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $matric_no = $_POST['matric_no'];
    $department = $_POST['department'];
    $level = $_POST['level'];
    $semester = $_POST['semester'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert student into database
    $sql = "INSERT INTO students (full_name, matric_no, department, level, semester, password) 
            VALUES ('$full_name', '$matric_no', '$department', '$level', '$semester', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<link rel="stylesheet" href="css/style.css">
<!-- Student Registration Form -->
<form method="POST">
    <h2>REGISTER AS A STUDENT</h2>
    <input type="text" name="full_name" placeholder="Full Name" required><br>
    <input type="text" name="matric_no" placeholder="Matric Number" required><br>
    
    <!-- Department Dropdown -->
    <select name="department" required>
        <option value="">Select Department</option>
        <option value="Computer Science">Computer Science</option>
        <option value="Mass Communication">Mass Communication</option>
        <option value="Science Laboratory Technology">Science Laboratory Technology</option>
        <option value="Business Administration">Business Administration</option>
        <option value="Accountacy">Accountacy</option>
        <option value="Public Administration">Public Administration</option>
    </select><br>

    <!-- Level Dropdown -->
    <select name="level" required>
        <option value="">Select Level</option>
        <option value="ND1">ND1</option>
        <option value="ND2">ND2</option>
        <option value="HND1">HND1</option>
        <option value="HND2">HND2</option>
    </select><br>

    <!-- Semester Dropdown -->
    <select name="semester" required>
        <option value="">Select Semester</option>
        <option value="First">First Semester</option>
        <option value="Second">Second Semester</option>
    </select><br>

    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
</form>

<?php
include '';
?>