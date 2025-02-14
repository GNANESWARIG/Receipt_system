<?php
session_start();  // Start session to store errors

require __DIR__ . '/vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\IOFactory; 
use PhpOffice\PhpSpreadsheet\Cell\Coordinate; 

include("db.php"); 

$_SESSION['upload_errors'] = []; // Reset errors

if (isset($_POST["uploadfile"])) {
    $fileTmpPath = $_FILES["myfile"]["tmp_name"];
    $fileType = pathinfo($_FILES["myfile"]["name"], PATHINFO_EXTENSION);
    $allowedTypes = ['xls', 'xlsx'];

    if (in_array($fileType, $allowedTypes)) {
        try {
            $spreadsheet = IOFactory::load($fileTmpPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

            for ($row = 2; $row <= $highestRow; ++$row) {
                $data = [];
                for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                    $columnLetter = Coordinate::stringFromColumnIndex($col);
                    $cellValue = $worksheet->getCell($columnLetter . $row)->getValue();
                    $data[] = mysqli_real_escape_string($conn, trim($cellValue ?? '')); // Prevent null errors
                }

                // Assign variables
                $reg_no = $data[0] ?? '';
                $stud_name = $data[1] ?? '';
                $sex = strtoupper($data[2] ?? '');
                $father_name = $data[3] ?? '';
                $year = $data[4] ?? '';
                $degree_branch = $data[5] ?? '';
                $rec_no1 = $data[6] ?? '';
                $quota = $data[7] ?? '';
                $mode = $data[8] ?? '';
                $tuti = $data[9] ?? '';
                $dev = $data[10] ?? '';
                $trai_pl = $data[11] ?? '';
                $cau_dep = $data[12] ?? '';
                $rec_no2 = $data[13] ?? '';
                $hostel = $data[14] ?? '';
                $online = $data[15] ?? '';
                $bus = $data[16] ?? '';
                $mess = $data[17] ?? '';

                // Skip invalid rows
                if (!in_array($sex, ['M', 'F'], true)) {
                    $_SESSION['upload_errors'][] = "Row $row: Invalid gender value '$sex'. Skipped.";
                    continue;
                }

                // Update database
                $stmt = $conn->prepare("
                    INSERT INTO student 
                    (reg_no, stud_name, sex, father_name, year, degree_branch, rec_no1, quota, mode, tuti, dev, trai_pl, cau_dep, rec_no2, hostel, online, bus, mess) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE 
                    stud_name = VALUES(stud_name), 
                    sex = VALUES(sex), 
                    father_name = VALUES(father_name), 
                    year = VALUES(year), 
                    degree_branch = VALUES(degree_branch),
                    rec_no1 = VALUES(rec_no1), 
                    quota = VALUES(quota), 
                    mode = VALUES(mode), 
                    tuti = VALUES(tuti), 
                    dev = VALUES(dev), 
                    trai_pl = VALUES(trai_pl), 
                    cau_dep = VALUES(cau_dep), 
                    rec_no2 = VALUES(rec_no2), 
                    hostel = VALUES(hostel), 
                    online = VALUES(online), 
                    bus = VALUES(bus), 
                    mess = VALUES(mess)
                ");

                if (!$stmt) {
                    $_SESSION['upload_errors'][] = "Row $row: Database error: " . $conn->error;
                    continue;
                }

                $stmt->bind_param("ssssssssssssssssss", $reg_no, $stud_name, $sex, $father_name, $year, $degree_branch, $rec_no1, $quota, $mode, $tuti, $dev, $trai_pl, $cau_dep, $rec_no2, $hostel, $online, $bus, $mess);

                if (!$stmt->execute()) {
                    $_SESSION['upload_errors'][] = "Row $row: Insert/update failed: " . $stmt->error;
                }

                $stmt->close();
            }

            $_SESSION['upload_success'] = "Student details added/updated successfully.";
        } catch (Exception $e) {
            $_SESSION['upload_errors'][] = "File processing error: " . $e->getMessage();
        }
    } else {
        $_SESSION['upload_errors'][] = "Invalid file type. Please upload an Excel file (.xls or .xlsx).";
    }

    // Redirect to display results
    header("Location: add_excel.php");
    exit;
}
?>
