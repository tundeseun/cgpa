<?php session_start();

include('function/script.php');

if(isset($_POST["department"])){
  $dept = $_POST["department"];
} else{

  $dept=$_SESSION["dept_new"];
}

if(isset($_SESSION["name"])){

  $name=$_SESSION["name"];
}

if((!(isset($_POST['sec']))) && (! $_SESSION["dept_new"])){
  header("Location: examView");

} else if((!(isset($_POST['sec']))) && ( $_SESSION["dept_new"])){
  header("Location: setupreport");

}
$sec=$_POST['sec'];
$field=$_POST['field'];
$degree=$_POST['degree'];
$effectivedate=$_POST['effectivedate'];
$external=$_POST['external'];
$resulttype=$_POST['resulttype'];




?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
	font-family: "Arial Black";
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
.foot .container .row .line{
 
  font-size: 2rem;
  font-weight: bolder;
  color: #CD853F;
  margin: 0 !important;
  padding: 0 !important;
}
.foot .container .row, .head{
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
<body>
<div class="image">

<div class="transparentbox">

    
<?php

 resulBoardtheader($conn,$dept,$field,$degree,$sec,$effectivedate,$external,$resulttype);
 processboardresult($conn,$field,$effectivedate,$external,$dept,$resulttype,$sec);


?>


    </div></div>
</body>
</html>
