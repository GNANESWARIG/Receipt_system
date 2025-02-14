<?php
include("db.php"); // Ensure your database connection is correct

// ALTER TABLE query
$sql = "
    ALTER TABLE student 
    MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT, 
    MODIFY COLUMN reg_no VARCHAR(25) NOT NULL UNIQUE, 
    MODIFY COLUMN stud_name VARCHAR(150) NOT NULL, 
    MODIFY COLUMN sex CHAR(1) NOT NULL CHECK (sex IN ('M', 'F')), 
    MODIFY COLUMN father_name VARCHAR(120) NOT NULL, 
    MODIFY COLUMN year INT NOT NULL CHECK (year BETWEEN 1 AND 4), 
    MODIFY COLUMN degree_branch VARCHAR(60) NOT NULL, 
    MODIFY COLUMN rec_no1 BIGINT(20) NOT NULL, 
    MODIFY COLUMN quota VARCHAR(60), 
    MODIFY COLUMN mode VARCHAR(30), 
    MODIFY COLUMN tuti DECIMAL(12,2), 
    MODIFY COLUMN dev DECIMAL(12,2), 
    MODIFY COLUMN trai_pl DECIMAL(12,2), 
    MODIFY COLUMN cau_dep DECIMAL(12,2), 
    MODIFY COLUMN rec_no2 BIGINT(20) NOT NULL, 
    MODIFY COLUMN hostel DECIMAL(12,2), 
    MODIFY COLUMN online DECIMAL(12,2), 
    MODIFY COLUMN bus DECIMAL(12,2), 
    MODIFY COLUMN mess DECIMAL(12,2);
";

// Execute the query
if (mysqli_query($conn, $sql)) {
    echo "Table altered successfully!";
} else {
    echo "Error altering table: " . mysqli_error($conn);
}
?>
