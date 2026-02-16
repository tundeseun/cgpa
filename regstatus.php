<?php session_start();

if (isset($_GET["department"])) {
    $dept = $_GET["department"];
} else {

    $dept = $_SESSION["dept_new"];
}
include('function/script.php');
$sec = $_GET['sec'];
$field = $_GET['field'];
$degree = $_GET['degree'];
$effectivedate = $_GET['effectivedate'];
$resulttype = $_GET['resulttype'];



// (A) LOAD MPDF
require "vendor/autoload.php";
$mpdf = new \Mpdf\Mpdf();

$mpdf = new \Mpdf\Mpdf();



// (C) THE HTML
$html = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Document</title>
    <style>
        @page {size: 29.7cm 42cm; margin: 5mm}
        div.page {page-break-after:always}
    </style>
    <style>
       h2 {
        margin: 0;  /* Remove default margin */
        line-height: 1.5;  /* Set line height to 1 to reduce spacing */
    }
    h3 {
        margin: 0;  /* Remove default margin */
        line-height: 1.5;  /* Set line height to 1 to reduce spacing */
    }
</style>
<style>
	
  .custom-table {
      text-align: center;
      font-family:Arial Black,;
	  font-size:13px;
      border-collapse: collapse;
      border-spacing: 0;
	  
    }
	.custom-table th
    {
        border: 1px solid #000; 
        height: 50px;
        padding: 8px; /* Adjust padding as needed */
    }
.custom-table td {
    border: 1px solid #000; /* Add border for both th and td elements */
   
}
		.codea{
	   -webkit-transform: rotate(-90deg);
	         -ms-transform: rotate(-90deg);
      -o-transform: rotate(-90deg);
      transform: rotate(-90deg);

	}
	 th {
  
    
      width:55px;
      font-weight:normal;
		
    }
	tr:nth-child(even){background-color: #f2f2f2;}
	tr:hover {background-color: #ddd;}
	
	</style>
	<style>

div.image

  {

  
  background:url(images/logged2.png);
	  
  background-repeat:no-repeat;

  background-position:center;
	  

  
  }

div.transparentbox

  {



  background-color:#ffffff;

  

  opacity:0.8;


  }

div.transparentbox p

  {

  

  font-weight:bold;

  color:#CD853F;

  }
.style2 {
	font-size: 13px;
	font-weight: bold;
}
    body,td,th {
	color: #000000;
	font-family: 'Arial Black';
	font-weight: normal;
	font-size: 12px;

		
}
.foot .container .row .sign{
  width: 10rem !important;
  height: 5rem !important;
  border-bottom: 2px solid #CD853F;
}
.foot .container .row .non{
  visibility: hidden;
}
.foot .line{
 
  font-size: 2rem;
  font-weight: bolder;
  color: #CD853F;
  margin: 0 !important;
  padding: 0 !important;
}
.foot .container .row{
  display: flex;
  
  justify-content: space-around;
  align-items: center;
  text-align: center;
  
}
		
		@media print{@page {size: landscape}}
		@media print {
* {
    -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
    color-adjust: exact !important;  /*Firefox*/
  }
}
    </style>
</head>
<body >
<div >
<div >

";

$html .= RegStatusHeaderPDF($conn, $dept, $field, $degree, $sec,$resulttype);

$html .= regStausList($conn, $field, $effectivedate, $dept, $degree, $resulttype);







$html .= "</div></div>
</body>
</html>
";


// // $html = file_get_contents("PAGE.HTML"); // load from html file

// (D) WRITE HTML TO PDF

$mpdf->WriteHTML($html);

// (E) OUTPUT
$mpdf->Output(); // directly show in browser
$mpdf->Output("registrationStatus.pdf", "D"); // force download
// $mpdf->Output("demoA.pdf"); // save to file on server

// 
