<?php
error_reporting(1);
ini_set('display_errors', 1);
require_once 'vendor/autoload.php';
require_once('function/script.php');

$board_date = $_GET['board'];

// Creating the new document...
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$fontStyleName = 'rStyle';
$phpWord->addFontStyle($fontStyleName, ['name' => 'Times New Roman','bold' => true, 'size' => 14, 'allCaps' => true]);

$fontStyleName3 = 'rStyle3';
$phpWord->addFontStyle($fontStyleName3, ['name' => 'Times New Roman','bold' => true, 'size' => 14, 'allCaps' => false]);

$fontStyleNameT = 'rStyleT';
$phpWord->addFontStyle($fontStyleNameT, ['name' => 'Times New Roman','bold' => false, 'size' => 12, 'allCaps' => false]);

$fontStyleName2 = 'rStyle2';
$phpWord->addFontStyle($fontStyleName2, ['name' => 'Times New Roman','bold' => false, 'size' => 14, 'allCaps' => false]);

$paragraphStyleName = 'pStyle';
$phpWord->addParagraphStyle($paragraphStyleName, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100]);

$paragraphStyleName4 = 'pStyle4';
$phpWord->addParagraphStyle($paragraphStyleName4, ['spaceAfter' => 0]);

$section = $phpWord->addSection();
/* Note: any element you append to a document must reside inside of a Section. */

$section->addText('UNIVERSITY OF IBADAN', $fontStyleName, $paragraphStyleName);
$section->addText('HIGHER DEGREE EXAMINATION RESULTS', $fontStyleName, $paragraphStyleName);
$section->addText('DEGREE OF DOCTOR OF PHILOSOPHY', $fontStyleName, $paragraphStyleName);
$section->addText('', $fontStyleName, $paragraphStyleName);

$fancyTableStyleName = 'Fancy Table';
//$fancyTableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER, 'cellSpacing' => 0]; // Added table style definition
$fancyTableCellStyle = ['valign' => 'center'];
$fancyTableCellBtlrStyle = ['valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR];
$fancyTableFontStyle = ['bold' => true];
$phpWord->addTableStyle($fancyTableStyleName, $fancyTableStyle);

// Function to clean HTML and CSS from text
function cleanText($text) {
    // Remove HTML tags
    $text = strip_tags($text);
    
    // Remove CSS styles (anything between style attributes)
    $text = preg_replace('/style\s*=\s*["\'][^"\']*["\']/i', '', $text);
    
    // Remove any remaining HTML entities
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    // Remove extra whitespace and normalize
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);
    
    return $text;
}

// Assuming the new table is named 'exam_details' that contains user_id and board_date
$query_distinct = "SELECT DISTINCT rt.department_id, rt.fac_id 
                  FROM registration_title rt 
                  WHERE rt.exco_date = '$board_date' AND rt.status = 4 
            AND rt.reject_by IS NULL 
            AND rt.reason IS NULL ";
$result_distinct = mysqli_query($conn, $query_distinct);

while ($row_distinct = mysqli_fetch_assoc($result_distinct)) {
    $dept_id = $row_distinct['department_id'];
    $fac_id = $row_distinct['fac_id'];
    
    // Get department and faculty names
    $dept_query = "SELECT department FROM dept_new WHERE id = '$dept_id'";
    $dept_result = mysqli_query($conn, $dept_query);
    $dept_row = mysqli_fetch_assoc($dept_result);
    $department = $dept_row['department'];
    
    $fac_query = "SELECT faculty FROM fac_new WHERE id = '$fac_id'";
    $fac_result = mysqli_query($conn, $fac_query);
    $fac_row = mysqli_fetch_assoc($fac_result);
    $faculty = $fac_row['faculty'];
    
    $section->addText('FACULTY OF '.strtoupper($faculty), $fontStyleName3, $paragraphStyleName4);
    $section->addText('Department of '.$department, $fontStyleName3, $paragraphStyleName4);
    $section->addText('', $fontStyleName, $paragraphStyleName);
    
    $sql = "SELECT DISTINCT n.Surname, n.Other_names, pa.matric, rt.faculty_date, rt.propose_title, rt.supervisor, rt.co_supervisor
            FROM registration_title rt
            LEFT JOIN new n ON rt.candidate_id = n.id 
            LEFT JOIN prev_app pa ON rt.candidate_id = pa.user_id
            LEFT JOIN reginvoice ON reginvoice.appno = n.numeration
            WHERE rt.department_id = '$dept_id' AND rt.fac_id = '$fac_id' 
            AND reginvoice.amount_paid = reginvoice.amount_charge 
            AND reginvoice.amount_paid > 0 
            AND rt.status = 4 
            AND rt.reject_by IS NULL 
            AND rt.reason IS NULL 
            AND rt.exco_date = '$board_date'
            ORDER BY n.Surname";

    $result = $conn->query($sql);
    
    $table = $section->addTable($fancyTableStyleName);    
    while ($row_new = mysqli_fetch_assoc($result)) {
        $effectivedate = $row_new['faculty_date'];
        $timestamp = strtotime($effectivedate);
        $formattedDate = date("j F, Y", $timestamp);
        
        // Process the proposal title properly - CLEAN HTML/CSS FIRST
        $proposalTitle = cleanText($row_new['propose_title']); // Clean HTML tags and CSS
        $formattedTitle = mb_convert_case(mb_strtolower($proposalTitle, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
        
        $table->addRow();
        $table->addCell(2000)->addText(htmlspecialchars($row_new['matric']), $fontStyleNameT);
        $table->addCell(5000)->addText(htmlspecialchars($row_new['Surname'] . ', ' . $row_new['Other_names']), $fontStyleNameT);
        $table->addCell(3000)->addText(htmlspecialchars($formattedDate), $fontStyleNameT);
        
        $table->addRow();
        $cell1 = $table->addCell(2000);
        $cell1->addText(htmlspecialchars("Thesis:"), $fontStyleNameT);
        
        // Set up the thesis cell to wrap text
        $cellStyle = array('valign' => 'top', 'gridSpan' => 2);
        $cell2 = $table->addCell(8000, $cellStyle);
        $paragraphStyle = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT);
        $cell2->addText(htmlspecialchars($formattedTitle), $fontStyleNameT, $paragraphStyle);
        
        $table->addRow();
        $table->addCell(2000)->addText(htmlspecialchars("Supervisor(s):"), $fontStyleNameT);
        
        // Properly format supervisor information
        $supervisorInfo = trim($row_new['supervisor']);
        if (!empty($row_new['co_supervisor'])) {
            $supervisorInfo .= ', ' . trim($row_new['co_supervisor']);
        }
        
        $table->addCell(8000, ['gridSpan' => 2])->addText(htmlspecialchars($supervisorInfo), $fontStyleNameT);
        
        $table->addRow();
        $emptyCell = $table->addCell(10000, ['gridSpan' => 3, 'cellMargin' => 0, 'height' => 200]);
        $emptyCell->addText('', $fontStyleNameT);

    }
}

$filename = "PhD_SenateList.docx";
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($filename);

// Download the file
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);
?>