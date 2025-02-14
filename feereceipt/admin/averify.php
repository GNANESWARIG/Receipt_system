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
            $highestColumnIndex = Coordinate::columnIndexFromString($worksheet->getHighestColumn());

            for ($row = 2; $row <= $highestRow; ++$row) {
                $data = [];
                for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                    $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $data[] = mysqli_real_escape_string($conn, trim($cellValue));
                }

                // Assign variables from the Excel data
                $reg_no = $data[0] ?? null;
                $stud_name = $data[1] ?? null;
                $sex = strtoupper($data[2] ?? null);
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

                // Skip row if reg_no is empty
                if (empty($reg_no)) {
                    echo "Skipping row $row: Missing reg_no!<br>";
                    continue;
                }

                // Check if student already exists
                $checkStmt = $conn->prepare("SELECT COUNT(*) FROM student WHERE reg_no = ?");
                $checkStmt->bind_param("s", $reg_no);
                $checkStmt->execute();
                $checkStmt->bind_result($count);
                $checkStmt->fetch();
                $checkStmt->close();

                if ($count > 0) {
                    // Update existing record
                    $stmt = $conn->prepare("
                        UPDATE student SET 
                        stud_name = ?, sex = ?, father_name = ?, year = ?, degree_branch = ?, 
                        rec_no1 = ?, quota = ?, mode = ?, tuti = ?, dev = ?, trai_pl = ?, 
                        cau_dep = ?, rec_no2 = ?, hostel = ?, online = ?, bus = ?, mess = ?
                        WHERE reg_no = ?
                    ");
                    $stmt->bind_param("ssssssssssssssssss", $stud_name, $sex, $father_name, $year, 
                                      $degree_branch, $rec_no1, $quota, $mode, $tuti, $dev, 
                                      $trai_pl, $cau_dep, $rec_no2, $hostel, $online, $bus, 
                                      $mess, $reg_no);
                } else {
                    // Insert new record
                    $stmt = $conn->prepare("
                        INSERT INTO student 
                        (reg_no, stud_name, sex, father_name, year, degree_branch, rec_no1, quota, 
                        mode, tuti, dev, trai_pl, cau_dep, rec_no2, hostel, online, bus, mess) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->bind_param("ssssssssssssssssss", $reg_no, $stud_name, $sex, $father_name, 
                                      $year, $degree_branch, $rec_no1, $quota, $mode, $tuti, 
                                      $dev, $trai_pl, $cau_dep, $rec_no2, $hostel, $online, 
                                      $bus, $mess);
                }

                // Execute query
                if (!$stmt->execute()) {
                    echo "❌ Error in row $row: " . $stmt->error . "<br>";
                } else {
                    echo "✅ Row $row updated successfully!<br>";
                }

                $stmt->close();
            }

            echo "<script>alert('Student details added/updated successfully!'); window.location.replace('add_excel.php');</script>";

        } catch (Exception $e) {
            echo "<script>alert('Error processing file: " . $e->getMessage() . "'); window.location.replace('add_excel.php');</script>";
        }
    } else {
        echo "<script>alert('Please upload a valid Excel file (.xls or .xlsx)'); window.location.replace('add_excel.php');</script>";
    }
}
?>
