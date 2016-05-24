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
error_reporting(E_ALL);
ini_set('display_errors','1');
?>

<?php
// Removes item from system and it's picture.
if(isset($_GET['yesdelete']))
{
// Deletes from database.
$id_to_delete=$_GET['yesdelete'];

/*$sql = oci_parse($conn,"select distinct op.product_Id from orders o,order2product op,product p where o.order_id = op.order_id AND op.product_id = '$id_to_delete'");
$productCount = mysql_num_rows($sql);
if($productCount > 0)
{
	  echo "<script type='text/javascript'>alert('Product already involved in an order, cant delete it.');window.location.href='inventory_list.php'; </script>";
	  exit();
}
*/

//else
//{
$sql = oci_parse($conn,"select c.name from product p,category c where p.category_id= c.category_id AND product_id = '$id_to_delete' ");
	//$productCount = mysql_num_rows($sql); // count the output amount.

	if(oci_execute($sql))
	{
		while($row = oci_fetch_row($sql))
		{
			$category_name=$row[0];
			
		}	
	}
	
	if($category_name == "Laptops")
	 {
		$sql = oci_parse($conn,"delete from Laptop where Product_id='$id_to_delete' ");
		oci_execute($sql);
		
	 }
	 
	 else if($category_name == "Mobile Phones")
	 {
		$sql = oci_parse($conn,"delete from Mobile_Phone where Product_id='$id_to_delete' ");
		oci_execute($sql);
		
	 }
	 
	 else
	 {
	 	$sql = oci_parse($conn,"delete from Tablet where Product_id='$id_to_delete' ");
	 	oci_execute($sql);
	 }
	 
	
$sql = oci_parse($conn,"delete from product where Product_id='$id_to_delete' ");
oci_execute($sql);

// Unlinks the image from server.
// Removes the pic
$pictodelete=("../inventory_images/$id_to_delete.jpg");
if(file_exists($pictodelete))
{
	unlink($pictodelete);	
}

//}
	 header("location: inventory_list.php");
	 exit();
}
?>


<?php
// This block grabs the whole product list for viewing.
$product_list = "";
$sql = oci_parse($conn,"select * from product order by product_ID DESC");
//$productCount = mysql_num_rows($sql); // count the output amount.

if(oci_execute($sql))
{
	while($row = oci_fetch_array($sql))
	{
		$id = $row[0];
		$product_name = $row[1];
		//$date_added = strftime("%b %d %Y", strtotime($row["date_added"])); 
		$product_list="$product_list $id - $product_name  &nbsp;&nbsp;&nbsp; <a href='inventory_edit.php?pid=$id'>edit</a> &bull; <a href='inventory_list.php?yesdelete=$id' onClick=\"return confirm('Do you want to delete?')\";>delete</a><br/>"; // line break.
			
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
</head>

<body>
<div align="center" id="mainWrapper">
<?php include_once("../template_header_admin.php"); ?>
<div id="pageContent"><br/>
  <div align="right" style="margin-right:32px">
  </div>
  <div align="right" style="margin-left:24px;">
<div align="left" style="margin-left:24px;">
  <h2>Inventory List </h2>
    <?php echo $product_list; ?></div>
<br/>
<br/>
</div>
<?php include_once("../template_footer.php");  ?>

</div>
</body>
</html>