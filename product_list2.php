<?php
session_start(); 
// Runs a select query to get the latest 6 items.
// Connect to the MYSQL database.
include "storescripts/connect_to_mysql.php";
$dynamicList = "";
$sql = mysql_query("select * from product");
$productCount = mysql_num_rows($sql); // count the output amount.

if($productCount > 0)
{
	while($row = mysql_fetch_array($sql))
	{
		$id = $row["Product_id"];
		$product_name = $row["Product_name"];
		$price = $row["Price"];
		$color = $row["Color"];
		$date_added = strftime("%b %d %Y", strtotime($row["date_added"])); 
		$dynamicList.='<table width="100%" border="0" cellspacing="0" cellpadding="6">
        <tr>
          <td width="29%"><a href="product.php?id=' .$id . '"><img style="border:#666 1px solid" src="inventory_images/' .$id . '.jpg" width="136" height="138" alt="' .$product_name .'" /></a></td>
          <td width="71%" valign="top">' . $product_name . '<br />
            $' . $price . '<br />
            <a href="product.php?id=' .$id . '">View Product Details</a></td>
        </tr>
      </table>'; // line break.	
	}	
	
	
}
else
{
	$dynamicList = "We have no products in our store yet.";	
}

mysql_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen, projection">
        <script type="text/javascript" src="js/jquery-1.4.2.min.js">
        </script>
        <script type="text/javascript" src="js/scripts.js">
        </script>
        </script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Product List</title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

</head>

<body>
<script>
function checkforblankProduct()
{
	if(document.getElementById('price1').value == "")
	{
		alert('Please enter price first');
		document.getElementById('price1').style.borderColor = "red";
		return false;		
	}
	
	if(document.getElementById('price2').value == "")
	{
		alert('Please enter price first');
		document.getElementById('price2').style.borderColor = "red";
		return false;		
	}		
}
</script>	



<div align="center" id="mainWrapper">
<?php  if(isset($_SESSION["customer_username"])) { include_once("template_header_logout_account.php"); } else { include_once("template_header_login_account.php"); } ?>
<div id="pageContent"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="18%" valign="top"><p><strong>Category</strong></p>
      <p><a href="http://localhost/MyOnlineStoreOwn/product_list.php">All</a></p>
      <p><a href="http://localhost/MyOnlineStoreOwn/product_list_Laptops.php">Laptops</a></p>
      <ul>
  <li><a href="default.asp">Processor</a></li>
	</ul>
      <p><a href="http://localhost/MyOnlineStoreOwn/product_list_MobilePhones.php">Mobile Phones</a></p>
      <ul>
  <li><a href="default.asp">RAM</a></li>
  <ul>
                                    <li>
                                	<a href="Core_i7.php">1 GB</a> </li>
                                    <li>
                                    <a href="4_GB.php">4 GB </a></li>
                   		  </ul>
                                </li>
                                </ul>
                                </li>
	  </ul>
      <p><a href="http://localhost/MyOnlineStoreOwn/product_list_Tablets.php">Tablets</a></p>
      <ul>
  <li><a href="default.asp">Screen Size</a></li>
	</ul>
      <p><strong>By Company</strong></p>
      <p><a href="http://localhost/MyOnlineStoreOwn/product_list_Apple.php">Apple</a></p>
      <p><a href="http://localhost/MyOnlineStoreOwn/product_list_Google.php">Google</a></p>
      <p><a href="http://localhost/MyOnlineStoreOwn/product_list_HP.php">HP</a></p>
      <p><a href="http://localhost/MyOnlineStoreOwn/product_list_HTC.php">HTC</a></p>
      <p><strong>By Price</strong></p>
      
      <form action="http://localhost/MyOnlineStoreOwn/product_list_priceSearch.php" onsubmit="return checkforblankProduct()" method="post">
        $<input name="price1" type="text" id="price1" size=8 /> 
   to <br/>
   $<input name="price2" type="text" id="price2" size=8 /> <br>
   &nbsp;&nbsp;<input type="submit" name="sprice" value="Go" >
</form>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="80%" valign="top"><p> <strong>Product List</strong></p>
      <p><?php echo $dynamicList; ?><br />
        <br />
        </p>
      <!--<table width="100%" border="0" cellspacing="0" cellpadding="6">
        <tr>
          <td width="29%"><a href="product.php?"><img style="border:#666 1px solid" src="inventory_images/13.jpg" width="136" height="138" alt="$dynamicTitle" /></a></td>
          <td width="71%" valign="top">Product Tile<br />
            Product Price<br />
            <a href="product.php?">View Product</a></td>
        </tr>
      </table>--> 
      <p>&nbsp; </p></td>
    </tr>
</table>
</div>
<?php include_once("template_footer.php"); ?>

</div>
</body>
</html>