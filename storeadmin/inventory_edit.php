<?php
// help you have persistent data, if theyaren't loggedin then session variable won't be created then we'll know they are not allowed to be here.
session_start(); 
if(!isset($_SESSION["manager"])) 
{
	header("location: admin_login.php"); // will not execute if loggedin.
	exit();
}
// Be sure to check that this manager SESSION value is infact in the database cuz people using firefox etc can create session cookies out of thin air and forge things on login type forms and can see the links.
$managerID = preg_replace('#[^0-9]#i','',$_SESSION["admin_id"]); // filter everything but nos and letters
$manager = preg_replace('#[^A-Za-z0-9]#i','',$_SESSION["manager"]); // filter everything but nos and letters
$password = preg_replace('#[^A-Za-z0-9]#i','',$_SESSION["admin_password"]); // filter everything but nos and letters

$conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 
$sql = oci_parse($conn,"SELECT Admin_ID FROM ADMINISTRATOR a,PERSON p WHERE a.PERSON_ID = p.PERSON_ID AND USERNAME = '$manager' AND PASSWORD = '$password' ");
// Make sure person exists in the database.
oci_execute($sql);
$result = oci_fetch_all($sql, $res); // count the row nums.
if($result == 0)
{
	echo "Your login session data is not on record in the database.";
	//session_destroy();
	//header("location:../index.php");
	exit();	
}

?>

<?php
error_reporting(E_ALL);
ini_set('display_errors','1');
?>


<?php
// Parses the form data and adds inventory item to the system.
if(isset($_POST['product_name']))
{
	$pid = mysql_real_escape_string($_POST['thisID']);
	$product_name = mysql_real_escape_string($_POST['product_name']);
	$price = mysql_real_escape_string($_POST['textfield']);
	$color = mysql_real_escape_string($_POST['textfield2']);
	$details = mysql_real_escape_string($_POST['textarea']);
	$supplier = mysql_real_escape_string($_POST['supplier']);	
	$category = mysql_real_escape_string($_POST['category']);	
		
	// See if that product name is an identical match to another product in the system.
	$sql = oci_parse($conn,"update product set name='$product_name',Price='$price',Color='$color',description='$details',Supplier_id='$supplier',Category_id='$category' where Product_id= '$pid'");
	oci_execute($sql);

	 if($_FILES['fileField']['tmp_name'] != "")
	 {
	 // Place the image in the folder.
	 $newname = "$pid.jpg";
	 move_uploaded_file($_FILES['fileField']['tmp_name'],"../inventory_images/$newname");
	 
	 }
	 header("location: inventory_list.php");
	 exit();
}
?>

<?php
// Gathers this product's full information for inserting automatically into the edit form below on page.

if(isset($_GET['pid']))
{
	//$targetID=$_GET['pid'];
	$targetID=preg_replace('#[^0-9]#i','',$_GET['pid']);
	
	$sql = oci_parse($conn,"select * from product where product_id='$targetID' ");
	//$productCount = mysql_num_rows($sql); // count the output amount.

	if(oci_execute($sql))
	{
		while($row = oci_fetch_array($sql))
		{
			$product_name=$row[1];
			$price=$row[2];
			$color=$row[3];
			$details=$row[4];
			$supplier=$row[5];
			$category=$row[6]; 
		}	
		
		$sql2 = oci_parse($conn,"select c.name,s.name from product p,category c,supplier s where p.category_id = c.category_id AND c.category_id = '$category' AND p.supplier_id = s.supplier_id AND s.supplier_id = '$supplier'");
		
		if(oci_execute($sql2))
		{
			while($row = oci_fetch_array($sql2))
			{
				$category_name=$row[0];
				$supplier_name=$row[1];		
			}
		}
	}
	else
	{
		echo "This product doesn't exist in the database";	
		exit();
	}
	
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inventory List</title>
<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
<?php include_once("../template_header_admin.php"); ?>
<div id="pageContent"><br/>
  <div align="right" style="margin-right:32px"><a href="inventory_list.php#inventoryForm">+ Add new inventory item</a></div>
<div align="left" style="margin-left:24px;">
  <h2>Inventory List</h2>
   
</div>
<a name = "inventoryForm" id = "inventoryForm"></a>  
 
 <h3>Add New Inventory Item Form</h3>
 
 <form action = "inventory_edit.php" enctype = "multipart/form-data" name = "myForm" id = "myForm" method = "post">
 
 <table width = "90%" border = "0" cellspacing = "0" cellpadding = "6">
 <tr>
 <td width = "20%">Product Name</td>
 <td width = "80%"><input name = "product_name" type = "text" id = "product_name" size = "64"  value="<?php echo $product_name; ?>"/> </label></td>
 </tr>
 <tr>
 <td>Product Price</td>
 <td> <label> $ <input name = "textfield" type = "text" id = "textfield" size = "12"  value="<?php echo $price; ?>" /> </label></td>
 </tr>
 <td>Product Color</td>
 <td> <label> <input name="textfield2" type = "text" id = "textfield2" size = "12" value="<?php echo $color; ?>" /> </label></td>
 </tr>
 <tr>
 <td>Product Details</td>
 <td> <label> <textarea name="textarea" id="textarea" cols="64" rows="5"><?php echo trim($details);?></textarea> </label></td>
 </tr>
  <td>Supplier</td>
 <td> <label> <select name = "supplier" id = "supplier"> <option value = "<?php echo $supplier; ?>" selected="selected"> <?php echo $supplier_name; ?> </option> <option value = "1"> Apple </option> <option value = "2" > Google </option> <option value = "3"> HTC </option> <option value="4"> HP </option> </select> 
 </label></td>
 <tr>
 <td>Category</td>
 <td> <label> <select name = "category" id = "category"> <option value = "<?php echo $category; ?>" selected="selected"> <?php echo $category_name; ?> </option> <option value = "Laptops"> Laptops </option> <option value = "Mobile Phones"> Mobile Phones </option> <option value = "Tablets"> Tablets </option> </select> 
 </label></td>
 </tr> 
 <tr>
 <td>Product Image</td>
 <td><label> <input type = "file" name = "fileField" id = "fileField" /> </label></td>
 </tr>
 <tr>
 <td>&nbsp;</td>
 <td><label> 
 <input name="thisID" type="hidden" value="<?php echo $targetID; ?>" />
 
 <input type = "submit" name = "button" value = "Make Changes" /> </label></td>
 </tr>
 
 </table>
 </form>
  <br/>
<br/>
<br/>
</div>
<?php include_once("../template_footer.php");  ?>

</div>
</body>
</html>