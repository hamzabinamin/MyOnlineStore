<?php
session_start();
// Runs a select query to get the latest 6 items.
// Connect to the MYSQL database.
$conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 
$dynamicList = "";
$sql = oci_parse($conn,"select Product_id,p.name,Price,Color,c.name 
from product p,category c 
where p.category_id = c.category_id AND p.category_id=(select category_id from category c where c.name = 'Tablets')
 ");
//$productCount = mysql_num_rows($sql); // count the output amount.

if(oci_execute($sql))
{
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
<title>Product List Tablets</title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
<?php  if(isset($_SESSION["customer_username"])) { include_once("template_header_logout_account.php"); } else { include_once("template_header_login_account.php"); } ?>
<div id="pageContent"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="17%" valign="top">
  <div>&nbsp;</div>
    <div align="left"></div>
    <div align="left"><strong>Category</strong></div>
    <ul>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list.php">All </a></li>
</ul>

    <div align="left"><strong>Laptops</strong></div>
   <ul>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_Laptops.php"> All </a></li>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_Core_i5.php"> Core i5 </a></li>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_Core_i7.php"> Core i7</a> </li>
</ul>

    <div align="left"><strong>Mobile Phones</strong></div>
    <ul>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_MobilePhones.php"> All </a></li>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_1GB.php"> 1 GB </a></li>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_4GB.php"> 4 GB</a> </li>
</ul>

    <div align="left"><strong>Tablets</strong></div>
    <ul>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_Tablets.php"> All </a></li>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_7inches.php"> 7 inches </a></li>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_9inches.php"> 9 inches</a> </li>
</ul>


    <div align="left"><strong>Company</strong></div>
    <ul>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_Apple.php"> Apple </a></li>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_Google.php"> Google </a></li>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_HP.php"> HP </a> </li>
<li><a href="http://localhost/MyOnlineStoreOwn/product_list_HTC.php"> HTC </a> </li>
</ul>


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
      <p>&nbsp;</p></td>
    <td width="80%" valign="top"><p> <strong>Tablets List</strong></p>
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