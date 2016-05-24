<?php

require "connect_to_mysql.php";

$sqlCommand = "Create table PRODUCT 
(
	Product_id int(11) not null auto_increment,
	Product_name varchar(255) not null,
	Price varchar(16) not null,
	Color varchar(65) not null,
	Product_details text not null,
	date_added date not null,
	primary key(Product_id),
	unique key Product_name(Product_name),
	Supplier_id int(11),
	Category_id int(11)
)";

if(mysql_query($sqlCommand))
	echo "Your PRODUCT table has been created successfully.";
else
	echo "ERROR! PRODUCT table has not been created.";
	
if(mysql_query("Alter table PRODUCT
add  constraint category_fk foreign key(Category_id) references CATEGORY(Category_id),
add constraint supplier_fk foreign key(Supplier_id) references SUPPLIER(Supplier_id)"))

echo "Table altered successfully.";

else
	die(mysql_error()); 	


?>