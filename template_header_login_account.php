<div id="pageHeader"><table width="100%" border="0" cellspacing="0" cellpadding="12">
  <tr>
    <td width="25%"><a href="index.php"><img src="http://localhost/xampp/MyOnlineStoreOwn/style/logo.jpg" width="210" height="35" alt="Logo" /></a></td>
    <td width="75%" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="left"><a href="http://localhost/MyOnlineStoreOwn">Home</a> &nbsp; &middot; &nbsp; <a href="http://localhost/MyOnlineStoreOwn/product_list.php">Products</a> &nbsp; &middot; &nbsp; <a href="http://localhost/MyOnlineStoreOwn/help_page.php">Help</a> &nbsp; &middot; &nbsp; <a href="http://localhost/MyOnlineStoreOwn/contact_page.php">Contact</a>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&middot; &nbsp; <a href="http://localhost/MyOnlineStoreOwn/registration_form.php">Create Account </a> &nbsp; &middot; &nbsp; <a href="http://localhost/MyOnlineStoreOwn/customer_login.php"> Sign in</a>  </td>
    
    </tr>
    
</table>
<script>
 function checkforblank()
{
	if(document.getElementById('search').value == "")
	{
		alert('Please type a product name first');
		document.getElementById('search').style.borderColor = "red";
		return false;		
	}
}
</script>
</div>

<div id="pageHeader"><table width="100%" border="0" cellspacing="0" cellpadding="12">
  <tr>
    <td width="25%" align="right">
      <form action ="http://localhost/MyOnlineStoreOwn/product_search_result.php" onsubmit="return checkforblank()"  method="post">
         <input type ="text"name="search" id = "search" placeholder="Search for products..." align="right"  />
      <input type="submit" value="Search" />
    </form></td>
  </tr>
  
</table>
 
</div>

