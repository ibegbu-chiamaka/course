<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve_results'])) {
    $approved_ids = $_POST['approve_results'];
    
    if (!empty($approved_ids)) {
        $ids = implode(",", array_map('intval', $approved_ids));
        $sql = "UPDATE results SET approved = 1 WHERE result_id IN ($ids)";
        
        if ($conn->query($sql) === TRUE) {
            echo "Selected results approved successfully. <a href='approve_results.php'>Go Back</a>";
        } else {
            echo "Error updating records: " . $conn->error;
        }
    } else {
        echo "No results selected for approval.";
    }
} else {
    echo "Invalid request.";
}
?>
