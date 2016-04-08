<?php
#		删除涉案人员页面
#		2016年1月11日, AM 09:02:42

	require_once 'header.php';
	require_once 'check.php';				//

	
	/*//过滤get参数
	if(!isset($_GET['personid'])||count($_GET) >= 2){
		die('非法参数');
	}else {
		//对变量$_GET['caseid']进行过滤，只匹配数字
		$_GET['personid'] = secnum($_GET['personid']);
		if(empty($_GET['personid'])){
			die('非法参数');
		}
	}*/
	//检测get变量是否合法
	$personid = secget($_GET,'personid');
	
	
	$caseid = $database->get("persons", array("caseid"),array('personid' => $personid));
	//权限检测，最高权限则直接删除
	if( $_SESSION['flag'] == 9 ){
		$database->delete("persons",array("personid" => $personid));
		header("Location: http://$host/newperson.php?caseid=".$caseid['caseid']);
		exit;
	//flag=3是县级网安权限，可对本县区下的所有案件中的涉案人员进行删除操作
	}else if( $_SESSION['flag'] == 3 ){
		$dist = $database->get("cases", array("casedist"),$caseid);
		if($_SESSION['dist'] == $dist['casedist']){
			$database->delete("persons",array("personid" => $personid));
			header("Location: http://$host/newperson.php?caseid=".$caseid['caseid']);
			exit;
		}else{
			die("你没有权限！");
		}
	//用户只能删除自己录入的案件内的人员
	}else if($_SESSION['flag'] < 3){
		$userid = $database->get("cases", array("userid"),$caseid);
		if($_SESSION['userid'] == $userid['userid']){
			$database->delete("persons",array("personid" => $personid));
			header("Location: http://$host/newperson.php?caseid=".$caseid['caseid']);
			exit;
		}else{
			die("你没有权限！");
		}
	}else{
		die("something wrong!");
	}
		

	
	
?>