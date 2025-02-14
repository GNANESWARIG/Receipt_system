<?php
require __DIR__ . '/vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\IOFactory; 
use PhpOffice\PhpSpreadsheet\Cell\Coordinate; 

include("db.php"); 

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
                $isEmptyRow = true; // Flag to check if the row is empty

                for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                    $columnLetter = Coordinate::stringFromColumnIndex($col);
                    $cellValue = $worksheet->getCell($columnLetter . $row)->getValue();
                    $cellValue = $cellValue !== null ? trim($cellValue) : ''; // Prevent null trim() error

                    if ($cellValue !== '') {
                        $isEmptyRow = false; // Mark as non-empty if any cell has data
                    }

                    $data[] = mysqli_real_escape_string($conn, $cellValue);
                }

                if ($isEmptyRow) {
                    continue; // Skip completely empty rows
                }

                // Assign variables
                $reg_no = $data[0] ?? null;
                $stud_name = $data[1] ?? null;
                $sex = strtoupper($data[2] ?? '');
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

                // Skip invalid rows
                if (!in_array($sex, ['M', 'F'])) {
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

                $stmt->bind_param("ssssssssssssssssss", $reg_no, $stud_name, $sex, $father_name, $year, $degree_branch, $rec_no1, $quota, $mode, $tuti, $dev, $trai_pl, $cau_dep, $rec_no2, $hostel, $online, $bus, $mess);

                if (!$stmt->execute()) {
                    die("Error inserting/updating row $row: " . $stmt->error);
                }

                $stmt->close();
            }

            echo "<script>alert('Student details added/updated successfully');window.location.replace('add_excel.php');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error processing file: " . $e->getMessage() . "');window.location.replace('add_excel.php');</script>";
        }
    } else {
        echo "<script>alert('Please choose an Excel file (.xls or .xlsx) only');window.location.replace('add_excel.php');</script>";
    }
}
