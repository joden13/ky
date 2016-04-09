<?php
	#header.php		页面头文件
	#2016年1月19日, AM 10:21:10
	
	require_once 'config.php';				//数据库配置文件
	include 'inject_check.php';		//防注入检测文件
	include 'slt.php';			//生成下拉列表文件
	
	//打开session
	session_start();
	$host = $_SERVER['SERVER_NAME'].'/ky';
	define("XITONG","网安支队电子物证勘验登记系统");
?>
<html>
<head>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<title><?php echo XITONG; ?> - Programed By Liu Kaiyang</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<script src="jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
