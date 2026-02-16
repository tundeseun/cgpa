<?php
// Turn off all error reporting to prevent errors appearing in the output
error_reporting(0);
ini_set('display_errors', 0);

require_once 'vendor/autoload.php';
require_once('function/script.php');

$board_date = $_GET['board'];
// $board_date = '2024-09-26';

try {
    // Creating new spreadsheet
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator("University of Ibadan")
        ->setLastModifiedBy("University of Ibadan")
        ->setTitle("PhD Certificate List")
        ->setSubject("PhD Certificate List")
        ->setDescription("List of PhD candidates for certificate approval")
        ->setKeywords("PhD Certificate List")
        ->setCategory("Academic Records");
    
    // Set up heading styles
    $headingStyle = [
        'font' => [
            'bold' => true,
            'size' => 11,
            'name' => 'Times New Roman',
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];
    
    // Set column widths
    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(10);
    $sheet->getColumnDimension('C')->setWidth(40);
    $sheet->getColumnDimension('D')->setWidth(30);
    $sheet->getColumnDimension('E')->setWidth(30);
    $sheet->getColumnDimension('F')->setWidth(10);
    $sheet->getColumnDimension('G')->setWidth(40);
    $sheet->getColumnDimension('H')->setWidth(20);
    $sheet->getColumnDimension('I')->setWidth(10);
    $sheet->getColumnDimension('J')->setWidth(20);
    
    // Add header
    $sheet->setCellValue('A1', 'MATRIC');
    $sheet->setCellValue('B1', 'UI SPECIAL NO');
    $sheet->setCellValue('C1', 'NAME');
    $sheet->setCellValue('D1', 'DATE OF BIRTH');
    $sheet->setCellValue('E1', 'TYPE OF DEGREE');
    $sheet->setCellValue('F1', 'IN THE');
    $sheet->setCellValue('G1', 'FACULTY');
    $sheet->setCellValue('H1', 'EFFECTIVE DATE');
    $sheet->setCellValue('I1', 'PICTURE');
    $sheet->setCellValue('J1', 'APPLICATION NO');
    
    // Apply heading styles and borders to header row
    $sheet->getStyle('A1:J1')->applyFromArray($headingStyle);
    $sheet->getStyle('A1:J1')->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'E0E0E0',
            ],
        ],
    ]);
    
    // Set row index to start after headers
    $row = 2;
    
    // Data style
    $dataStyle = [
        'font' => [
            'name' => 'Times New Roman',
            'size' => 11,
        ],
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
            'wrapText' => true,
        ],
    ];
    
    // Border style for tables
    $borderStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ];
    
    // Query all candidates
    $sql = "SELECT DISTINCT 
                n.Surname, 
                n.Other_names, 
                pa.matric, 
                za.date_of_birth,
                rt.faculty_date, 
                rt.propose_title, 
                rt.supervisor, 
                rt.co_supervisor,
                d.department,
                f.faculty,
                n.numeration
            FROM registration_title rt
            LEFT JOIN new n ON rt.candidate_id = n.id 
            LEFT JOIN prev_app pa ON rt.candidate_id = pa.user_id
            LEFT JOIN zmain_app za ON rt.candidate_id = za.user_id
            LEFT JOIN reginvoice ON reginvoice.appno = n.numeration
            LEFT JOIN dept_new d ON rt.department_id = d.id
            LEFT JOIN fac_new f ON rt.fac_id = f.id
            WHERE rt.exco_date = '$board_date' 
            AND rt.status = 4 
            AND rt.reject_by IS NULL 
            AND rt.reason IS NULL
            AND reginvoice.amount_paid = reginvoice.amount_charge 
            AND reginvoice.amount_paid > 0 
            ORDER BY f.faculty, d.department, n.Surname";
    
    $result = $conn->query($sql);
    
    while ($row_new = mysqli_fetch_assoc($result)) {
        // Format dates
        $effectivedate = $row_new['faculty_date'];
        $timestamp = strtotime($effectivedate);
        $formattedEffectiveDate = date("F j, Y", $timestamp);
        
        // Format birth date
        $birthdate = $row_new['date_of_birth'];
        $birthtimestamp = strtotime($birthdate);
        $formattedBirthDate = date("F j, Y", $birthtimestamp);
        
        // Get UI special number (assuming it's stored somewhere or can be generated)
        $ui_special_no = $row_new['matric'].'UI'; // Add logic to get UI special number if available
        
        // Full name in proper format
        $fullName = trim($row_new['Surname'] . ', ' . $row_new['Other_names']);
        
        // Department name in proper format
        $department = trim($row_new['department']);
        
        // Faculty name in proper format
        $faculty = trim($row_new['faculty']);
        
        // Add student info to the row
        $sheet->setCellValue('A' . $row, $row_new['matric']);
        $sheet->setCellValue('B' . $row, $ui_special_no);
        $sheet->setCellValue('C' . $row, mb_convert_case(mb_strtolower($fullName, 'UTF-8'), MB_CASE_TITLE, 'UTF-8'));
        $sheet->setCellValue('D' . $row, $formattedBirthDate);
        $sheet->setCellValue('E' . $row, 'Doctor of Philosophy');
        $sheet->setCellValue('F' . $row, 'in the');
        $sheet->setCellValue('G' . $row, 'Faculty of '.$faculty);
        $sheet->setCellValue('H' . $row, $formattedEffectiveDate);
        $sheet->setCellValue('I' . $row, ''); // Picture reference (if available)
        $sheet->setCellValue('J' . $row, $row_new['numeration']);
        
        // Apply styles to the row
        $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray($dataStyle);
        $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray($borderStyle);
        
        // Increment row counter
        $row++;
    }
    
    // Auto-filter for easy filtering
    $sheet->setAutoFilter('A1:J' . ($row - 1));
    
    // Create writer and save file
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = "PhD_Certificate_List.xlsx";
    
    // Make sure nothing has been output to the browser yet
    if (ob_get_length()) ob_end_clean();
    
    // Set headers for file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    // Save to php://output
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    // Log the error (optional)
    error_log('Excel generation error: ' . $e->getMessage());
    
    // Provide a clean error message
    echo "An error occurred while generating the Excel file: " . $e->getMessage();
}
?>