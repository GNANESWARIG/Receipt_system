<?php
$host = getenv("mysql.railway.internal");      
$dbname = getenv("railway");
$username = getenv("root");
$password = getenv("EgGrgtbFgbOiQSRGmgULeiWffbNwQUwj");
$port = getenv("3306");      

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} else {
    echo "✅ Database connected successfully!";
}
?>
