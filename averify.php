<?php
require 'vendor/autoload.php'; // Ensure PHPSpreadsheet is autoloaded
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

include("db.php");

if (isset($_POST["uploadfile"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["myfile"]["name"]);

    // Check if the uploaded file is an Excel file
    $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $allowedTypes = ['xls', 'xlsx'];
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["myfile"]["tmp_name"], $target_file)) {
            // Load the Excel file
            $spreadsheet = IOFactory::load($target_file);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

            // Loop through each row of the worksheet
            for ($row = 2; $row <= $highestRow; ++$row) {
                $data = [];
                for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                    // Convert column index to letter
                    $columnLetter = Coordinate::stringFromColumnIndex($col);
                    // Construct cell address
                    $cellAddress = $columnLetter . $row;
                    // Get the value in the current cell
                    $cellValue = $worksheet->getCell($cellAddress)->getValue();
                    $data[] = mysqli_real_escape_string($conn, trim($cellValue));
                }

                // Assign variables with appropriate checks
                $reg_no = $data[0] ?? null;
                $stud_name = $data[1] ?? null;
                $sex = $data[2] ?? null;
                $father_name = $data[3] ?? null;
                $year = $data[4] ?? null;
                $degree_branch = $data[5] ?? null;
                $rec_no1 = $data[6] ?? null;
                $quota = $data[7] ?? null;
                $mode = $data[8] ?? null;
                $tuti = $data[9] ?? null;
                $dev = $data[10] ?? null;
                $trai_pl = $data[11] ?? null;
                $cau_dep = $data[12] ?? null;
                $rec_no2 = $data[13] ?? null;
                $hostel = $data[14] ?? null;
                $online = $data[15] ?? null;
                $bus = $data[16] ?? null;
                $mess = $data[17] ?? null;
                $sex = isset($data[2]) ? trim($data[2]) : null;
                // Convert to uppercase for uniformity
                $sex = strtoupper($sex);
                
                // Validate the value before inserting
                if (!in_array($sex, ['M', 'F'])) {
                    error_log("Invalid sex value on row $row: $sex");
                    continue; // Skip this row
                }
                

                // Prepare SQL statement with placeholders, excluding the 'id' column
                $stmt = $conn->prepare("INSERT INTO student (reg_no, stud_name, sex, father_name, year, degree_branch, rec_no1, quota, mode, tuti, dev, trai_pl, cau_dep, rec_no2, hostel, online, bus, mess) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                // Bind parameters to the statement
                $stmt->bind_param("ssssssssssssssssss", $reg_no, $stud_name, $sex, $father_name, $year, $degree_branch, $rec_no1, $quota, $mode, $tuti, $dev, $trai_pl, $cau_dep, $rec_no2, $hostel, $online, $bus, $mess);

                // Execute the statement
                if (!$stmt->execute()) {
                    // Handle execution errors
                    echo "Error inserting row $row: " . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            }

            // Delete the uploaded file after processing
            unlink($target_file);

            echo "<script>alert('Student details added successfully');window.location.replace('add_excel.php');</script>";
        } else {
            echo "<script>alert('File upload failed');window.location.replace('add_excel.php');</script>";
        }
    } else {
        echo "<script>alert('Please choose an Excel file (.xls or .xlsx) only');window.location.replace('add_excel.php');</script>";
    }
}
?>
