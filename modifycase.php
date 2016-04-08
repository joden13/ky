<?php
#		modifycase.php  修改案件信息
#		2016年1月18日, PM 07:43:11

	require_once 'header.php';
	require_once 'check.php';				//

	
	/*//检测是否已设置变量$_GET['caseid']
	if(!isset($_GET['caseid'])||count($_GET) >= 2){
		die('非法参数0');
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
	//抽取相关案件数据
	$sql = $database->get("cases", array('caseno','casedate','casedist','casedep','casedep2','casename','casedetails','requirement','linkman1','contacts1','linkman2','contacts2','jobstatus','casecheck'),array('caseid' => $caseid));
	//print_r($sql);
	//echo $sql['casedist'];
?>
<html>
<head>
		<title><?php echo XITONG; ?> -- 修改委托案件</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
</head>
<body>
<fieldset>
<legend>修改委托案件 - 所有项目必填</legend>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
<?php
if($_SESSION['flag'] == 9){
	echo slt($distlist,'casedist',$sql['casedist']);		//生存select下拉列表，函数slt()见selectlist.php
	echo slt($deplist,'casedep',$sql['casedep']);
	echo "<p>单位名称：<input type='text' name='casedep2' value=".$sql['casedep2']." />例：刑警大队或城关派出所，仅汉字。</p>";
	}else if($_SESSION['flag'] == 3){		//县区网安大队权限
		echo "<input type='hidden' name='casedist' value=".$_SESSION['dist']." />";
		echo slt($deplist,'casedep',$sql['casedep']);
		echo "<p>单位名称：<input type='text' name='casedep2' value=".$sql['casedist']." />例：刑警大队或城关派出所，仅汉字。</p>";
	}else if($_SESSION['flag'] <= 2){
	echo "<input type='hidden' name='casedist' value=".$_SESSION['dist']." />";
	echo "<input type='hidden' name='casedep' value=".$_SESSION['dep']." />";
	echo "<input type='hidden' name='casedep2' value=".$_SESSION['dep2']." />";
}
?>
<p>案件名称：<input type='text' name='casename' value=<?php echo $sql['casename'];?> /></p>
<p>简要案情：<textarea name='casedetails' cols='50' rows='10'><?php echo stripcslashes($sql['casedetails']);?></textarea></p>
<p>勘验需求：<textarea name='requirement' cols='50' rows='5'><?php echo stripcslashes($sql['requirement']);?></textarea></p>
<p>联系人姓名: <input type='text' name='linkman1' value=<?php echo $sql['linkman1'];?> />仅汉字。 联系方式: <input type='text' name='contacts1' value=<?php echo $sql['contacts1'];?> />仅数字。</p>
<p>联系人姓名: <input type='text' name='linkman2' value=<?php echo $sql['linkman2'];?> />仅汉字。 联系方式: <input type='text' name='contacts2' value=<?php echo $sql['contacts2'];?> />仅数字。</p>
<input type='submit' value='提交' />
</form>
</fieldset>
<?php
	require_once 'footer.php';
	if(!empty($_POST)){
		//print_r($_POST);
		//echo '<br>';
		//把只允许为中文的输入项组成一个数组，为过滤做准备
		$chs = array("casedist" => $_POST['casedist'],"casedep" => $_POST['casedep'],"casedep2" => $_POST['casedep2'],"linkman1" => $_POST['linkman1'],"linkman2" => $_POST['linkman2']);
		//把只允许为数字的输入项组成一个数组，为过滤做准备
		$mob = array("contacts1" => $_POST['contacts1'],"contacts2" => $_POST['contacts2']);
		//把可能含有中文英文数字符号的输入项组成一个数组，为过滤做准备
		$txtarea = array("casename" => $_POST['casename'],"casedetails" => $_POST['casedetails'],"requirement" => $_POST['requirement']);
		/*extract();			//把数组的值赋予key变量：$key= $val
		secchs($chs);
		print_r(secchs($chs));
		secnum($num);
		print_r(secnum($num));
		saddslashes($txtarea);
		print_r(saddslashes($txtarea));
		//print(stripslashes($txtarea['casename']));
		*/
		//对三个数组应用不同的过滤函数后，组成同一个数组
		$secarr = sec('cn',$chs)+sec('mob',$mob)+saddslashes($txtarea);
		//循环检测数组是否有空值，如有输出异常后退出，否则什么也不做
		foreach($secarr as $val){
			if(empty($val)){
				die("请检查是否按照要求填写表单。");
			}
		}
		//print_r($secarr);
		//插入数据库
		$database->update('cases',$secarr,array('caseid' => $caseid));
		//var_dump($database->error());
		//根据插入数据库语句返回的id值跳转到案件详情页面
		header("Location: http://$host/details.php?caseid=$caseid");
		exit;
	}
?>