<?php

require "connect_to_mysql.php";

$sqlCommand = "Create table order2product 
(
	Customer_id int(11),
	Product_id int(11), 
	Quantity int(11)
)";

if(mysql_query($sqlCommand))
	echo "Your admin table has been created successfully.";
else
	echo "ERROR! Admin table has not been created."
	
if(mysql_query("Alter table order2product
add constraint customer_fk foreign key(customer_id) references CUSTOMER(Customer_id),
add constraint product_fk foreign key(product_id) references PRODUCT(Product_id),

echo "Table altered successfully.";

else
	die(mysql_error()); 



?>