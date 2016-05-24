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
$conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 
$sql = oci_parse($conn,"SELECT Admin_ID FROM ADMINISTRATOR a,PERSON p WHERE a.PERSON_ID = p.PERSON_ID AND USERNAME = '$manager' AND PASSWORD = '$password' ");
// Make sure person exists in the database.
oci_execute($sql);
$result = oci_fetch_all($sql, $res); // count the row nums.

if($result == 0)
{
	echo "Your login session data is not on record in the database.";
	exit();	
}

?>

<?php
//error_reporting(E_ALL);
//ini_set('display_errors','1');
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
	$special = mysql_real_escape_string($_POST['special']);	
	
	// See if that product name is an identical match to another product in the system.
	$sql = oci_parse($conn,"SELECT PRODUCT_ID from PRODUCT where NAME = '$product_name'");
	oci_execute($sql);
	$result = oci_fetch_all($sql, $res); // count the rows.
	
	if($result > 0)
	{ 
		 echo "<script type='text/javascript'>alert('Sorry you are trying to add a duplicate Product into the system');window.location.href='add_item_form.php'; </script>";
	    exit();
		
	 }
	 else
	 {
	 	// Add this prodcut into the database now.
	 	$sql = oci_parse($conn,"INSERT INTO PRODUCT(PRODUCT_ID,NAME,PRICE,COLOR,DESCRIPTION,SUPPLIER_ID,CATEGORY_ID)  values(PRODUCT_INCREMENT.nextval,'$product_name','$price','$color','$details','$supplier','$category')");
	 	oci_execute($sql);
	 	//$sql = oci_parse($conn,"SELECT * FROM PRODUCT ORDER BY PRODUCT_ID DESC"); // Auto-incremented Id inside the database.
	 	$sql = oci_parse($conn,"SELECT * FROM (SELECT product_id FROM PRODUCT ORDER BY PRODUCT_ID DESC) WHERE ROWNUM = 1");
		//$result = oci_fetch_all($sql,$res);

		if(oci_execute($sql))
		{
	 		while($row = oci_fetch_row($sql))
			{ $pid = $row[0]; }	

		$newname = "$pid.jpg";
	 	move_uploaded_file($_FILES['fileField']['tmp_name'],"../inventory_images/$newname");
		}
	 	// Place the image in the folder.
	 	
	 
	$sql = oci_parse($conn,"SELECT c.NAME from PRODUCT p,CATEGORY c where p.CATEGORY_ID = c.CATEGORY_ID AND p.NAME = '$product_name' ");
	//oci_execute($sql);

	//$productCount = oci_fetch_all($sql, $res); // count the output amount.


	if(oci_execute($sql))
	{
		while($row = oci_fetch_row($sql))
		{
			$category_name=$row[0];
			
		}	
		
	}
	
	if($category_name == "Laptops")
	 {
		$sql = oci_parse($conn,"INSERT INTO LAPTOP(LAPTOP_ID,PRODUCT_ID,PROCESSOR) values (LAPTOP_INCREMENT.nextval,'$pid','$special')");
		oci_execute($sql);		
	 }
	 
	 else if($category_name == "Mobile Phones")
	 {
		$sql = oci_parse($conn,"INSERT INTO MOBILE_PHONE(MOBILE_PHONE_ID,PRODUCT_ID,RAM) values (MOBILE_PHONE_INCREMENT.nextval,'$pid','$special')");
		oci_execute($sql);		
	 }
	 
	 else
	 {
	 	$sql = oci_parse($conn,"INSERT INTO TABLET(TABLET_ID,PRODUCT_ID,SCREEN_SIZE) values (TABLET_INCREMENT.nextval,'$pid','$special')");	
	 	oci_execute($sql);
	 }
	 
	}	 
	
	header("location: inventory_list.php");
	exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Item Form</title>
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
	
	if(document.getElementById('special').value == "")
	{
		alert('Please enter the special specification');
		document.getElementById('special').style.borderColor = "red";
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
  </div>
<div align="left" style="margin-left:24px;">
</div>
<a name = "inventoryForm" id = "inventoryForm"></a>  
 
 <h2>Add New Inventory Item Form</h2>
 
 <form action = "add_item_form.php" onsubmit="return checkforblank()" enctype = "multipart/form-data" name = "myForm" id = "myForm" method = "post">
 
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
 <td>Special Specification</td>
 <td> <label> <input name="special" type = "text" id = "special" size = "12" /> </label></td>
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