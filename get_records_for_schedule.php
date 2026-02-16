<?php
require 'vendor/autoload.php';
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';

$dbName = 'pgcollege';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT f.faculty, d.department, g.degree 
        FROM fieldofinterest5 fi 
        JOIN fac_new f ON fi.fac = f.id 
        JOIN dept_new d ON fi.dept = d.id 
        JOIN degree_new g ON fi.degree = g.id
        ORDER BY f.faculty, d.department, g.degree";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[$row['faculty']][$row['department']][] = $row['degree'];
    }
} else {
    echo "0 results";
}

// Make degrees distinct
foreach ($data as $faculty => &$departments) {
    foreach ($departments as $department => &$degrees) {
        $degrees = array_unique($degrees);
    }
}
// echo '<pre>';
// print_r($data);
// echo '</pre>';

$conn->close();

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

$phpWord = new PhpWord();

$fontStyleName3 = 'rStyle3';
$phpWord->addFontStyle($fontStyleName3, ['name' => 'Times New Roman','bold' => true, 'size' => 12, 'allCaps' => true]);

$paragraphStyleName4 = 'pStyle4';
$phpWord->addParagraphStyle($paragraphStyleName4, ['spaceAfter' => 100]);

$fontStyleName5 = 'rStyle5';
$phpWord->addFontStyle($fontStyleName5, ['name' => 'Times New Roman','bold' => false, 'size' => 11, 'allCaps' => false]);

$fontStyleName6 = 'rStyle6';
$phpWord->addFontStyle($fontStyleName6, ['name' => 'Times New Roman','bold' => false, 'size' => 11, 'allCaps' => true]);



// Adding a new section to the document
$section = $phpWord->addSection();

// Add data to the Word document
foreach ($data as $fac_name => $departments) {
    $section->addText('FACULTY OF '.htmlspecialchars($fac_name),$fontStyleName3,$paragraphStyleName4);
    foreach ($departments as $dept_name => $degrees) {
        $section->addText('DEPARTMENT OF '.htmlspecialchars($dept_name), $fontStyleName6);
        foreach ($degrees as $degree_name) {
            $section->addText(htmlspecialchars($degree_name),$fontStyleName5);
        }
        $section->addText(' ');
    }

    $section->addText(' ');

}

// Save the Word document
$filename = 'faculty_departments_degrees.docx';
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');

try {
    $objWriter->save($filename);
    echo "Document created successfully. Download it from <a href='$filename'>here</a>.";
} catch (Exception $e) {
    echo 'Error saving document: ', $e->getMessage();
}
?>
