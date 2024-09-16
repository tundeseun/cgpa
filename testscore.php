<!-- HTML form to upload Excel file -->
<form method="post" enctype="multipart/form-data">
    <input type="file" name="excelFile">
    <input type="submit" value="Upload and Process Excel">
</form>
<?php
include('function/script.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    uploadExcelToMySQL($_FILES['excelFile'],$conn);
}

?>