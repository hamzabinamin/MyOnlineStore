<?php
session_start();
// Connect to the MYSQL database.
$conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 
?>

<?php
/*error_reporting(E_ALL);
ini_set('display_errors','1');*/
?>


<?php
// Parses the form data and adds the new customer to the system.
if(isset($_POST['first_name']))
{
	$first_name = mysql_real_escape_string($_POST['first_name']);
	$last_name = mysql_real_escape_string($_POST['last_name']);
	$email = mysql_real_escape_string($_POST['email']);
	$username = mysql_real_escape_string($_POST['username']);
	$pw = mysql_real_escape_string($_POST['pw']);
	$country = mysql_real_escape_string($_POST['country']);
	$state = mysql_real_escape_string($_POST['state']);
	$city = mysql_real_escape_string($_POST['city']);
	$postal = mysql_real_escape_string($_POST['postal']);
	$address = mysql_real_escape_string($_POST['address']);
	$baddress = mysql_real_escape_string($_POST['baddress']);
	$phone = mysql_real_escape_string((int)$_POST['phone']);	

	// See if that username is match to another one in the database.
	$sql = oci_parse($conn,"select username,password from person where username = '$username'");
	//$productMatch = mysql_num_rows($sql); // count the rows.
	oci_execute($sql);
	$result = oci_fetch_all($sql, $res);

	if($result > 0)
	{ 
		echo 'Sorry this uername is already taken, <a href = "registration_form.php">click here</a>';
	
	exit();
	 }
	 
	 // Add this customer to the database.
	 $sql = oci_parse($conn,"INSERT INTO PERSON(PERSON_ID,USERNAME,PASSWORD,PHONE,ADDRESS,FIRST_NAME,LAST_NAME,COUNTRY,STATE,CITY,EMAIL) values(PERSON_INCREMENT.nextval,'$username','$pw','$phone','$address','$first_name','$last_name','$country','$state','$city','$email')");
	 oci_execute($sql);

	 $sql = oci_parse($conn,"SELECT * FROM (SELECT PERSON_ID FROM PERSON ORDER BY PERSON_ID DESC) WHERE ROWNUM = 1");

	 if(oci_execute($sql))
		{
	 		while($row = oci_fetch_row($sql))
			{ $pid = $row[0]; }
		}	

	 $sql = oci_parse($conn, "INSERT INTO Customer(Customer_id,Person_id,Billing_Address) values(Customer_Increment.nextval,'$pid','$baddress')");
	 oci_execute($sql);

	 echo 'Account created successfully, <a href = "customer_login.php">click here to go to the Log-in page.</a>';
	 exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registration Form</title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

<script>

// Form Validation.

// Function to remove the white spaces in the details textarea.
function trimfield(str) 
{ 
    return str.replace(/^\s+|\s+$/g,''); 
}

function checkforblank()
{
	if(document.getElementById('first_name').value == "")
	{
		alert('Please enter your First name');
		document.getElementById('first_name').style.borderColor = "red";
		return false;		
	}
	
	
	if(document.getElementById('last_name').value == "")
	{
		alert('Please enter your Last name');
		document.getElementById('last_name').style.borderColor = "red";
		return false;		
	}
	
	
	if(document.getElementById('email').value == "")
	{
		alert('Please enter your E-mail address');
		document.getElementById('email').style.borderColor = "red";
		return false;		
	}		
	
	if(document.getElementById('username').value == "")
	{
		alert('Please enter your User name');
		document.getElementById('username').style.borderColor = "red";
		return false;		
	}		
	
	if(document.getElementById('pw').value == "")
	{
		alert('Please enter your Password');
		document.getElementById('pw').style.borderColor = "red";
		return false;		
	}		
	
	if(document.getElementById('country').value == "")
	{
		alert('Please enter your Country');
		document.getElementById('country').style.borderColor = "red";
		return false;		
	}		
	
	if(trimfield(document.getElementById('state').value) == '')
	{
		alert('Please enter your State');
		document.getElementById('state').style.borderColor = "red";
		return false;		
	}	
	
	if(document.getElementById('city').value == "")
	{
		alert('Please enter your City');
		document.getElementById('city').style.borderColor = "red";
		return false;		
	}	
	
	if(document.getElementById('postal').value == "")
	{
		alert('Please enter your Postal code');
		document.getElementById('postal').style.borderColor = "red";
		return false;		
	}	
	
	
	if(document.getElementById('address').value == "")
	{
		alert('Please enter your Address');
		document.getElementById('address').style.borderColor = "red";
		return false;		
	}	
	
	if(document.getElementById('baddress').value == "")
	{
		alert('Please enter your Billing address');
		document.getElementById('baddress').style.borderColor = "red";
		return false;		
	}	
	
	if(document.getElementById('phone').value == "")
	{
		alert('Please enter your Phone number');
		document.getElementById('phone').style.borderColor = "red";
		return false;		
	}	
}

</script>


</head>

<body>
<div align="center" id="mainWrapper">
<?php  if(isset($_SESSION["customer_username"])) { include_once("template_header_logout_account.php"); } else { include_once("template_header_login_account.php"); } ?>
<div id="pageContent"><br/>
<div align="left" style="margin-left:24px;">
  <h2>Registration</h2>
   
</div>
<a name = "registrationForm" id = "registrationForm"></a>  
 
 <h3>Registration Form</h3>
 
 <form action = "registration_form.php" onsubmit="return checkforblank()" enctype = "multipart/form-data" name = "myForm" id = "myForm" method = "post">
 
 <table width = "90%" border = "0" cellspacing = "0" cellpadding = "6">
 <tr>
 <td width = "20%">First Name</td>
 <td width = "80%"><input name = "first_name" type = "text" id = "first_name" size = "34" /> </label></td>
 </tr>
 <tr>
 <td>Last Name</td>
 <td> <label>
 <input name = "last_name" type = "text" id = "last_name" size = "34" /> </label></td>
 </tr>
 <td>E-mail</td>
 <td> <label> <input name="email" type = "text" id = "email" size = "34" /> </label></td>
 </tr>
  <td>User Name</td>
 <td> <label> <input name="username" type = "text" id = "username" size = "34" /> </label></td>
 </tr>
 <td>Password</td>
 <td> <label> <input name="pw" type = "text" id = "pw" size = "34" /> </label></td>
 </tr>
 <tr>
 <td>Country</td>
 <td> <label> <input name="country" type="text" id="country" size = "34" /> </label></td>
 </tr>
  <td>State</td>
 <td> <label> <input name="state" type="text" id="state" size = "34" /> </label></td>
 </tr>
  <td>City</td>
 <td> <label> <input name="city" type="text" id="city" size = "34" /> </label></td>
 </tr>
  <td>Postal code</td>
 <td> <label> <input name="postal" type="text" id="postal" size = "34" /> </label></td>
 </tr>
  <td>Address</td>
 <td> <label> <input name="address" type="text" id="address" size = "34" /> </label></td>
 </tr>
  <td>Billing Address</td>
 <td> <label> <input name="baddress" type="text" id="baddress" size = "34" /> </label></td>
 </tr>
  <td>Phone</td>
 <td> <label> <input name="phone" type="text" id="phone" size = "34" /> </label></td>
 </tr>
 <tr>
 <td>&nbsp;</td>
 <td><label> <input type = "submit" name = "button" value = "Create Account" /> </label></td>
 </tr>
 </label>
 </table>
 </form>
</div>
<?php include_once("template_footer.php");  ?>

</div>
</body>
</html>