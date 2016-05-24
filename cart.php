<?php
// help you have persistent data, if theyaren't loggedin then session variable won't be created then we'll know they are not allowed to be here.
session_start(); 
if(!isset($_SESSION["customer_username"])) 
{
	header("location: customer_login.php"); // will not execute if loggedin.
	exit();
}

// Connect to the MYSQL database.
$conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 
?>

<?php

// If the user attempts to add something to the cart from the product page.
if(isset($_POST['pid']))
{
	$pid = $_POST['pid'];
	$wasFound=false;
	$i=0;
	// If the cart session variable is not set or cart array is empty
	if(!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1)
	{
		$_SESSION["cart_array"]=array(0=>array("item_id"=>$pid,"quantity"=>1)); // Storing array in session variable so that it can remember what's inside a customer's cart.	
	}
	else
	{
		// Runs if the cart has at-least one item in it/ Updates quantity.
		foreach($_SESSION["cart_array"] as $each_item)
		{
			$i++;
			while(list($key,$value) = each($each_item))
			{
				if($key == "item_id" && $value == $pid)
				{
					// That item is in cart already, so we'll just adjust it's quantity.
					array_splice($_SESSION["cart_array"],$i-1,1,array(array("item_id"=>$pid,"quantity"=>$each_item["quantity"] + 1))); // Removes a portion of array and replaces it with something else i.e. the quantity updation.
				$wasFound = true;	
			    } // Closes if condition.
			} // Closes while loop.			
		} // Closes foreach loop.
		if($wasFound == false)
		{
			array_push($_SESSION["cart_array"],array("item_id"=>$pid,"quantity"=>1));	
		}
	}
	header("location:cart.php");
	exit();
}
?>

<?php

// If user chooses to empty his/her cart.
if(isset($_GET['cmd']) && $_GET['cmd'] == "emptycart")
{
	unset($_SESSION["cart_array"]);	
}
?>

<?php

// If user chooses to adjust item quantity.
if(isset($_POST['index_to_adjust']) && $_POST['index_to_adjust'] != "")
{
	$index_to_adjust = $_POST['index_to_adjust'];
	$quantity = $_POST['quantity'];
	$quantity = preg_replace('#[^0-9]#i','',$quantity); // Filters non allowed inputs e.g. decimal values.
	if($quantity >= 100) { $quantity = 99; } // Highest number for quantity.
	if($quantity < 1) { $quantity = 1; } // Lowest number for quantity.
	if($quantity == "") { $quantity = 1; }
	$i = 0;
	foreach($_SESSION["cart_array"] as $each_item)
		{
			$i++;
			while(list($key,$value) = each($each_item))
			{
				if($key == "item_id" && $value == $index_to_adjust)
				{
					// That item is in cart already, so we'll just adjust it's quantity.
					array_splice($_SESSION["cart_array"],$i-1,1,array(array("item_id"=>$index_to_adjust,"quantity"=>$quantity))); // Removes a portion of array and replaces it with something else i.e. the quantity updation.
			
			    } // Closes if condition.
			} // Closes while loop.			
		} // Closes foreach loop.
		
		header ("location: cart.php");
		exit();
}

?>

<?php
// If the user wants to remove an item from cart.
// This code runs when we send the hidden variable through the remove button.

// if the index_to_remove hiddle variable is set or is not empty then the if condiion will run.
if(isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != "")
{
// Access the array and run code to remove that array index.
	
	$key_to_remove = $_POST['index_to_remove']; // Storing the hiddle variable in local php variable.
	//echo 'index - '.$key_to_remove. ' : Count - ';
	if(count($_SESSION["cart_array"]) <= 1) // If we have only one item in our cart, we empty the cart.
	{
		unset($_SESSION["cart_array"]);
	}
	
	else
	{
		unset($_SESSION["cart_array"]["$key_to_remove"]);	// We specifically search for that item through it's index in array
		sort($_SESSION["cart_array"]);						// Then unset it and sort the array(sort array indexes).
		//echo count($_SESSION["cart_array"]);
	}
	header("location:cart.php");
	exit();
}

?>

<?php
// Renders the cart for the user to view.
$cartOutput = "";
$cartTotal = "";
if(!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1)
{
	$cartOutput = "<h2 align='center'>Your shopping cart is empty</h2>";		
}
else
{
	$i=0;
	foreach($_SESSION["cart_array"] as $each_item)
	{
		
		$item_id = $each_item['item_id'];
		$sql = oci_parse($conn,"select * from product where Product_id='$item_id' ");
		
		if(oci_execute($sql))
		{
			while($row= oci_fetch_Array($sql))
			{
				$product_name = $row[1];
				$price = $row[2];
				$color = $row[3];
				$details = $row[4];
			}
		}
		$pricetotal = $price * $each_item['quantity'];
		$cartTotal = $pricetotal + $cartTotal;
		
		setlocale(LC_MONETARY,"en_US");
		$pricetotal = number_format($pricetotal,2);
		
		// Dynamic table row assembly.
		$cartOutput .= "<tr>";
		$cartOutput .= '<td><a href="product.php?id=' .$item_id .'">'. $product_name. '</a> <br/><img src="inventory_images/' . $item_id .'.jpg" alt="' .$product_name. ' " width="40" height="52" border="1" /></td>';
		$cartOutput .= '<td>'.$details. '</td>';
		$cartOutput .= '<td>$'.$price. '</td>';
		$cartOutput .= '<td><form action="cart.php" method="post">
		<input name="quantity" type="text" value="'.$each_item['quantity'].'" size="1" maxlength="2" />
		<input name="adjustBtn' .$item_id. '" type="submit" value="change" />
		<input name="index_to_adjust" type="hidden" value="' . $item_id . '"/></form> </td>';
		//$cartOutput .= '<td>'.$each_item['quantity']. '</td>';
		$cartOutput .= '<td>$'.$pricetotal. '</td>';
		$cartOutput .= '<td>'.'<form action="cart.php" method="post"><input name="deleteBtn' .$item_id. '" type="submit" value="X" /><input name="index_to_remove" type="hidden" value="' . $i . '"/></form></td>';
		$cartOutput .= '</tr>';
		
		$i++;
	}		
		$_SESSION["order_total"] = $cartTotal;
		setlocale(LC_MONETARY,"en_US");
		$cartTotal = number_format($cartTotal,2);
		$cartTotal = "<div align='right'>Cart Total: $" .$cartTotal. "USD </div>" ;
		
}
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Your Cart</title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

<script>

// Form Validation.

function checkforblankPayment()
{
	if(document.getElementById('PaymentType').value == "")
	{
		alert('Please select a payment type before placing the order');
		document.getElementById('PaymentType').style.borderColor = "red";
		return false;		
	}

}

</script>

</head>

<body>
<div align="center" id="mainWrapper">
<?php  if(isset($_SESSION["customer_username"])) { include_once("template_header_logout_account.php"); } else { include_once("template_header_login_account.php"); } ?>
<div id="pageContent">
<div style="margin:24px; text-align:left;">

<br/>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
  <tr>
    <td width="34%" bgcolor="#DAF1FE"><strong>Product</strong></td>
    <td width="34%" bgcolor="#DAF1FE"><strong>Product Description</strong></td>
    <td width="10%" bgcolor="#DAF1FE"><strong>Unit Price</strong></td>
    <td width="8%" bgcolor="#DAF1FE"><strong>Quantity</strong></td>
    <td width="6%" bgcolor="#DAF1FE"><strong>Total</strong></td>
    <td width="8%" bgcolor="#DAF1FE"><strong>Remove</strong></td>
  </tr>
  <?php echo $cartOutput; ?>
 <!-- <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>-->
</table>
<p><?php echo "$cartTotal"; ?>
  <br/>
</p>  
<p>  
&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
  <a href="cart.php?cmd=emptycart">Empty Your Cart</a>
  &emsp;
  <a href="http://localhost/MyOnlineStoreOwn/product_list.php">Continue Shopping</a>  
<form action = "order_page.php" onsubmit="return checkforblankPayment()" enctype = "multipart/form-data" name = "myForm" id = "myForm" method = "post">
 
 <table width = "90%" border = "0" align="right" cellpadding = "6" cellspacing = "0">
 <tr>
 <td width="76%" align="right">Payment Type</td>
 <td width="24%"> <label>  
   <select name = "PaymentType" id = "PaymentType"> <option value = "" selected="selected">Select a payment type</option> <option value = "1"> Cash on delivery </option> <option value = "2" > Credit-Card </option> </select> 
 </label></td>
 </tr>
 <tr>
 <td>&nbsp;</td>
 <td><label> <input type = "submit" name = "button" value = "Check Out" /> </label></td>
 </tr>
 
 </table>
 </form>
  <br/>
<br/>
<br/>
</div>
</div>



 <?php include_once("template_footer.php"); ?>
</div>
</body>
</html>