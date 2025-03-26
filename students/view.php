<?php
session_start();
include '../includes/config.php';

// Check if matric number is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['matric_no'])) {
    $matric_no = trim($_POST['matric_no']);
    
    // Fetch student ID based on matric number
    $stmt = $conn->prepare("SELECT student_id FROM students WHERE matric_no = ?");
    $stmt->bind_param("s", $matric_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    
    if ($student) {
        $_SESSION['student_id'] = $student['student_id'];
        header("Location: view_results.php");
        exit();
    } else {
        $error = "Invalid Matric Number. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result Verification</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .popup-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
        }
        .popup {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .popup input {
            width: 80%;
            padding: 8px;
            margin-top: 10px;
        }
        .popup button {
            margin-top: 10px;
            padding: 8px 15px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .popup button:hover {
            background: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="popup-container">
        <div class="popup">
            <h2>Enter Matric Number</h2>
            <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>
            <form method="POST">
                <input type="text" name="matric_no" placeholder="Enter Matric Number" required>
                <br>
                <button type="submit">Verify</button>
            </form>
        </div>
    </div>
</body>
</html>
