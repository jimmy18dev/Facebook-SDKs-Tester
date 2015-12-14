<?php
session_start();
if(!empty($_SESSION['AppID']) && !empty($_SESSION['AppSecret'])){
	$_SESSION['AppID'] 		= $_POST['AppID'];
	$_SESSION['AppSecret'] 	= $_POST['AppSecret'];
}else{
	$_SESSION['AppID'] 		= $_POST['AppID'];
	$_SESSION['AppSecret'] 	= $_POST['AppSecret'];
}

header("location:index.php");
exit(0);
?>