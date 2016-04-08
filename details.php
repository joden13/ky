<?php
#		details.php  显示案件详情页面
#		2016年1月3日, AM 10:39:18

	require_once 'header.php';		//包含头文件
	require_once 'check.php';				//


	/*//检测是否已设置变量$_GET['caseid']
	if(!isset($_GET['caseid'])||count($_GET) >= 2){
		die('非法参数');
	}else {
		//对变量$_GET['caseid']进行过滤，只匹配数字
		$_GET['caseid'] = secnum($_GET['caseid']);
		if(empty($_GET['caseid'])){
			die('非法参数');
		}
		//echo $_GET['caseid'];
	}*/
	//检测get变量是否合法
	$caseid = secget($_GET,'caseid');
	
	
	//查询抽取数据
	$sql = $database->get("cases", array('userid','caseno','casedate','casedist','casedep','casedep2','casename','casedetails','requirement','linkman1','contacts1','linkman2','contacts2','jobstatus','casecheck','remarks'),array('caseid' => $caseid));
	if(empty($sql)){
		die('错误的参数！');
	}
	
	//检测权限
	if($_SESSION['flag'] == 9){
		
	}else if($_SESSION['flag'] == 3){
		if($_SESSION['dist'] != $sql['casedist']){
			die('你没有权限查看该案件！');
		}
	}else if($_SESSION['flag'] == 2 or $_SESSION['flag'] == 1){
		if($_SESSION['userid'] != $sql['userid']){
			die('你没有权限查看该案件！');
		}
	}else{
		die('你的权限有问题！');
	}
	
	
	
	
	//print_r($sql);
	//利用循环将抽取的数据形成表格
	echo "<table>";
	foreach($sql as $key => $val){
		if($key == 'userid'){
			continue; 
		}
		//利用函数将英文的字段名替换为中文
		$a = entochs($key);
		//删除由 addslashes() 函数添加的反斜杠。
		$val = stripcslashes($val);
		//替换jobstatus、casecheck的值为相应的中文状态
		if($key == 'jobstatus'){
			if($val == '2'){
				$val = '物品已领回';
			}else if($val == '1'){
				$val = '勘验已完成';
			}else{
				$val = '勘验未完成';
			}
		}
		if($key == 'casecheck'){
			if($val == '1'){
				$val = '案件已审核';
			}else{
				$val = '案件未审核';
			}
		}
		echo "<tr><th>",$a,"</th><td id='details'>",$val,"</td></tr>";
	}
	echo "</table>";

	//如果案件已审核则不允许新增涉案人员与物品，除非是网安高级用户
	echo "<p><a href='javascript:history.back(-1)'>返回上一页</a>";
	if($sql['casecheck'] == '0' || $_SESSION['flag'] > 3){
		echo "<a href='http://",$host,"/modifycase.php?caseid=",$caseid,"'> 修改案件信息 </a>";
		echo "<a href='http://",$host,"/newperson.php?caseid=",$caseid,"'> 新增涉案人员 </a>";
		echo "<a href='http://",$host,"/newsample.php?caseid=",$caseid,"'> 新增委托物品 </a>";
	}
	if($sql['casecheck'] == '1' || $_SESSION['flag'] > 3){
		echo "<a href='http://",$host,"/showperson.php?caseid=",$caseid,"'> 涉案人员 </a><a href='http://",$host,"/showsample.php?caseid=",$caseid,"'> 委托物品 </a><a href='http://",$host,"/print2.php?caseid=",$caseid,"'> 打印物品清单 </a><a href='http://",$host,"/print1.php?caseid=",$caseid,"'> 打印委托书 </a><a href='http://",$host,"/print3.php?caseid=",$caseid,"'> 打印告知书 </a>";
	}
	echo '</p>';
	require_once 'footer.php';
?>