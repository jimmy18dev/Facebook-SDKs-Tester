<?php
session_start();
$_SESSION['AppID'] 		= '';
$_SESSION['AppSecret'] 	= '';

header("location:index.php");
exit(0);
?>