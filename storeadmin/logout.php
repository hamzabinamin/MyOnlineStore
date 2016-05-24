<?php
if (!isset($_SESSION))
  {
    session_start();
  }
session_destroy();
header("location: admin_login.php");

?>