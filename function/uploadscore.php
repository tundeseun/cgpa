<?php
include('connect.php');
use PhpOffice\PhpSpreadsheet\IOFactory;

// Function to upload and process an Excel file to MySQL
function uploadExcelToMySQL($excelFile, $conn)
{
    if (isset($excelFile) && $excelFile['error'] === UPLOAD_ERR_OK) {
        $tmpFileName = $excelFile['tmp_name'];

        require 'vendor/autoload.php'; // Require the autoload.php from PhpSpreadsheet

        $spreadsheet = IOFactory::load($tmpFileName); // Load the Excel file using PhpSpreadsheet

        foreach ($spreadsheet->getActiveSheet()->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Prepare and execute your SQL INSERT statement here using PDO
            // Ensure your table structure matches the Excel data (number of columns, data types, etc.)

            // Example: Inserting into a 'example_table' with assumed structure: id, name, age
            $sql = "INSERT INTO testupload (name, age) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            
            // Assuming the first column is name and the second column is age in the Excel sheet
            $stmt->execute([$rowData[0], $rowData[1]]);
        }

        // Optionally, you can output a success message or perform other actions after the insertion
        echo "Excel data uploaded successfully to MySQL!";
    } else {
        echo "File upload failed!";
    }
}
?>
