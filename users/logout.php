<?php
session_start();
include('../inc/config.inc.php');

if(!isset($_SESSION['username']))
{
	$url = BASE_URL . 'home.php';
	header('Location: $url');
	exit();
}else{
	$_SESSION=array();
	session_destroy();
	setcookie(session_name(), time()-300);
	$url=BASE_URL . 'index.php';
	ob_end_clean();
	header("Location: $url");
}

?>