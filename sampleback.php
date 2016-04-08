<?php
#		涉案物品领回信息填写
#		2016年1月11日, PM 04:04:58

	require_once 'header.php';
	require_once 'check.php';				//

	
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
?>
	

<body>
	<fieldset>
	<legend>涉案物品领回</legend>
	<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
		<p>领回人: <input type="text" name="backman" /></p>
		<input type="submit" value="提交" />
	</form>
	</fieldset>
		
<?php
	require_once 'footer.php';

	if(!empty($_POST)){
		//取涉案物品关联的案件序号
		$caseid = $database->get("samples",array("caseid"),array("sampleid" => $sampleid));
		//对领回人输入项进行过滤
		$_POST['backman'] = sec('cn',$_POST['backman']);
		$_POST['backdate'] = date("Y-m-d");
		//更新数据库
		$database->update("samples", $_POST,array("sampleid" => $sampleid));
		header("Location: http://$host/newsample.php?caseid=".$caseid['caseid']);
		exit;
	}
?>