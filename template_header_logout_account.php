<div id="pageHeader"><table width="100%" border="0" cellspacing="0" cellpadding="12">
  <tr>
    <td width="25%"><a href="index.php"><img src="http://localhost/MyOnlineStoreOwn/style/logo.jpg" width="210" height="35" alt="Logo" /></a></td>
    <td width="75%" align="right"><a href="cart.php">Your Cart</a></td>
  </tr>
  <tr>
    <td colspan="2" align="left"><a href="http://localhost/MyOnlineStoreOwn">Home</a> &nbsp; &middot; &nbsp; <a href="http://localhost/MyOnlineStoreOwn/product_list.php">Products</a> &nbsp; &middot; &nbsp; <a href="http://localhost/MyOnlineStoreOwn/help_page.php">Help</a> &nbsp; &middot; &nbsp; <a href="http://localhost/MyOnlineStoreOwn/contact_page.php">Contact</a>&emsp;&emsp;&emsp;&emsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp; &middot; &nbsp; <a href="http://localhost/MyOnlineStoreOwn/customer_log_out.php"> Log out</a>  </td>
    
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
    <td width="25%" align="right"><form action ="http://localhost/MyOnlineStoreOwn/product_search_result.php" onsubmit="return checkforblank()"  method="post">
         <input type ="text"name="search" id = "search" placeholder="Search for products..." align="right"  />
      <input type="submit" value="Search" />
    </form></td>
  </tr>
  
</table>
 
</div>

