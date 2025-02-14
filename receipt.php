<?php
session_start();
include("db.php"); // Include database connection file

if (isset($_GET['reg_no'])) {
    $reg_no = mysqli_real_escape_string($conn, $_GET['reg_no']);

    // Fetch student details from the database
    $sql = "SELECT * FROM student WHERE reg_no = '$reg_no'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);

        // Custom function to convert numbers to words
        function convertNumberToWords($number) {
            $words = array(
                '0' => '', '1' => 'One', '2' => 'Two', '3' => 'Three', '4' => 'Four',
                '5' => 'Five', '6' => 'Six', '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
                '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve', '13' => 'Thirteen',
                '14' => 'Fourteen', '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
                '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty', '30' => 'Thirty',
                '40' => 'Forty', '50' => 'Fifty', '60' => 'Sixty', '70' => 'Seventy',
                '80' => 'Eighty', '90' => 'Ninety'
            );

            $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
            $result = '';

            if ($number == 0) {
                return 'zero';
            }

            $i = 0;
            while ($number > 0) {
                $divider = ($i == 2) ? 10 : 100;
                $amount = $number % $divider;
                $number = floor($number / $divider);

                if ($amount) {
                    $result = (($amount > 19) ? $words[floor($amount / 10) * 10] . ' ' . $words[$amount % 10] : $words[$amount]) . ' ' . $digits[$i] . ' ' . $result;
                }
                $i++;
            }

            return trim($result) . ' Only';
        }

        function generateReceipt($student, $title, $details, $total) {
            echo '<div class="header">';
            echo '<img src="/feereceipt/images/1.png" alt="Left Logo">';
            echo '<div>';
            echo '<h1 class="college-name">R. M. K. COLLEGE OF ENGINEERING AND TECHNOLOGY</h1>';
            echo '<p class="institution-details">(An Autonomous Institution and ISO 9001:2015 Certified Institution,</br> All UG Programmes Accredited by NBA & NAAC with "A")</p>';
            echo '<p class="address">R.S.M. Nagar, Puduvoyal - 601 206.</p>';
            echo '</div>';
            echo '<img src="/feereceipt/images/2.jpg" alt="Right Logo">';
            echo '</div>';
            echo '<h2>' . htmlspecialchars($title) . '</h2>';
        

            echo '<div class="details-container" >';
            echo '<table class="details-table">';
            echo '<tr>';
            // Left Column
            echo '<td class="label" style="width:10%; text-align:left; padding-right:5px;">No</td>';
            echo '<td style="width:2%; text-align:left;">:</td>';
            echo '<td style="width:60%; font-family: \'Courier New\', monospace; font-size: 10px; text-align:left;">' . htmlspecialchars($student['rec_no']) . '</td>';
            // Right Column
            echo '<td class="labelr" style="width:10%; text-align:left; padding-right:5px;">Date</td>';
            echo '<td style="width:2%; text-align:right;">:</td>';
            echo '<td style="width:11%; font-family: Calibri, sans-serif; font-size: 10px; text-align:left;">' . date('d.m.Y') . '</td>';
            echo '</tr>';
            
            echo '<tr>';
            // Left Column
            $prefix = ($student['sex'] === 'M') ? 'Mr.' : 'Mrs.';
            $relation = ($student['sex'] === 'M') ? 'S/O' : 'D/O';
            echo '<td class="label" style="text-align:left; padding-right:5px;">Name</td>';
            echo '<td style="text-align:left;">:</td>';
            echo '<td style="font-family: Calibri, sans-serif; font-size: 10px; font-weight: bold; width:60%;">' . $prefix . ' ' . htmlspecialchars($student['stud_name']) . ' ' . $relation . ' ' . htmlspecialchars($student['father_name']) . '</td>';
            // Right Column
            echo '<td class="labelr" style="text-align:left; padding-right:5px;">Mode</td>';
            echo '<td style="text-align:right;">:</td>';
            echo '<td style="font-family: Calibri, sans-serif; font-size: 10px; width:11%;">' . htmlspecialchars($student['mode']) . '</td>';
            echo '</tr>';
            
            echo '<tr>';
// Left Column
echo '<td class="label" style="text-align:left; padding-right:5px;">Reg. No</td>';
echo '<td style="text-align:left;">:</td>';
echo '<td style="font-family: \'Courier New\', monospace; font-size: 10px; width:60%;">' . htmlspecialchars($student['reg_no']) . '</td>';
// Right Column
echo '<td class="labelr" style="text-align:left; padding-right:5px;">Degree / Branch</td>';
echo '<td style="text-align:right;">:</td>';
echo '<td style="font-family: Calibri, sans-serif; font-size: 10px; width:11%;">' . htmlspecialchars($student['degree_branch']) . '</td>';
echo '</tr>';
            
            //echo '<tr>';
            // Left Column
            $yearMapping = [
                '1' => 'First',
                '2' => 'Second',
                '3' => 'Third',
                '4' => 'Fourth',
            ];
            $yearText = $yearMapping[$student['year']] ?? 'Unknown';
        
            echo '<tr>';
            // Center Column (Year)
            echo '<td colspan="3" style="text-align:center; font-family: Calibri, sans-serif; font-size: 12px; width:11%";>';
            echo 'Year : ' . htmlspecialchars($yearText);
            echo '</td>';
            
            // Right Column (Quota)
            echo '<td class="labelr" colspan="1" style="text-align:left; font-family: Calibri, sans-serif; font-size: 12px; width:11%;">Quota</td>';
            echo '<td style="text-align:right;">:</td>';
            echo '<td style="font-family: Calibri, sans-serif; font-size: 10px; text-align:left;">' . htmlspecialchars($student['quota']) . '</td>';
            echo '</tr>';
            echo '</table>';
            echo '</div>';
            
            
            echo '<table class="fee-table" style="border-collapse: collapse; width: 95%; margin: 0 auto;">';
            echo '<tr>';
            echo '<th style="width: 10%; border-top: 1px solid black; border-bottom: 1px solid black;">S. No.</th>'; // S. No. column
            echo '<th style="width: 65%; border-top: 1px solid black; border-bottom: 1px solid black;">Particulars</th>'; // Particulars column
            echo '<th style="width: 25%; text-align: right; border-top: 1px solid black; border-bottom: 1px solid black; padding-right: 8%;">Amount (Rs.)</th>'; // Amount column
            echo '</tr>';
            
            foreach ($details as $index => $detail) {
                echo '<tr>';
                echo '<td style="width: 10%; ">' . ($index + 1) . '</td>'; // S. No.
                echo '<td style="width: 65%;">' . htmlspecialchars($detail['name']) . '</td>'; // Particulars
                echo '<td style="width: 25%; text-align: right; padding-right: 8%;">' . number_format($detail['amount']) . '</td>'; // Right-aligned Amount
                echo '</tr>';
            }
            
            // Add an empty row for spacing
            echo '<tr><td colspan="3" style="height: 10px;"></td></tr>';
            
            // Total row with alignment
            echo '<tr style="border-top: 1px solid black; border-bottom: 1px solid black;">';
            echo '<td style="border-top: 1px solid black; border-bottom: 1px solid black;"></td>'; // Empty cell for S. No.
            echo '<td style="text-align: left; font-weight: bold; padding-left: 10px; border-top: 1px solid black; border-bottom: 1px solid black;">TOTAL</td>'; // Left-aligned under "Particulars"
            echo '<td style="text-align: right; padding-right: 8%; font-weight: bold; border-top: 1px solid black; border-bottom: 1px solid black;">' . number_format($total) . '</td>'; // Right-aligned under "Amount"
            echo '</tr>';
            echo '</table>';
            
            

            
        
            echo '<p style="font-family: Calibri, sans-serif; font-size: 10px; font-weight: bold; text-align: center; margin-top: 10px;">';
            echo 'Rupees in Words: ' . convertNumberToWords($total);
            echo '</p>';
            echo '<div class="footer" style="margin-top: 40px; display: flex; justify-content: space-between;">';
            echo '<div style="text-align: left; font-family: Calibri, sans-serif; font-size: 10px; margin-left: 8%;">';
            echo '<p>Administrative Officer</p>';
            echo '</div>';
            echo '<div style="text-align: right; font-family: Calibri, sans-serif; font-size: 10px; margin-right: 3%;">';
            echo '<p>Cashier</p>';
            echo '</div>';
            echo '</div>';
            
            // Add dashed line below Administrative Officer and Cashier
            echo '<div style="border-bottom: 1px dashed black; margin-top: 10px;"></div>';
            

            
        }
        
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .page {
            width: 210mm; /* A4 width */
            height: 297mm; /* A4 height */
            margin: 0 auto;
            padding: 10mm; /* Reduce padding to fit content */
            box-sizing: border-box;
            background-color: #fff;
        }
        .section {
            margin-bottom: 10px; /* Minimize spacing */
            border-bottom: 1px dashed black;
        }
        .content {
            font-size: 14px; /* Reduce font size */
            line-height: 1.2; /* Tighter line height */
        }
        .section:last-child {
            border-bottom: none;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .header img {
            height: 60px;
        }
        .header div {
            text-align: center;
            flex: 1;
        }
        .college-name {
            font-family: Calibri, sans-serif;
            font-size: 14px;
            font-weight: bold;
        }
        .institution-details {
            font-family: Calibri, sans-serif;
            font-size: 10px;
            font-style: italic;
        }
        .address {
            font-family: Calibri, sans-serif;
            font-size: 11px;
            font-weight: bold;
        }
        h1{
            text-align: center;
            
            margin: 10px 0; 
        }
        h2 {
            text-align: center;
            font-family: "Times New Roman", serif;
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            padding: 4px;
            text-align: left;
            font-size: 10px;
        }
        .fee-table {
            width: 95%;
            margin: 0 auto;
        }

        .fee-table th:last-child, .fee-table td:last-child {
            text-align: right;
            padding-right: 8%;
        }

        .fee-table td, .fee-table th {
            padding: 4px;
        }

        .footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .details-table .label {
        text-align: left;
        padding-left: 5mm; /* Increase the left gap for labels */
        padding-right: 4mm; /* Maintain a consistent gap between the label and value */
        /* Optional: Makes the labels more distinct */
    }
        .footer p {
            font-size: 10px;
        }
        .print-buttons {
            position: fixed;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }
        .print-buttons button {
            padding: 10px 15px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .print-buttons .back-btn{
            background-color: rebeccapurple;
            color: white;
        }
        .print-buttons .print-btn {
            background-color: #4CAF50;
            color: white;
        }
        .print-buttons .word-btn {
            background-color: #2196F3;
            color: white;
        }
        @media print {
            body {
                margin: 0;
            }
            .print-buttons {
                display: none;
            }
            
            @page{
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-buttons">
        <button class="back-btn" onclick="history.back()">Back</button>
        <button class="print-btn" onclick="window.print()">Print Receipt</button>
        <!--<button class="word-btn" onclick="downloadWord()">Save as Word</button>-->
    </div>
    <div class="page">
    <?php
// Tuition Fee Section
$tuitionDetails = [
    ['name' => 'TUITION FEE', 'amount' => $student['tuti']],
    ['name' => 'DEVELOPMENT FEE', 'amount' => $student['dev']],
    ['name' => 'TRAINING AND PLACEMENT', 'amount' => $student['trai_pl']],
    ['name' => 'CAUTION DEPOSIT', 'amount' => $student['cau_dep']],
];
$totalTuition = array_sum(array_column($tuitionDetails, 'amount'));

// Pass rec_no1 for the tuition receipt
$student['rec_no'] = $student['rec_no1'];
generateReceipt($student, 'TUITION RECEIPT', $tuitionDetails, $totalTuition);

// Hostel Fee Section
$hostelDetails = [
    ['name' => 'BREAKFAST & LUNCH FEE', 'amount' => $student['mess']],
    ['name' => 'TRANSPORT', 'amount' => $student['bus']],
    ['name' => 'ONLINE MATERIALS AND BOOKS', 'amount' => $student['online']],
    ['name' => 'HOSTEL', 'amount' => $student['hostel']],
];
$totalHostel = array_sum(array_column($hostelDetails, 'amount'));

// Pass rec_no2 for the hostel receipt
$student['rec_no'] = $student['rec_no2'];
generateReceipt($student, 'HOSTEL RECEIPT', $hostelDetails, $totalHostel);
?>

        </div>
    </div>
    <script>
        function downloadWord() {
            const content = document.querySelector('.page').outerHTML;
            const header = `
                <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
                <head><meta charset="UTF-8"></head><body>`;
            const footer = "</body></html>";
            const sourceHTML = header + content + footer;

            const blob = new Blob(['\ufeff', sourceHTML], { type: 'application/msword' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'Fee_Receipts.doc';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
    </script>
</body>
</html>


<?php
    } else {
        echo '<p style="color:red;">No receipt found for registration number: ' . htmlspecialchars($reg_no) . '</p>';
    }
} else {
    echo '<p style="color:red;">Invalid access. Registration number is required.</p>';
}
?>
