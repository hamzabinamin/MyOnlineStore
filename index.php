<?php
// Runs a select query to get the latest 6 items.
// Connect to the MYSQL database.
session_start(); 
//include "storescripts/connect_to_mysql.php";
$dynamicList = "";
 $conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 

$sql = oci_parse($conn,"SELECT * FROM PRODUCT");
//$result = oci_fetch_all($sql, $res);
if(oci_execute($sql))
{
	while($row = oci_fetch_row($sql))
	{
		$id = $row[0];
		$product_name = $row[1];
		$price = $row[2];
		$color = $row[3];
		//$date_added = strftime("%b %d %Y", strtotime($row["date_added"])); 
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
	$dynamicList = "We have no products in our store yet";	
}

//mysql_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Store Home Page</title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
<?php  if(isset($_SESSION["customer_username"])) { include_once("template_header_logout_account.php"); } else { include_once("template_header_login_account.php"); } ?>
<div id="pageContent"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="27%" align="center" valign="top"><p><strong>Welcome to our store</strong></p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="43%" align="center" valign="top"><p> <strong>Newest Items</strong></p>
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
    <td width="30%" align="center" valign="top"><p><strong>Store Information</strong></p>
      <p>Our electronics store offers top of the line and the newest products. The products are supplied to our store directly from official vendors. We deal with the latest gadgets ranging from Laptops,Mobile Phones and Tablets.</p>
      <p></p></td>
  </tr>
</table>
</div>

<?php include_once("template_footer.php"); ?>

</div>
</body>
</html>