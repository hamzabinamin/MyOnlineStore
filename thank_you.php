 <?php /*?><?php
//Script Error Reporting
error_reporting(E_ALL);
ini_set('display_error','1');
?>
<?php */?>
<?php
// help you have persistent data, if they aren't loggedin then session variable won't be created then we'll know they are not allowed to be here.
session_start(); 
if(!isset($_SESSION["customer"])) 
{
	header("location: customer_login.php"); // will not execute if loggedin.
	exit();
}

// Connect to the MYSQL database.
	include "storescripts/connect_to_mysql.php";
// Checks to see that the URL variable is set and that it exists in the database.

// Will be used to output the order elements.

if(isset($_SESSION["cart_array"]) && !empty($_SESSION["cart_array"]))
{
	echo 'Thank You for making your order. <a href="order_page.php">Order Details</a>';	
}

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
  <?php  if(isset($_SESSION["customer"])) { include_once("template_header_logout_account.php"); } else { include_once("template_header_login_account.php"); } ?>
<div id="pageContent">
<div style="margin:24px; text-align:left;">

<br/>
</div>
<br/>
</div>
<?php include_once("template_footer.php"); ?>
</div>
</body>
</html>