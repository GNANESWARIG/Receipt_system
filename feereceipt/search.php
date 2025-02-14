<?php
session_start();
include("db.php"); // Include database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the registration number from the form
    $reg_no = mysqli_real_escape_string($conn, $_POST['reg_no']);

    // Query the database
    $sql = "SELECT * FROM student WHERE reg_no = '$reg_no'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // If record exists, redirect to the receipt page
        header("Location: receipt.php?reg_no=" . urlencode($reg_no));
        exit();
    } else {
        // Display an error message if no record is found
        echo '<p style="color:red; text-align:center;">No student found with registration number: ' . htmlspecialchars($reg_no) . '</p>';
        echo '<p style="text-align:center;"><a href="index.php">Go Back</a></p>';
    }
} else {
    // Redirect back to the index page if accessed directly
    header("Location: index.php");
    exit();
}
?>
