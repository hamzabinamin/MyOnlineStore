 <?php /*?><?php
//Script Error Reporting
error_reporting(E_ALL);
ini_set('display_error','1');
?>
<?php */?>
<?php
session_start(); 

// Connect to the MYSQL database.
	 $conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 
// Checks to see that the URL variable is set and that it exists in the database.
if(isset($_GET['id']))
{
	
	$id=preg_replace('#[^0-9]#i','',$_GET['id']);
	
	// Use this var to check if this ID exists, if yes then get the product details.
	// If no then exit this script and echo message.
	$sql = oci_parse($conn,"select * from product where Product_id='$id' ");
	//$productCount = mysql_num_rows($sql); // count the output amount.

if(oci_execute($sql)) 
{
	// Get the product details.
	while($row = oci_fetch_row($sql))
	{
		$id = $row[0];
		$product_name = $row[1];
		$price = $row[2];
		$details = $row[4];
		$supplier = $row[5];
		$category = $row[6];
		//$date_added = strftime("%b %d %Y", strtotime($row["date_added"])); 		
	}
	
	$sql2 = oci_parse($conn,"select c.name,s.name from product p,category c,supplier s where p.category_id = c.category_id AND p.supplier_id = s.supplier_id AND p.product_id = '$id'");
		
		if(oci_execute($sql2))
		{
			while($row = oci_fetch_array($sql2))
			{
				$category_name=$row[0];
				$supplier_name=$row[1];		
			}
		}		
		
		if($category_name == "Laptops")
		 {
			$sql = oci_parse($conn,"select Processor from product p,Laptop l where l.product_id = '$id' ");
			//$productCount = mysql_num_rows($sql);
			
			if(oci_execute($sql))
			{
			while($row = oci_fetch_array($sql))
			  {
				$specs=$row[0];
			
			  }		
			}
		
		 }
		 
		 else if($category_name == "Mobile Phones")
	    {
		$sql = oci_parse($conn,"select RAM from product p,Mobile_Phone m where m.product_id = '$id' ");
		//$productCount = mysql_num_rows($sql);
		
		if(oci_execute($sql))
		{
		while($row = oci_fetch_array($sql))
			{
			 	$specs=$row[0];
			
			}		
		}
		
	    }
	 
	 
	    else
	   {
	 	$sql = oci_parse($conn,"select Screen_size from product p,Tablet t where t.product_id = '$id' ");
		//$productCount = mysql_num_rows($sql);
		
			if(oci_execute($sql))
			{
			while($row = oci_fetch_array($sql))
			{
			 	$specs=$row[0];
			
			}	
			 
		    }
       }
}

else
{
	echo "That item does not exist.";
	exit();
}

}	
else
{
	echo "Data to render this page is missing."; // Get id is not set, which it should be ALWAYS.
	exit();
}

//mysql_close();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $product_name; ?></title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
<?php  if(isset($_SESSION["customer_username"])) { include_once("template_header_logout_account.php"); } else { include_once("template_header_login_account.php"); } ?>
<div id="pageContent"><table width="100%" border="0" cellspacing="15" cellpadding="0">
  <tr>
    <td width="21%" height="322" valign="top"><p>&nbsp;</p>
      <p> &nbsp; <img src="inventory_images/<?php echo $id; ?>.jpg" alt=<?php echo "$product_name"; ?> width="154" height="164" style="border:color#666" img 1px /></p>
      <p>  &nbsp; <a href="inventory_images/<?php echo $id; ?>.jpg">View Full Size Image</a></p>
      
    <td width="79%" valign="top">
      <h3>&nbsp;  </h3>
      <h3> &nbsp;&nbsp;<a href="#"><?php echo $product_name; ?> </a></h3>
      <p> &nbsp; <?php echo "Price:  $$price"; ?> </p>
      <p>&nbsp; <?php echo "Company: $supplier_name &nbsp; Category: $category_name"; ?> </p>
      <p> &nbsp; <?php echo "Product Details: $details"; ?> </p>
	  <p> &nbsp; <?php echo "Specifications: $specs"; ?> </p>
      <p>&nbsp;</p>
     <form id="form1" name="form1" method="post" action="cart.php">
     <input type="hidden" name="pid" id="pid" value="<?php echo $id; ?>" /> <input type="submit" name="button" id="button" value="Add to Shopping Cart" /> </form> 
        <br />
        </p>
     
      <p>&nbsp; </p></td>
    </tr>
</table>
</div>
<?php include_once("template_footer.php"); ?>

</div>
</body>
</html>