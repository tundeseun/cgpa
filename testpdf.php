<?php

require "vendor/autoload.php";
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML(file_get_contents('testreceipt.php'));
$mpdf->Output();


?>