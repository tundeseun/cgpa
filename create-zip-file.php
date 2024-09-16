<?php
// Path where the ZIP file will be created
$zipFile = "ProcessedBoardResult.zip";

// Create the ZIP file
$zipArchive = new ZipArchive();
if ($zipArchive->open($zipFile, ZipArchive::CREATE) !== TRUE) {
    exit("Unable to open file.");
}

// Folder to be zipped
// $projectDir = dirname(__DIR__);
// $ProcessedBoardResultDir = $projectDir . '/cgpa_new/ProcessedBoardResult';
$folder = 'ProcessedBoardResult/';
createZip($zipArchive, $folder);
$zipArchive->close();

// Function to recursively add files and directories to the ZIP archive
function createZip($zipArchive, $folder)
{
    if (is_dir($folder)) {
        if ($f = opendir($folder)) {
            while (($file = readdir($f)) !== false) {
                if (is_file($folder . $file)) {
                    if ($file != '' && $file != '.' && $file != '..') {
                        $zipArchive->addFile($folder . $file);
                    }
                } else {
                    if (is_dir($folder . $file)) {
                        if ($file != '' && $file != '.' && $file != '..') {
                            $zipArchive->addEmptyDir($folder . $file);
                            createZip($zipArchive, $folder . $file . '/');
                        }
                    }
                }
            }
            closedir($f);
        } else {
            exit("Unable to open directory " . $folder);
        }
    } else {
        exit($folder . " is not a directory.");
    }
}

// Ensure all output buffering is cleared before sending headers
while (ob_get_level()) {
    ob_end_clean();
}

// Download the created ZIP file
$filename = $zipFile;
if (file_exists($filename)) {
    $absoluteFilePath = __DIR__ . '/' . $filename;
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($absoluteFilePath));
    readfile($absoluteFilePath);
    exit();
}
?>
