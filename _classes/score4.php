<?php session_start();
 $username=$_SESSION['name']; 
//	require_once('auth.php');
?>
<?php
//include('db.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CGPA Calculator</title>
<script type="text/javascript" src="selectstaff.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/
ajax/libs/jquery/1.5/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
$(".edit_tr").click(function()
{
var ID=$(this).attr('id');
$("#first_"+ID).show();
$("#last_"+ID).hide();
$("#last_input_"+ID).show();
}).change(function()
{
var ID=$(this).attr('id');
var first=$("#first_input_"+ID).val();
var last=$("#last_input_"+ID).val();
var dataString = 'id='+ ID +'&price='+first+'&qty_sold='+last;
$("#first_"+ID).html('<img src="load.gif" />');


if(first.length && last.length>0)
{
$.ajax({
type: "POST",
url: "table_edit_ajax.php",
data: dataString,
cache: false,
success: function(html)
{

$("#first_"+ID).html(first);
$("#last_"+ID).html(last);
}
});
}
else
{
alert('Enter something.');
}

});

$(".editbox").mouseup(function() 
{
return false
});

$(document).mouseup(function()
{
$(".editbox").hide();
$(".text").show();
});

});
</script>
<style>
body
{
font-family:Arial, Helvetica, sans-serif;
font-size:14px;
padding:10px;
}
.editbox
{
display:none
}
td
{
padding:7px;
border-left:1px solid #fff;
border-bottom:1px solid #fff;
}
table{
border-right:1px solid #fff;
}
.editbox
{
font-size:14px;
width:29px;
background-color:#ffffcc;

border:solid 1px #000;
padding:0 4px;
}
.edit_tr:hover
{
background:url(edit.png) right no-repeat #80C8E5;
cursor:pointer;
}
.edit_tr
{
background: none repeat scroll 0 0 #D5EAF0;
}
th
{
font-weight:bold;
text-align:left;
padding:7px;
border:1px solid #fff;
border-right-width: 0px;
}
.head
{
background: none repeat scroll 0 0 #91C5D4;
color:#00000;

}

</style>
<link rel="stylesheet" href="reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="tab.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="tcal.css" />
<link href="tabs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="tcal.js"></script> 
<script type="text/javascript">

var popupWindow=null;

function child_open()
{ 

popupWindow =window.open('printform.php',"_blank","directories=no, status=no, menubar=no, scrollbars=yes, resizable=no,width=950, height=400,top=200,left=200");

}
</script>
<script type="text/javascript">$(function() {
  $("#from_sales_date").date_input();
   $("#to_sales_date").date_input();
    $("#from_purchase_date").date_input();
   $("#to_purchase_date").date_input();
   $("#from_sales_purchase_date").date_input();
   $("#to_sales_purchase_date").date_input();
    $("#from_stock_sales_date").date_input();
   $("#to_stock_sales_date").date_input();
  
});

function sales_report_fn() 
{ 
 window.open("sales_report.php?from_sales_date="+$('#from_sales_date').val()+"&to_sales_date="+$('#to_sales_date').val(),"myNewWinsr","width=620,height=800,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no,scrollbars=yes"); 
   
}
function purchase_report_fn() 
{ 
 window.open("purchase_report.php?from_purchase_date="+$('#from_purchase_date').val()+"&to_purchase_date="+$('#to_purchase_date').val(),"myNewWinsr","width=620,height=800,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no,scrollbars=yes"); 
   
} 

function sales_purchase_report_fn() 
{ 
 window.open("all_report.php?from_sales_purchase_date="+$('#from_sales_purchase_date').val()+"&to_sales_purchase_date="+$('#to_sales_purchase_date').val(),"myNewWinsr","width=620,height=800,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no,scrollbars=yes"); 
   
} 

function stock_sales_report_fn() 
{ 
 window.open("sales_stock_report.php?from_stock_sales_date="+$('#from_stock_sales_date').val()+"&to_stock_sales_date="+$('#to_stock_sales_date').val(),"myNewWinsr","width=620,height=800,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no,scrollbars=yes"); 
   
} 

</script>
        <script type="text/javascript" src="jquery.date_input.js"></script>
<link rel="stylesheet" href="date_input.css" type="text/css">
<script type="text/javascript">$(function() {
  $("#datefield").date_input();
   $("#due").date_input();
});</script>


		<script src="js/jquery.validationEngine-en.js" type="text/javascript"></script>
		<script src="js/jquery.validationEngine.js" type="text/javascript"></script>
		 <script src="js/jquery.hotkeys-0.7.9.js"></script>
		<!-- AJAX SUCCESS TEST FONCTION	
			<script>function callSuccessFunction(){alert("success executed")}
					function callFailFunction(){alert("fail executed")}
			</script>
		-->
		
		<script>	
		
		
		
		
		
		$(document).ready(function() {
			// SUCCESS AJAX CALL, replace "success: false," by:     success : function() { callSuccessFunction() }, 
			 $("#name").focus();
			$("#form1").validationEngine(),
			
			jQuery(document).bind('keydown', 'Ctrl+s',function() {
		  $('#form1').submit();
		  return false;
			});
			
			jQuery(document).bind('keydown', 'Ctrl+r',function() {
		  $('#form1').reset();
		  return false;
			});
			
			jQuery(document).bind('keydown', 'Ctrl+0',function() {
			window.location = "admin.php";
		  return false;
			});
			jQuery(document).bind('keydown', 'Ctrl+1',function() {
			window.location = "add_purchase.php";
			  return false;
			});
			jQuery(document).bind('keydown', 'Ctrl+2',function() {
			window.location = "add_stock_sales.php";
			  return false;
			});
			jQuery(document).bind('keydown', 'Ctrl+3',function() {
			window.location = "add_stock_details.php";
			  return false;
			});
			jQuery(document).bind('keydown', 'Ctrl+4',function() {
			window.location = "add_category.php";
			  return false;
			});
			jQuery(document).bind('keydown', 'Ctrl+5',function() {
			window.location = "add_supplier_details.php";
			  return false;
			});
			jQuery(document).bind('keydown', 'Ctrl+6',function() {
			window.location = "add_customer_details.php";
			  return false;
			});
			jQuery(document).bind('keydown', 'Ctrl+7',function() {
			window.location = "view_stock_entries.php";
			  return false;
			});
			jQuery(document).bind('keydown', 'Ctrl+8',function() {
			window.location = "view_stock_sales.php";
			  return false;
			});
			jQuery(document).bind('keydown', 'Ctrl+9',function() {
			window.location = "view_stock_details.php";
			  return false;
			});
			//$.validationEngine.loadValidation("#date")
			//alert($("#formID").validationEngine({returnIsValid:true}))
			//$.validationEngine.buildPrompt("#date","This is an example","error")	 		 // Exterior prompt build example								 // input prompt close example
			//$.validationEngine.closePrompt(".formError",true) 							// CLOSE ALL OPEN PROMPTS
		});
	</script>
<style type="text/css">
<!--
.style2 {	font-family: Andalus;
	font-weight: bold;
	font-size: 24px;
}
.style62 {font-family: Arial}
.style64 {font-size: 10px}
.style66 {font-size: 10}
.style68 {font-size: 9px}
.style70 {font-size: 9}
.style71 {	font-family: Andalus;
	font-weight: bold;
	font-size: 18px;
}
.edit_tr1 {background: none repeat scroll 0 0 #D5EAF0;
}
.style72 {
	font-family: "Arial Black";
	font-weight: bold;
	font-size: 30px;
}
.style10 {font-family: "Book Antiqua", "Monotype Corsiva"; font-size: 12px; }
.style11 {font-size: 12}
-->
</style>
</head>

<body bgcolor="#dedede">
 
<table width="1262" align="center">
  <tr>
    <td width="224"><div align="right"><img src="images/logo_trans.jpg" width="75" height="77" /></div></td>
    <td width="815"><div align="center" class="style72">The Postgraduate School, University of Ibadan <br />
    CGPA Application </div></td>
    <td width="207"><div align="left"><img src="images/pgcrest2.jpg" width="127" height="93" /></div></td>
  </tr>
</table>
<p align="left"><b>Welcome <?php echo strtoupper($username);   ?></b></p>
<ol id="toc">
    <li><a href="#inventory"><span>Menu</span></a></li>
	<li><a href="#editprice"><span>Upload Student Details</span></a></li>
    <li><a href="list_coz.php"><span>Select Semester Courses</span></a></li>
    <li><a href="#alert"><span>Upload Scores</span></a></li>
	<li><a href="#addproitem"><span>Report</span></a></li>
	<li><a href="index.php"><span>Logout</span></a></li>
</ol>

<div class="content" id="inventory"><br />
  <table width="1175" height="263" align="center">
    <tr>
      <td colspan="2" valign="top"><table width="200" align="center" bgcolor="#38385D">
          <tr>
            <td><table width="544" align="center" bgcolor="#CCCCCC">
                <tr>
                  <td width="133"><div align="right"><span class="style10">
                      <?php
					  require_once("_classes/db_connect.inc");
					  $dept5=$_SESSION['dept'];
					  $sell5="select * from department_table where id='$dept5'";
					  $query5=mysqlii_query($sell5) or die(mysqlii_error());
					  while($row=mysqlii_fetch_array($query5))
					  {
					  $dept=$row['title'];
					  }
					  ?>
                    Department:</span></div></td>
                  <td width="399" bgcolor="#FFFFFF" class="style10"><?php   echo $dept;   ?></td>
                </tr>
                <tr>
                  <td><div align="right"><span class="style10"> Semester:</span></div></td>
                  <td bgcolor="#FFFFFF" class="style10"><span class="style11"></span></span>
                      <label>
                      <select name="semester" class="combo1" id="semester">
                        <option selected="selected">.</option>
                        <option value="First">First</option>
                        <option value="Second">Second</option>
                        <option value="Third">Third</option>
                      </select>
                    </label></td>
                </tr>
                <tr>
                  <td colspan="2"><table width="580" height="210" align="center" bgcolor="#F0F0F0">
                      <tr>
                        <td width="328" height="204" valign="top" bgcolor="#FFFFFF"><label>
                            <p>
                              <?php  
									  require_once("_classes/db_connect.inc");
					  $num=1;
					  $num2=2;
					  $dept5=$_SESSION['dept'];
					  //echo $dept5;
					  //$alert=alert('This is a Core course you must register');
					  $select="select * from course_online where dept='$dept5'";
							$query=mysqlii_query($select) or die(mysqlii_error());
							echo"<table align=center><tr><td bgcolor=#ffffff>";
							//echo"<table align=center bgcolor=#ffffff><tr><th align=left><div class=style60>ELECTIVE COURSES</div></th></tr></table> ";
							echo"<table width=700 align=center bgcolor=#ffffff> <tr bgcolor=#000000><th style=border-width: 1px 0px 0px; border-style: solid solid none; border-color: #330033 -moz-use-text-color;><font color=#ffffff size=2px>COURSE CODE</font></th><th style=border-width: 1px 0px 0px; border-style: solid solid none; border-color: #000000; align=center><font color=#ffffff size=2px>COURSE TITLE</font></th><th style=border-width: 1px 0px 0px; border-style: solid solid none; border-color: #000000; align=center><font color=#ffffff size=2px>UNIT</font></th><th style=border-width: 1px 0px 0px; border-style: solid solid none; border-color: #000000; align=center><font color=#ffffff size=2px>STATUS</font></th><th style=border-width: 1px 0px 0px; border-style: solid solid none; border-color: #000000; align=center><font color=#ffffff size=2px>SEMESTER</font></th></tr>";
							while($row = mysqlii_fetch_array($query))
  {
				$code=$row['code'];
				$title=$row['title'];
				$unit=$row['unit'];	
				$type_c=$row['type_c'];	
				$id=$row['id'];	
				$semester7=$row['semester'];	  
					
					echo"<tr>";
echo"<td align=left><input name='subject[]'  type=checkbox value=$id /> $code</td><td align=left>$title</td><td align=center>$unit</td><td align=center>$type_c</td><td align=center>$semester7</td><td width=60 align=center><a href=edit_coz.php?id=$id><img src=images/edit-icon.png width=14 height=14 /></a><td><a href=delete2.php?id=$id><img src=images/delete.png name=Submit width=14 height=14 /></a></td>";

}
 echo"</tr></table></table>" ;
 					   
					      ?>
                            </p>
                          <p>&nbsp;</p>
                          <label>
                            <p>&nbsp;</p>
                          <div align="center"></div>
                          </label>
                            <table width="582" align="center">
                              <tr>
                                <td width="138"><div align="left"><a href="menu.php"><img src="images/back.png" width="90" height="36" /></a></div></td>
                                <td width="330"><div align="center">
                                    <input type="submit" name="Submit" id="Submit" value="Approved" />
                                </div></td>
                                <td width="98"><div align="right"><a href="score4.php?id=<?php $semester=$_GET['semester']; echo $semester;   ?>"></a></div></td>
                              </tr>
                            </table>
                          <div align="left"></div>
                          </label>
                            <label></label></td>
                      </tr>
                  </table></td>
                </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
  </table>
</div>
<script src="activatables.js" type="text/javascript"></script>
<script type="text/javascript">
activatables('page', ['inventory', 'alert', 'sales', 'addproitem', 'addpro', 'editprice']);
</script>
</body>
</html>
