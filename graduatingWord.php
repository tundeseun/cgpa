<?php
session_start();

if (isset($_GET["dept"])) {
    $dept = $_GET["dept"];
} else {
    $dept = $_SESSION["dept_new"];
}

include 'function/script.php';
$sec           = $_GET['sec'];
$field         = $_GET['field'];
$degree        = $_GET['degree'];
$effectivedate = $_GET['effectivedate'];
$resulttype    = $_GET['resulttype'];

// Load PHPWord
require_once 'vendor/autoload.php';

// Creating the new document
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Define font styles
$headerFontStyle = 'headerStyle';
$phpWord->addFontStyle($headerFontStyle, [
    'name'    => 'Times New Roman',
    'bold'    => true,
    'size'    => 14,
    'allCaps' => true,
]);

$tableFontStyle = 'tableStyle';
$phpWord->addFontStyle($tableFontStyle, [
    'name' => 'Arial',
    'bold' => false,
    'size' => 12,
]);

$tableHeaderFontStyle = 'tableHeaderStyle';
$phpWord->addFontStyle($tableHeaderFontStyle, [
    'name' => 'Arial',
    'bold' => true,
    'size' => 12,
]);

$signatureFontStyle = 'signatureStyle';
$phpWord->addFontStyle($signatureFontStyle, [
    'name' => 'Times New Roman',
    'bold' => true,
    'size' => 12,
]);

// Define paragraph styles
$centerParagraphStyle = 'centerStyle';
$phpWord->addParagraphStyle($centerParagraphStyle, [
    'alignment'  => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
    'spaceAfter' => 200,
]);

$normalParagraphStyle = 'normalStyle';
$phpWord->addParagraphStyle($normalParagraphStyle, [
    'spaceAfter' => 100,
]);

// Define table style
$tableStyleName = 'graduatingListTable';
$tableStyle     = [
    'borderSize'  => 6,
    'borderColor' => '000000',
    'cellMargin'  => 80,
    'alignment'   => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
    'cellPadding' => 100,
];
$phpWord->addTableStyle($tableStyleName, $tableStyle);

// Create section with landscape orientation
$sectionStyle = [
    'orientation'  => 'landscape',
    'marginLeft'   => 500,
    'marginRight'  => 500,
    'marginTop'    => 500,
    'marginBottom' => 500,
];
$section = $phpWord->addSection($sectionStyle);

// Add header content (you'll need to implement GradListHeaderWord function)
addGradListHeader($section, $conn, $dept, $field, $degree, $sec, $resulttype, $headerFontStyle, $centerParagraphStyle);

// Add graduating list table
addGraduatingListTable($section, $conn, $field, $effectivedate, $dept,$degree, $resulttype, $tableStyleName, $tableHeaderFontStyle, $tableFontStyle, $signatureFontStyle, $centerParagraphStyle);

// Save and download the document
$filename  = "GraduatingList.docx";
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($filename);

// Download the file
header("Content-Disposition: attachment; filename=$filename");
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
readfile($filename);

// Clean up
unlink($filename);

function addGradListHeader($section, $conn, $dept, $field, $degree, $sec, $resulttype, $headerFontStyle, $centerParagraphStyle)
{
    $sel = mysqli_query($conn, "select fac_new.faculty,dept_new.department,field_new.field_title from fieldofinterest5 inner join field_new on fieldofinterest5.field=field_new.id inner join dept_new on dept_new.id=fieldofinterest5.dept inner join fac_new on fac_new.id=fieldofinterest5.fac where fieldofinterest5.dept='$dept' and fieldofinterest5.field='$field'") or die(mysqli_error($conn));
    $row = mysqli_fetch_array($sel);

    $sel  = mysqli_query($conn, "select naration from rendition where dept='$dept' and specialization='$field' and degree='$degree'") or die(mysqli_error($conn));
    $row2 = mysqli_fetch_array($sel);
    if ($resulttype == 1) {
        $rType = "Supplementary ";
    } else {
        $rType = "";
    }

    $naration0 = $row2['naration'] ?? 'No Naration Set';
    $naration  = $rType.' '. $naration0;
    $header1  = '<h2 align=center>University of Ibadan</h2>';
    $header2  = $row['faculty'];
    $header3  = $row['department'];
    $header4  = $row['field_title'];

    // Format faculty header
    $facultyHeader = strtoupper($header2);
    if (! preg_match('/^(INSTITUTE|CENTRE|CENTER)/i', $header2)) {
        $facultyHeader = strtoupper("FACULTY OF " . $header2);
    }

    // Format department header
    $departmentHeader = strtoupper($header3);
    if (! preg_match('/^(INSTITUTE|CENTRE|CENTER)/i', $header3)) {
        $departmentHeader = strtoupper("DEPARTMENT OF " . $header3);
    }
    $section->addText('UNIVERSITY OF IBADAN', $headerFontStyle, $centerParagraphStyle);
    $section->addText(strtoupper($facultyHeader), $headerFontStyle, $centerParagraphStyle);
    $section->addText(strtoupper($departmentHeader), $headerFontStyle, $centerParagraphStyle);
    $section->addText(strtoupper($naration . ' DEGREE EXAMINATION RESULTS - ' . $sec . ' SESSION'), $headerFontStyle, $centerParagraphStyle);
    $section->addText(strtoupper('AREA OF SPECIALIZATION: ' . $header4), $headerFontStyle, $centerParagraphStyle);
    $section->addText('LIST OF GRADUATING STUDENTS', $headerFontStyle, $centerParagraphStyle);
    $section->addTextBreak();
}

function addGraduatingListTable($section, $conn, $field, $effectivedate, $dept, $degree, $resulttype, $tableStyleName, $tableHeaderFontStyle, $tableFontStyle, $signatureFontStyle, $centerParagraphStyle)
{
    // Get HOD information
    $hod_query    = "SELECT * FROM hod_cgpa WHERE dept_new = $dept AND status = 0";
    $hod_result   = $conn->query($hod_query);
    $hod_row      = $hod_result->fetch_assoc();
    $hod_desig_id = $hod_row['designation'];
    $hod_title_id = $hod_row['title'];

    $hod_desig_query  = "SELECT title FROM designation WHERE id = '$hod_desig_id'";
    $hod_desig_result = $conn->query($hod_desig_query);
    $hod_desig_row    = $hod_desig_result->fetch_assoc();
    $hod_desig        = $hod_desig_row['title'];

    $hod_title_query  = "SELECT title FROM title WHERE id = '$hod_title_id'";
    $hod_title_result = $conn->query($hod_title_query);
    $hod_title_row    = $hod_title_result->fetch_assoc();

    if ($hod_desig === 'Professor and Head') {
        $hod_name = $hod_row['initial'] . ' ' . $hod_row['lname'] . ' ' . $hod_row['fname'];
    } else {
        $hod_name = $hod_title_row['title'] . ' ' . $hod_row['initial'] . ' ' . $hod_row['lname'] . ' ' . $hod_row['fname'];
    }

    // Get student data
    $sql = "SELECT DISTINCT t.matric,f.field_title ,t.effectivedate, n.Surname,n.Other_names
            FROM testscore t
            LEFT JOIN new n ON t.user_id = n.id
            LEFT JOIN field_new f ON t.field = f.id
            LEFT JOIN remark r ON t.matric = r.matric
            LEFT JOIN new ON new.id = t.user_id
            LEFT JOIN reginvoice ON reginvoice.appno = new.numeration
            WHERE t.degree='$degree' AND t.field='$field' AND t.effectivedate='$effectivedate' AND t.dept= '$dept' AND t.resulttype= '$resulttype'
            AND r.remark<>'NG' AND reginvoice.amount_paid=reginvoice.amount_charge AND reginvoice.amount_paid>0
            AND reginvoice.sessioned = t.session_of_grad
            ORDER BY n.Surname";

    $result = $conn->query($sql);

    // Create table
    $table = $section->addTable($tableStyleName);

    // Add header row
    $table->addRow(500);
    $table->addCell(800)->addText('S/N', $tableHeaderFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    $table->addCell(2000)->addText('Matric No.', $tableHeaderFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    $table->addCell(4000)->addText('Candidate Fullname (Surname First in CAP)', $tableHeaderFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    $table->addCell(3000)->addText('Area of Specialisation', $tableHeaderFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    $table->addCell(2000)->addText('Effective Date of Award', $tableHeaderFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

    // Add data rows
    $i = 1;
    while ($row = mysqli_fetch_array($result)) {
        $dateString    = $row['effectivedate'];
        $date          = new DateTime($dateString);
        $formattedDate = $date->format('d F, Y');

        $table->addRow();
        $table->addCell(800)->addText($i++, $tableFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(2000)->addText($row['matric'], $tableFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(4000)->addText(strtoupper($row['Surname']) . ', ' . ucwords(strtolower($row['Other_names'])), $tableFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(3000)->addText($row['field_title'], $tableFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(2000)->addText($formattedDate, $tableFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    }

    // Add signature section
    $section->addTextBreak(2);

    // Create signature table
    $signatureTable = $section->addTable();
    $signatureTable->addRow();

    // HOD signature cell
    $hodCell = $signatureTable->addCell(5000);
    $hodCell->addText('_______________________________', ['color' => 'CD853F', 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    $hodCell->addTextBreak();
    $hodCell->addText($hod_name, $signatureFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    $hodCell->addText($hod_desig, $signatureFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

    // Spacer cell
    $signatureTable->addCell(2000);

    // Date signature cell
    $dateCell = $signatureTable->addCell(3000);
    $dateCell->addText('_______________________________', ['color' => 'CD853F', 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
    $dateCell->addTextBreak();
    $dateCell->addText('Date', $signatureFontStyle, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
}
