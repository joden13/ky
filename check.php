<?php
#		检测是否登录，如登录显示用户信息
#		2016年1月2日, PM 11:43:41

	if(empty($_SESSION['flag'])){		//session变量未注册则重定向至登录页。
		header("Location: http://$host/login.php");
		exit;
	}
	echo "<p> <a href='http://",$host,"/main.php'>主页</a>当前用户：",$_SESSION['username']," | 所属地区：",$_SESSION['dist']," | 用户部门：",$_SESSION['dep']," | 用户单位：",$_SESSION['dep2'],"<a href='http://",$host,"/logout.php'>退出登录</a> </p>";
?>