require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

include("db.php");

if (isset($_POST["uploadfile"])) {
    $fileTmpPath = $_FILES["myfile"]["tmp_name"];
    $fileType = pathinfo($_FILES["myfile"]["name"], PATHINFO_EXTENSION);
    $allowedTypes = ['xls', 'xlsx'];

    // Validate the file type
    if (in_array($fileType, $allowedTypes)) {
        try {
            // Load the Excel file directly from the temp path
            $spreadsheet = IOFactory::load($fileTmpPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

            // Loop through each row of the worksheet
            for ($row = 2; $row <= $highestRow; ++$row) {
                $data = [];
                for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                    $columnLetter = Coordinate::stringFromColumnIndex($col);
                    $cellAddress = $columnLetter . $row;
                    $cellValue = $worksheet->getCell($cellAddress)->getValue();
                    $data[] = mysqli_real_escape_string($conn, trim($cellValue));
                }

                // Assign variables
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

                // Validate sex field
                if (!in_array($sex, ['M', 'F'])) {
                    error_log("Invalid sex value on row $row: $sex");
                    continue; // Skip this row
                }

                // Prepare SQL statement
                $stmt = $conn->prepare("INSERT INTO student (reg_no, stud_name, sex, father_name, year, degree_branch, rec_no1, quota, mode, tuti, dev, trai_pl, cau_dep, rec_no2, hostel, online, bus, mess) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                // Bind parameters
                $stmt->bind_param("ssssssssssssssssss", $reg_no, $stud_name, $sex, $father_name, $year, $degree_branch, $rec_no1, $quota, $mode, $tuti, $dev, $trai_pl, $cau_dep, $rec_no2, $hostel, $online, $bus, $mess);

                // Execute the statement
                if (!$stmt->execute()) {
                    echo "Error inserting row $row: " . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            }

            echo "<script>alert('Student details added successfully');window.location.replace('averify.php');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error processing file: " . $e->getMessage() . "');window.location.replace('add_excel.php');</script>";
        }
    } else {
        echo "<script>alert('Please choose an Excel file (.xls or .xlsx) only');window.location.replace('add_excel.php');</script>";
    }
}
