<?php
// Turn off all error reporting to prevent errors appearing in the output
error_reporting(0);
ini_set('display_errors', 0);

require_once 'vendor/autoload.php';
require_once('function/script.php');

$board_date = $_GET['board'];
// $board_date = '2025-04-08 17:14:20';

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
    $sheet->getColumnDimension('A')->setWidth(15);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(40);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(30);
    $sheet->getColumnDimension('F')->setWidth(5);
    $sheet->getColumnDimension('G')->setWidth(40);
    $sheet->getColumnDimension('H')->setWidth(15);
    $sheet->getColumnDimension('I')->setWidth(30);
    $sheet->getColumnDimension('J')->setWidth(20);
    $sheet->getColumnDimension('K')->setWidth(10);
    $sheet->getColumnDimension('L')->setWidth(30);
    
    // Add header
    $sheet->setCellValue('A1', 'MATRIC');
    $sheet->setCellValue('B1', 'UI SPECIAL NO');
    $sheet->setCellValue('C1', 'NAME');
    $sheet->setCellValue('D1', 'DATE OF BIRTH');
    $sheet->setCellValue('E1', 'TYPE OF DEGREE');
    $sheet->setCellValue('F1', 'IN');
    $sheet->setCellValue('G1', 'COURSE OF STUDY');
    $sheet->setCellValue('H1', 'IN THE');
    $sheet->setCellValue('I1', 'FACULTY');
    $sheet->setCellValue('J1', 'EFFECTIVE DATE');
    $sheet->setCellValue('K1', 'PICTURE');
    $sheet->setCellValue('L1', 'APPLICATION NO');
    
    // Apply heading styles and borders to header row
    $sheet->getStyle('A1:L1')->applyFromArray($headingStyle);
    $sheet->getStyle('A1:L1')->applyFromArray([
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
    
    // Query all candidates with rendition table data
    $sql = "SELECT DISTINCT 
                n.Surname, 
                n.Other_names, 
                pa.matric, 
                za.date_of_birth,
                t.effectivedate, 
                d.department,
                f.faculty,
                n.numeration,
                r.naration
            FROM testscore t
            LEFT JOIN new n ON t.user_id = n.id 
            LEFT JOIN prev_app pa ON t.user_id = pa.user_id
            LEFT JOIN zmain_app za ON t.user_id = za.user_id
            LEFT JOIN reginvoice ON reginvoice.appno = n.numeration
            LEFT JOIN dept_new d ON t.dept = d.id
            LEFT JOIN fac_new f ON t.fac = f.id
            LEFT JOIN remark ON t.user_id = remark.user_id
            LEFT JOIN rendition r ON r.dept = t.dept
            AND r.degree = t.degree AND r.specialization = t.field
            WHERE t.board_date = '$board_date' 
            AND t.stage = 4 
            AND remark.remark <> 'NG'
            AND reginvoice.amount_paid = reginvoice.amount_charge 
            AND reginvoice.amount_paid > 0 
            ORDER BY f.faculty, d.department, n.Surname";
    
    $result = $conn->query($sql);
    
    while ($row_new = mysqli_fetch_assoc($result)) {
        // Format dates
        $effectivedate = $row_new['effectivedate'];
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
        
        // Parse narataion for degree information
        $narataion = $row_new['naration'] ?? '';
        $degree_type = '';
        $course_of_study = '';
        
        // Parse the narataion field to extract TYPE OF DEGREE and COURSE OF STUDY
        if (!empty($narataion)) {
            // Pattern 1: "Master of Science in Banking and Finance"
            if (preg_match('/^(.*?)\s+of\s+(.*?)\s+in\s+(.*)$/i', $narataion, $matches)) {
                $degree_type = trim($matches[1] . ' of ' . $matches[2]); // "Master of Science"
                $course_of_study = trim($matches[3]); // "Banking and Finance"
            }
            // Pattern 2: "Master in Business Computing"
            elseif (preg_match('/^(.*?)\s+in\s+(.*)$/i', $narataion, $matches)) {
                $degree_type = trim($matches[1]); // "Master"
                $course_of_study = trim($matches[2]); // "Business Computing"
            }
            else {
                // If neither pattern matches, use the entire narataion as degree type
                $degree_type = $narataion;
            }
        }
        
        
        // Add student info to the row
        $sheet->setCellValue('A' . $row, $row_new['matric']);
        $sheet->setCellValue('B' . $row, $ui_special_no);
        $sheet->setCellValue('C' . $row, mb_convert_case(mb_strtolower($fullName, 'UTF-8'), MB_CASE_TITLE, 'UTF-8'));
        $sheet->setCellValue('D' . $row, $formattedBirthDate);
        $sheet->setCellValue('E' . $row, $degree_type);
        $sheet->setCellValue('F' . $row, 'in');
        $sheet->setCellValue('G' . $row, $course_of_study);
        $sheet->setCellValue('H' . $row, 'in the');
        $sheet->setCellValue('I' . $row, 'Faculty of '.$faculty);
        $sheet->setCellValue('J' . $row, $formattedEffectiveDate);
        $sheet->setCellValue('K' . $row, ''); // Picture reference (if available)
        $sheet->setCellValue('L' . $row, $row_new['numeration']);
        
        // Apply styles to the row
        $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray($dataStyle);
        $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray($borderStyle);
        
        // Increment row counter
        $row++;
    }
    
    // Auto-filter for easy filtering
    $sheet->setAutoFilter('A1:L' . ($row - 1));
    
    // Create writer and save file
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = "MSc_Certificate_List.xlsx";
    
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