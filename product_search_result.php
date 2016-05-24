<?php 
session_start();
/*?><?php
//Script Error Reporting
error_reporting(E_ALL);
ini_set('display_error','1');
?>
<?php *////*?>
<?php
// Connect to the MYSQL database.
	$conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 
	
// Checks to see that the URL variable is set and that it exists in the database.
if(isset($_POST['search']) && !empty($_POST['search']))
{
	$Searchresult=preg_replace("#[^0-9a-z]#i"," ",$_POST['search']);
	$dynamicList = "";
	// Use this var to check if this ID exists, if yes then get the product details.
	// If no then exit this script and echo message.
	$sql = oci_parse($conn,"SELECT * FROM PRODUCT WHERE NAME LIKE '%$Searchresult%' ");
	//$productCount = mysql_num_rows($sql); // count the output amount.
	

if(oci_execute($sql)) 
{
	// Get the product details.
	while($row = oci_fetch_array($sql))
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
	
	/*$sql2 = oci_parse($conn,"select c.name, s.name from product p,category c,supplier s where p.category_id = c.category_id AND p.supplier_id = s.supplier_id");
		
		if(oci_execute($sql2))
		{
			while($row = oci_fetch_array($sql2))
			{
				$category_name=$row[0];
				$supplier_name=$row[1];
			}		
		}*/		
}
else 
{
	$dynamicList = "Item doesn't exist";
}
}
else
{
	echo "<script type='text/javascript'>alert('Data to render this page is missing');window.location.href='index.php'; </script>";
}

//mysql_close();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Product List Through Search Result</title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
<?php  if(isset($_SESSION["customer_username"])) { include_once("template_header_logout_account.php"); } else { include_once("template_header_login_account.php"); } ?>
<div id="pageContent"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
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