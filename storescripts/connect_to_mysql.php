<?php
 $conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 

 if (!$conn) 
 {
   $m = oci_error();
   echo $m['message'], "\n";
   exit; 
} 
else 
{
  // print "Connected to Oracle!";
} 
   oci_close($conn); 
   

?>