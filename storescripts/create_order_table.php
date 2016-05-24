<?php

require "connect_to_mysql.php";

$sqlCommand = "Create table ORDERS
(
	Order_id int(11) not null auto_increment,
	Order_date date not null,
	primary key(Order_id),
	Customer_id int(11),
	Product_id int(11), 
	Payment_id int(11)
)";


if(mysql_query($sqlCommand))
	echo "Your ORDER table has been created successfully.";
else
	echo "ERROR! ORDER table has not been created.";

if(mysql_query("Alter table ORDERS
add constraint customer_fk foreign key(customer_id) references CUSTOMER(Customer_id),
add constraint product_fk foreign key(product_id) references PRODUCT(Product_id),
add constraint payment_fk foreign key(payment_id) references PAYMENT(Payment_id)"))

echo "Table altered successfully.";

else
	die(mysql_error()); 




?>