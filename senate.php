<?php
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
$section->addText('DEGREE OF MASTER', $fontStyleName, $paragraphStyleName);
$section->addText('', $fontStyleName, $paragraphStyleName);

$fancyTableStyleName = 'Fancy Table';
$fancyTableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER, 'cellPadding' => 5];
$fancyTableCellStyle = ['valign' => 'center'];
$fancyTableCellBtlrStyle = ['valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR];
$fancyTableFontStyle = ['bold' => true];
$phpWord->addTableStyle($fancyTableStyleName, $fancyTableStyle);


$query_name = "SELECT DISTINCT degree_new.degree, dept_new.department, dept_new.id AS dept_id, fac_new.faculty, fac_new.id AS fac_id FROM testscore INNER JOIN dept_new ON testscore.dept = dept_new.id INNER JOIN fac_new ON testscore.fac = fac_new.id INNER JOIN degree_new ON testscore.degree = degree_new.id  WHERE testscore.board_date = '$board_date'";
$result_name = mysqli_query($conn, $query_name);

while ($row = mysqli_fetch_assoc($result_name)) {
    $section->addText('FACULTY OF '.strtoupper($row['faculty']), $fontStyleName3,$paragraphStyleName4);
    $section->addText('Department of '.$row['department'].' ('.strtoupper($row['degree']).')', $fontStyleName3,$paragraphStyleName4);
    $dept_id = $row['dept_id'];
    $fac_id = $row['fac_id'];
    $sql = "SELECT DISTINCT n.Surname, n.Other_names, t.matric, t.effectivedate, r.naration
            FROM testscore t
            LEFT JOIN new n ON t.user_id = n.id 
            LEFT JOIN remark ON t.user_id = remark.user_id
            LEFT JOIN rendition r ON t.field = r.specialization
            LEFT JOIN reginvoice ON reginvoice.appno = n.numeration
            WHERE t.dept = '$dept_id' AND t.fac = '$fac_id' AND reginvoice.amount_paid = reginvoice.amount_charge AND reginvoice.amount_paid > 0 AND remark.remark <> 'NG'  AND t.board_date = '$board_date'
            ORDER BY n.Surname";

    $result = $conn->query($sql);
    
    $table = $section->addTable($fancyTableStyleName);    
    while ($row_new = mysqli_fetch_assoc($result)) {
        $effectivedate = $row_new['effectivedate'];
    $timestamp = strtotime($effectivedate);
    $formattedDate = date("j F, Y", $timestamp);
        $table->addRow();
     $table->addCell(2000)->addText(htmlspecialchars($row_new['matric']),$fontStyleNameT);
     $table->addCell(5000)->addText(htmlspecialchars($row_new['Surname'] . ', ' . $row_new['Other_names']),$fontStyleNameT);
     $table->addCell(6000)->addText(htmlspecialchars($row_new['naration']),$fontStyleNameT);
     $table->addCell(3000)->addText(htmlspecialchars($formattedDate),$fontStyleNameT);
    }
}


$filename = "SenateList.docx";
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($filename);

// Download the file
header("Content-Disposition: attachment; filename=$filename");
readfile($filename);

