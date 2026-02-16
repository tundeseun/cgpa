<?php
include('function/script.php');

// Call the function to fetch uploaded records
$uploadedData = uploadExcelToMySQL($excelFile, $conn, $sec, $coz);

// Return the uploaded data as JSON
echo json_encode($uploadedData);
?>
