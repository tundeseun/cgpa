<?php
require_once('function/script.php');
require 'vendor/autoload.php'; // Include Composer autoloader

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

ob_start();
// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();


$spreadsheet->getProperties()
    ->setCreator("Your Name")
    ->setLastModifiedBy("Your Name")
    ->setTitle("Test Scores")
    ->setDescription("Test scores exported from database")
    ->setKeywords("test scores phpspreadsheet export")
    ->setCategory("Test Scores");

// Add a worksheet
$sheet = $spreadsheet->getActiveSheet();

// Add column headers
$sheet->setCellValue('A1', 'MATRIC');
$sheet->setCellValue('B1', 'UI SPECIAL NO');
$sheet->setCellValue('C1', 'NAME');
$sheet->setCellValue('D1', 'DATE OF BIRTH');
$sheet->setCellValue('E1', 'NARATION');
$sheet->setCellValue('F1', 'FACULTY');
$sheet->setCellValue('G1', 'DATE OF AWARD');
$sheet->setCellValue('H1', 'PICTURE');
$sheet->setCellValue('I1', 'APPLICATION NO');

// Execute your SQL query
$sql = "SELECT DISTINCT n.Surname, n.Other_names, t.matric, t.effectivedate, r.naration, z.date_of_birth, f.faculty, n.numeration
    FROM testscore t
    LEFT JOIN new n ON t.user_id = n.id 
    LEFT JOIN fac_new f ON t.fac = f.id 
    LEFT JOIN zmain_app z ON t.user_id = z.user_id 
    LEFT JOIN remark ON t.user_id = remark.user_id
    LEFT JOIN rendition r ON t.field = r.specialization
    LEFT JOIN reginvoice ON reginvoice.appno = n.numeration
    WHERE reginvoice.amount_paid = reginvoice.amount_charge AND reginvoice.amount_paid > 0 AND remark.remark <> 'NG'
    ORDER BY n.Surname";

// Execute the query and fetch results
// Assuming you have a database connection established and stored in $conn
$result = mysqli_query($conn, $sql);

// Populate the Excel sheet with data
$row = 2; // start from row 2 (after headers)
while ($row_data = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row, $row_data['matric']);
    $sheet->setCellValue('B' . $row, $row_data['matric']."UI");
    $sheet->setCellValue('C' . $row, $row_data['Other_names']." ".$row_data['Surname']);
    $sheet->setCellValue('D' . $row, $row_data['date_of_birth']);
    $sheet->setCellValue('E' . $row, $row_data['naration']);
    $sheet->setCellValue('F' . $row, $row_data['faculty']);
    $sheet->setCellValue('G' . $row, $row_data['effectivedate']);
    $sheet->setCellValue('H' . $row, "PICTURE");
    $sheet->setCellValue('I' . $row, $row_data['numeration']);
    $row++;
}

// Set column widths (optional)
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(50);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="test_scores22.xlsx"');
header('Cache-Control: max-age=0');

// Save Excel file to the browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

?>
