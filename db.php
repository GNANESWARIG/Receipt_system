<?php
$host = "mysql.railway.internal";  // Replace with actual host
$dbname = "railway";               // Replace with actual database name
$username = "root";                // Replace with actual username
$password = "EgGrgtbFgbOiQSRGmgULeiWffbNwQUwj"; // Replace with actual password
$port = 3306;                      // Ensure port is correct

// Check if MySQLi extension is enabled
if (!extension_loaded('mysqli')) {
    die("❌ MySQLi extension is not enabled!");
}

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} else {
    echo "✅ Database connected successfully!";
}
?>
