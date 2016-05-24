<?php
// help you have persistent data, if theyaren't loggedin then session variable won't be created then we'll know they are not allowed to be here.
session_start(); 
if(!isset($_SESSION["manager"])) 
{
	header("location:admin_login.php"); // will not execute if loggedin.
	exit();
}
// Be sure to check that this manager SESSION value is in fact in the database cuz people using firefox etc can create session cookies out of thin air and forge things on login type forms and can see the links.
$managerID = preg_replace('#[^0-9]#i','',$_SESSION["admin_id"]); // filter everything but nos and letters
$manager = preg_replace('#[^A-Za-z0-9]#i','',$_SESSION["manager"]); // filter everything but nos and letters
$password = preg_replace('#[^A-Za-z0-9]#i','',$_SESSION["admin_password"]); // filter everything but nos and letters

// Connect to the MYSQL database.
include "../storescripts/connect_to_mysql.php";
$sql = mysql_query("Select * from admin where id='$managerID' AND username='$manager' AND password='$password' LIMIT 1");
// Make sure person exists in the database.

$existCount = mysql_num_rows($sql); // count the row nums.
if($existCount == 0)
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
// Deletes Item / Questions Admin, and deletes product if they choose yes.
if(isset($_GET['deleteid']))
{
	echo 'Do you really want to delete this item with ID of ' .$_GET['deleteid']. '? <a href = "inventory_list.php?yesdelete='.$_GET['deleteid']. '"> Yes</a> | <a href="inventory_list.php"> No</a>';	
	exit(); // doesn't render the whole page, only prompts the question script.
}

// Removes item from system and it's picture.
if(isset($_GET['yesdelete']))
{
// Deletes from database.
$id_to_delete=$_GET['yesdelete'];
$sql = mysql_query("delete from product where Product_id='$id_to_delete' limit 1") or die(mysql_error());

// Unlinks the image from server.
// Removes the pic
$pictodelete=("../inventory_images/$id_to_delete.jpg");
if(file_exists($pictodelete))
{
	unlink($pictodelete);	
}

	 header("location: inventory_list.php");
	 exit();
}
?>

<?php
// Parses the form data and adds inventory item to the system.
if(isset($_POST['product_name']))
{
	$product_name = mysql_real_escape_string($_POST['product_name']);
	$price = mysql_real_escape_string($_POST['textfield']);
	$color = mysql_real_escape_string($_POST['textfield2']);
	$supplier = mysql_real_escape_string((int)$_POST['supplier']);
	$category = mysql_real_escape_string((int)$_POST['category']);	
	$details = mysql_real_escape_string($_POST['textarea']);	
	
	// See if that product name is an identical match to another product in the system.
	$sql = mysql_query("select Product_id from product where Product_name = '$product_name' limit 1");
	$productMatch = mysql_num_rows($sql); // count the rows.
	
	if($productMatch > 0)
	{ 
		echo "<script> alert('Sorry you are trying to add a duplicate Product into the system') </script";
		
	 }
	 else
	 {
	 	// Add this prodcut into the database now.
	 	$sql = mysql_query("insert into product(Product_name,Price,Color,Product_details,date_added,Supplier_id,Category_id)   		     	values('$product_name','$price','$color','$details',now(),'$supplier','$category')") or die(mysql_error());
	 	$pid = mysql_insert_id(); // Auto-incremented Id inside the database.
	 
	 	// Place the image in the folder.
	 	$newname = "$pid.jpg";
	 	move_uploaded_file($_FILES['fileField']['tmp_name'],"../inventory_images/$newname");
	 	header("location: inventory_list.php");
	 	exit();
	 }
}
?>

<?php
// This block grabs the whole product list for viewing.
$product_list = "";
$sql = mysql_query("select * from product order by date_added ASC");
$productCount = mysql_num_rows($sql); // count the output amount.

if($productCount > 0)
{
	while($row = mysql_fetch_array($sql))
	{
		$id = $row["Product_id"];
		$product_name = $row["Product_name"];
		$date_added = strftime("%b %d %Y", strtotime($row["date_added"])); 
		$product_list="$product_list $id - $product_name - $date_added &nbsp;&nbsp;&nbsp; <a href='inventory_edit.php?pid=$id'>edit</a> &bull; <a href='inventory_list.php?deleteid=$id'>delete</a><br/>"; // line break.	
	}	
}
else
{
	$product_list = "You have no products in your store yet";	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inventory List</title>
<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />

<script>

// Form Validation.

// Function to remove the white spaces in the details textarea.
function trimfield(str) 
{ 
    return str.replace(/^\s+|\s+$/g,''); 
}

function checkforblank()
{
	if(document.getElementById('product_name').value == "")
	{
		alert('Please enter the Products name');
		document.getElementById('product_name').style.borderColor = "red";
		return false;		
	}
	
	if(document.getElementById('textfield').value == "")
	{
		alert('Please enter Products price');
		document.getElementById('textfield').style.borderColor = "red";
		return false;		
	}		
	
	if(document.getElementById('textfield2').value == "")
	{
		alert('Please enter Products color');
		document.getElementById('textfield2').style.borderColor = "red";
		return false;		
	}		
	
	if(trimfield(document.getElementById('textarea').value) == '')
	{
		alert('Please enter the products details');
		document.getElementById('textarea').style.borderColor = "red";
		return false;		
	}	
	
	if(document.getElementById('supplier').value == "")
	{
		alert('Please select Products supplier');
		document.getElementById('supplier').style.borderColor = "red";
		return false;		
	}	
	
	if(document.getElementById('category').value == "")
	{
		alert('Please select Products category');
		document.getElementById('category').style.borderColor = "red";
		return false;		
	}	
	
	if(document.getElementById('fileField').value == "")
	{
		alert('Please upload an image of the product');
		document.getElementById('fileField').style.borderColor = "red";
		return false;		
	}	
}

</script>


</head>

<body>
<div align="center" id="mainWrapper">
<?php include_once("../template_header_admin.php"); ?>
<div id="pageContent"><br/>
  <div align="right" style="margin-right:32px">
    <p><a href="inventory_list.php#inventoryForm">+ Add new inventory item</a></p>
  </div>
<div align="left" style="margin-left:24px;">
  <h2>Inventory List</h2>
    <?php echo $product_list; ?>
</div>
<a name = "inventoryForm" id = "inventoryForm"></a>  
 
 <h3>Add New Inventory Item Form</h3>
 
 <form action = "inventory_list.php" onsubmit="return checkforblank()" enctype = "multipart/form-data" name = "myForm" id = "myForm" method = "post">
 
 <table width = "90%" border = "0" cellspacing = "0" cellpadding = "6">
 <tr>
 <td width = "20%">Product Name</td>
 <td width = "80%"><input name = "product_name" type = "text" id = "product_name" size = "64" /> </label></td>
 </tr>
 <tr>
 <td>Product Price</td>
 <td> <label> $ <input name = "textfield" type = "text" id = "textfield" size = "12" /> </label></td>
 </tr>
 <td>Product Color</td>
 <td> <label> <input name="textfield2" type = "text" id = "textfield2" size = "12" /> </label></td>
 </tr>
 <tr>
 <tr>
 <td>Product Details</td>
 <td> <label> <textarea name = "textarea" id = "textarea" cols = "64" rows = "5"></textarea> </label></td>
 </tr>
 <tr>
 <td>Supplier</td>
 <td> <label> <select name = "supplier" id = "supplier"> <option value = "" selected="selected"> </option> <option value = "1"> Apple </option> <option value = "2" > Google </option> <option value = "3"> HTC </option> <option value="4"> HP </option> </select> 
 </label></td>
 </tr>
  <tr>
 <td>Category</td>
 <td> <label> <select name = "category" id = "category"> <option value = "" selected="selected"> </option> <option value = "1"> Laptops </option> <option value = "2" > Mobile Phones </option> <option value = "3"> Tablets </option> </select> 
 </label></td>
 </tr>

 <td>Product Image</td>
 <td><label> <input type = "file" name = "fileField" id = "fileField" /> </label></td>
 </tr>
 <tr>
 <td>&nbsp;</td>
 <td><label> <input type = "submit" name = "button" value = "Add This Item Now" /> </label></td>
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