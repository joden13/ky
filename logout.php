<?php
	#退出登录
	#2016年1月19日, PM 03:30:15
	
	require_once 'header.php';
	session_destroy();
	header("Location: http://$host/login.php");
?>