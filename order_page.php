 <?php /*?><?php
//Script Error Reporting
error_reporting(E_ALL);
ini_set('display_error','1');
?>
<?php */?>
<?php
// help you have persistent data, if they aren't loggedin then session variable won't be created then we'll know they are not allowed to be here.
session_start(); 
if(!isset($_SESSION["customer_username"])) 
{
	header("location: customer_login.php"); // will not execute if loggedin.
	exit();
}

// Connect to the MYSQL database.
	$conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 
// Checks to see that the URL variable is set and that it exists in the database.

// Will be used to output the order elements.
$cartOutput = "";
if(isset($_SESSION["cart_array"]) && !empty($_SESSION["cart_array"]))
{
	
	$customer=$_SESSION["customer_username"];
	$cartTotal=$_SESSION["order_total"];
	
	// Get the customer id against the username of the customer.
	$sql=oci_parse($conn,"select c.customer_id,c.person_id from customer c,person p where p.username='$customer' AND p.person_id = c.person_id");
	//$productCount = mysql_num_rows($sql); // count the output amount.
	
	if(oci_execute($sql))
	{
		while($row = oci_fetch_row($sql))
		{
			$customer_id=$row[0];
			$person_id=$row[1];
		}	
	}
	else
	{
		echo "This user doesn't exist in our database.";	
		exit();
	}
	
	if(isset($_POST['PaymentType']) && !empty($_POST['PaymentType']))
	{
		$paymentType = $_POST["PaymentType"];	
		$_POST["PaymentType"] = "";
		$sql = oci_parse($conn,"insert into orders(order_id,order_date,payment_id,customer_id,total_price,person_id) values(ORDER_INCREMENT.nextval,sysdate,'$paymentType','$customer_id','$cartTotal','$person_id')");
		oci_execute($sql);
	}
	
	// Get the order id of the customer in order to store information in order2product table.
	$sql2=oci_parse($conn,"select order_id from orders where customer_id='$customer_id'");
	//$productCount = mysql_num_rows($sql2); // count the output amount.
	

	if(oci_execute($sql2))
	{
		while($row = oci_fetch_array($sql2))
		{
			$order_id=$row[0];	
		}	
	}
	else
	{
		echo "The customer id which you specified doesn't have an order id against it.";	
		exit();
	}
	
$_SESSION["order_id"]=$order_id;	
	foreach($_SESSION["cart_array"] as $listitem) 
	{
		$item_id=$listitem['item_id'];
		$quantity=$listitem['quantity'];
		$sql = oci_parse($conn,"insert into order2product(Order_id,Product_id,quantity) values('$order_id','$item_id','$quantity')");
		oci_execute($sql); 
	
	}

	//header ("location:order_page.php");
		//exit();


// To get the product list of the order
$sql3 = oci_parse($conn,"SELECT p.name from product p,orders o,order2product op,customer c where o.order_id = op.order_id AND o.order_id='$order_id' AND p.product_id = op.product_id AND o.customer_id = c.customer_id");
//$productCount = mysql_num_rows($sql3); // count the output amount.

	if(oci_execute($sql3))
	{
		$result_array = array();
	while($row = oci_fetch_assoc($sql3))
	{
    	$result_array[] = $row['NAME'];
	}
	}
	else
	{
		echo "The customer id which you specified doesn't have a product list and order against it.";	
		exit();
	}


// To get the quanity of products in the order.
$sql5 = oci_parse($conn,"SELECT distinct p.name,quantity from product p,orders o,order2product op,customer c where o.order_id='$order_id' AND o.order_id = op.order_id AND p.product_id = op.product_id AND o.customer_id = c.customer_id");
//$productCount = mysql_num_rows($sql5); // count the output amount.

	if(oci_execute($sql5))
	{
		$quantity_array = array();
	while($row = oci_fetch_assoc($sql5))
	{
    	$quantity_array[] = $row['QUANTITY'];
	}
	}
	else
	{
		echo "The customer id which you specified doesn't have products, their quantity and order against it.";	
		exit();
	}

// To get the payment type of the order
$sql4 = oci_parse($conn,"SELECT distinct payment_type from payment pp,product p,orders o,order2product op,customer c
where o.payment_id = pp.payment_id AND o.order_id='$order_id' AND o.order_id = op.order_id AND p.product_id = op.product_id AND o.customer_id = c.customer_id");
//$productCount = mysql_num_rows($sql4); // count the output amount.

	if(oci_execute($sql4))
	{
	while($row = oci_fetch_array($sql4))
	{
    	$payment_type = $row[0];
	}
	}
	else
	{
		echo "The customer id which you specified doesn't have a payment type and order against it.";	
		exit();
	}

	
	// Dynamic table row assembly.
	$cartOutput .= '<tr>';
	$cartOutput .= '<td>' .$order_id. '</td>';
	$cartOutput .= '<td>'."Pending.". '</td>';
	$cartOutput .= '<td>'.$payment_type. '</td>';
	$cartOutput .= '<td>';
	foreach($result_array as $listitem) 
	{ 
    	 $cartOutput .= $listitem.'<br/>';
	} 
	$cartOutput .= '</td>';
	$cartOutput .= '<td>';
	foreach($quantity_array as $itemquntity) 
	{ 
    $cartOutput .= $itemquntity. '<br/>';
	} 
	//$cartOutput .= '</td>';
	//$cartOutput .= '</tr>';
	$cartOutput .= '<td>'.'$'.$cartTotal. '</td>';

	//header("location:thank_you.php");
	//exit();

}
else
{
    echo "<script type='text/javascript'>alert('Your cart is empty, Please fill your cart first in order to make your order');window.location.href='cart.php'; </script>";
}
//mysql_close();

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Order Page</title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
<p>
  <?php  if(isset($_SESSION["customer_username"])) { include_once("template_header_logout_account.php"); } else { include_once("template_header_login_account.php"); } ?>
<div id="pageContent">
<div style="margin:24px; text-align:left;">

<br/>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
  <tr>
    <td width="24%" bgcolor="#DAF1FE"><strong>Order ID</strong></td>
    <td width="21%" bgcolor="#DAF1FE"><strong>Order Status</strong></td>
    <td width="16%" bgcolor="#DAF1FE"><strong>Payment Type</strong></td>
    <td width="16%" bgcolor="#DAF1FE"><strong>Product(s)</strong></td>
    <td width="13%" bgcolor="#DAF1FE"><strong>Quantity</strong></td>
    <td width="10%" bgcolor="#DAF1FE"><strong>Total</strong></td>
  </tr>
  <?php if($cartOutput != NULL) { echo $cartOutput; } ?>

 <!-- <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>-->
</table>
<?php /*echo "$cartTotal"; */ ?>
</div>
<br/>
</div>
<?php include_once("template_footer.php"); ?>
</div>
</body>
</html>