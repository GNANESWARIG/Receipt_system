<?php
session_start();
ob_start(); // Start output buffering to prevent header issues
include("db.php"); // Include database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if reg_no is set and not empty
    if (!isset($_POST['reg_no']) || empty(trim($_POST['reg_no']))) {
        header("Location: index.php?error=empty");
        exit();
    }

    // Get the registration number from the form
    $reg_no = trim($_POST['reg_no']);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM student WHERE reg_no = ?");
    $stmt->bind_param("s", $reg_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect to receipt page if student found
        header("Location: receipt.php?reg_no=" . urlencode($reg_no));
        exit();
    } else {
        // If no student found, display an error
        echo '<p style="color:red; text-align:center;">No student found with registration number: ' . htmlspecialchars($reg_no) . '</p>';
        echo '<p style="text-align:center;"><a href="index.php">Go Back</a></p>';
    }

    // Close statement
    $stmt->close();
} else {
    // Redirect back if accessed directly
    header("Location: index.php");
    exit();
}
?>
