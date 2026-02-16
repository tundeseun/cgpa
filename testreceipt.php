<?php
 use Phppot\Order;
 //require_once __DIR__ . '/../Model/Order.php';
// function getHTMLPurchaseDataToPDF($result, $orderItemResult, $orderedDate, $due_date)
// {
// ob_start();
$result="Global Test"
?>



<html>
<head>University of Ibadan Receipt - <?php  echo $result; ?>
<link href="assets/css/style.css" rel="stylesheet" />
<style>
    /* Watermark style */
    .watermark1 {
        position: fixed;
        top: 12%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        /* Rotate the watermark */
        font-size: 4em;
        /* Increased font size */
        font-weight: bold;
        /* Make the text bold */
        color: rgba(0, 0, 255, 0.1);
        /* Adjust opacity as needed */
        z-index: 1000;
        /* Ensure it's above other content */
    }


    .watermark2 {
        position: fixed;
        bottom: 14%;
        right: 46%;
        transform: translate(-20%, -20%) rotate(-90deg);
        /* Rotate the watermark */
        font-size: 2em;
        /* Increased font size */
        /*font-weight: bold;*/
        /* Make the text bold */
        color: rgba(255, 0, 255, 0.1);
        /* Adjust opacity as needed */
        z-index: 100;
        /* Ensure it's above other content */
    }




    .watermark3 {
        position: fixed;
        top: 12%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        /* Rotate the watermark */
        font-size: 4em;
        /* Increased font size */
        font-weight: bold;
        /* Make the text bold */
        color: rgba(0, 0, 255, 0.1);
        /* Adjust opacity as needed */
        z-index: 1000;
        /* Ensure it's above other content */
    }


    .watermark4 {
        position: fixed;
        bottom: 14%;
        right: 46%;
        transform: translate(-20%, -20%) rotate(-90deg);
        /* Rotate the watermark */
        font-size: 2em;
        /* Increased font size */
        /*font-weight: bold;*/
        /* Make the text bold */
        color: rgba(255, 0, 255, 0.1);
        /* Adjust opacity as needed */
        z-index: 100;
        /* Ensure it's above other content */
    }
    </style>

    <style>
    .signature-container {
        text-align: right;
        /* Align content to the right */
    }

    .signature-line {
        width: 20%;
        /* Adjust width as needed */
        border-bottom: 2px solid black;
        /* Change border style as needed */
        margin-bottom: 5px;
        /* Adjust margin as needed */
        float: right;
        /* Align the line to the right */
        position: relative;
        /* Position the line relative to its container */
    }

    .signature-text {
        position: absolute;
        /* Position the text relative to the line */
        bottom: -20px;
        /* Adjust the distance between the line and the text */
        left: 0;
        /* Center the text under the line */
        width: 180%;
        /* Ensure the text is centered */
        text-align: center;
        /* Center the text */
    }
    .dark-thick-line {
    height: 4px; /* Adjust the thickness as needed */
    background-color: #333; /* Dark color for the line */
    margin: 20px 0; /* Adjust margin as needed */
}

    </style>
      <style>
        .watermark {
            position: relative;
        }
        .watermark::after {
            content: "Watermark Text";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-90deg);
            font-size: 60px; /* Adjust font size as needed */
            color: rgba(255, 0, 255, 0.1); /* Adjust text color and opacity */
            z-index: 100;
        }

        .bgtext { 
            position: relative; 
        } 
  
        .bgtext:after { 
            margin: 3rem; 
            content: "Background text"; 
            position: absolute; 
            font-size: 60px;
            transform: rotate(300deg); 
            -webkit-transform: rotate(300deg); 
            color:red; 
            top: 0; 
            left: 0; 
            z-index: -1; 
        } 
    </style>

</head>
<body>
<!-- <?php
// include_once("../_classes/connect.php"); -->
$teller="3109-6396-9939"; //$_GET['textbox'];
// $sel=mysqli_query($conn,"select * from transreceipt where tellerno='$teller'") or die(mysqli_error($conn));
// ($row=mysqli_fetch_array($sel));
$transdate='transdate';
$receiptno='receiptno';
$tellerno ='tellerno';
$amount='amount';
$payer ='payer';
$description='description';
$bankname ='bankname';
$accountcode='accountcode';
?>

 <!-- <div class="watermark1">UI Receipts</div>
 <div class="watermark2"><?php //echo $receiptno;   ?></div> -->

    <!-- <div style="text-align: left;border-top:1px solid #000;">
        <div style="font-size: 24px;color: #666;">INVOICE</div> -->
         <div style="text-align:center;">
         <div style="display: inline-block; vertical-align: middle;">
        <img src="assets/img/logo.png" width="50" height="50" />
    </div>
    <div style="display: inline-block; vertical-align: middle;">
        <b>UNIVERSITY OF IBADAN</b>
    </div>
    </div>
    <div style="border-bottom:1px solid #000;position:relative">
<table style="line-height: 1;">
    <tr><td><b>Transref#:</b> #<?php echo $receiptno; ?>
        </td>
        <td style="text-align:right;"><b>Receiver:</b></td>
    </tr>
    <tr>
        <td><b>Date:</b> <?php echo $transdate; ?></td>
        <td style="text-align:right;"><?php echo $payer; ?></td>
    </tr>
    <tr>
        <td><b>Teller NO:</b><?php echo $tellerno; ?>
        </td>
        <td style="text-align:right;"><?php //echo $result; ?></td>
    </tr>
    <tr>
        <td><b>Account Code:</b><?php echo $accountcode; ?>
        </td>
        <td style="text-align:right;"><?php //echo $result; ?></td>
    </tr>
    <tr>
        <td><b>Bank:</b><?php echo $bankname; ?>
        </td>
        <td style="text-align:right;"><?php //echo $result; ?></td>
    </tr>
<tr>
<td></td>
<td style="text-align:right;"><?php //echo $result; ?></td>
</tr>
</table>

<div></div>


    <div class='bgtext'>
  
        <table style="line-height: 2;">
         <!-- Add watermark inside the table
         <tr>
         
                <td colspan="2" style="text-align:center; opacity: 0.1; font-style: italic; color: rgba(255, 0, 255, 0.1);transform: translate(-50%, -50%) rotate(-90deg); font-size: 60px;">Watermark Text</td>
            </tr> -->
            <tr style="font-weight: bold;border:1px solid #cccccc;background-color:#f2f2f2;">
                <td style="border:1px solid #cccccc;">Item Description</td>
                <!-- <td style = "text-align:right;border:1px solid #cccccc;width:85px">Price ($)</td>
                <td style = "text-align:right;border:1px solid #cccccc;width:75px;">Quantity</td> -->
                <td style = "text-align:right;border:1px solid #cccccc;">Amount Paid <strike>(N)</strike></td>
            </tr>
<?php
$total = 0;
$productResult="Bag of Rice";
$orderItemResultp=500;
$orderItemResultq=6;
$price=85000;
$total=540000;
// $productModel = new Order();
// foreach ($orderItemResult as $k => $v) {
//     $price = $orderItemResult[$k]["item_price"] * $orderItemResult[$k]["quantity"];
//     $total += $price;
//     $productResult = $productModel->getProduct($orderItemResult[$k]["product_id"]);
    ?>
    <tr> <td style="border:1px solid #cccccc;"><?php echo $description; ?></td>
         <td style = "text-align:right; border:1px solid #cccccc;"><?php echo number_format($amount, 2); ?></td>
               </tr>
<?php
//}
?>
<tr style = "font-weight: bold;">
    <td></td><td></td>
    <td style = "text-align:right;">Total (N)</td>
    <td style = "text-align:right;"><?php echo number_format($total, 2); ?></td>
</tr>
</table></div>
<!-- <p><u>Kindly make your payment to</u>:<br/>
Bank: American Bank of Commerce<br/>
A/C: 05346346543634563423<br/>
BIC: 23141434<br/>
</p>
<p><i>Note: Please send a remittance advice by email to vincy@phppot.com</i></p> -->
</body>
</html>

<?php
//return ob_get_clean();
//}
?>