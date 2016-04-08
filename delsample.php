<?php
#		删除涉案物品页面
#		2016年1月11日, PM 01:50:50

	require_once 'header.php';
	require_once 'check.php';
	
	/*//过滤get参数
	if(!isset($_GET['sampleid'])||count($_GET) >= 2){
		die('非法参数');
	}else {
		//对变量$_GET['caseid']进行过滤，只匹配数字
		$_GET['sampleid'] = secnum($_GET['sampleid']);
		if(empty($_GET['sampleid'])){
			die('非法参数');
		}
	}*/
	//检测get变量是否合法
	$sampleid = secget($_GET,'sampleid');
	//查询物品的案件ID
	$caseid = $database->get("samples",array("caseid"),array('sampleid' => $sampleid));
	//如果最高权限直接删除
	if( $_SESSION['flag'] == 9 ){
		$database->delete("samples",array("sampleid" => $sampleid));
		header("Location: http://$host/newsample.php?caseid=".$caseid['caseid']);
		exit;
	//flag=3是县级网安权限，可对本县区下的所有案件中的涉案物品进行删除操作
	}else if( $_SESSION['flag'] == 3 ){
		$dist = $database->get("cases", array("casedist"),$caseid);
		if($_SESSION['dist'] == $dist['casedist']){
			$database->delete("samples",array("sampleid" => $sampleid));
			header("Location: http://$host/newsample.php?caseid=".$caseid['caseid']);
			exit;
		}else{
			die("你没有权限！");
		}
	//用户只能删除自己录入的案件内的物品
	}else if($_SESSION['flag'] < 3){
		$userid = $database->get("cases", array("userid"),$caseid);
		if($_SESSION['userid'] == $userid['userid']){
			$database->delete("samples",array("sampleid" => $sampleid));
			header("Location: http://$host/newsample.php?caseid=".$caseid['caseid']);
			exit;
		}else{
			die("你没有权限！");
		}
	}else{
		die("something wrong!");
	}
