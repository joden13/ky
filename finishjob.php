<?php
	#		完成勘验，填写相关信息
	#		2016年1月10日, PM 07:46:44
	
	require_once 'header.php';
	require_once 'check.php';

	
	if($_SESSION['flag'] != 9){
		die('你没有权限！');
	}
	
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
?>


		<fieldset>
		<legend>完成勘验</legend>
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
			<p>报告文号: <input type="text" name="reportid" />不需出具报告的，请留空提交。</p>
			<p>勘验人：
			<select name="worker">
			  <option value ="" selected="selected">请选择...</option>
			  <option value ="张镇宁">张镇宁</option>
			  <option value ="涂安元">涂安元</option>
			  <option value ="李先捷">李先捷</option>
			  <option value ="刘开阳">刘开阳</option>
			</select></p>
			<p>任务状态：
			<select name="jobstatus">
			  <option value ="0" selected="selected">请选择...</option>
			  <option value ="0">勘验未完成</option>
			  <option value ="1">勘验已完成</option>
			  <option value ="2">完成已领回</option>
			</select></p>
			<input type="submit" value="提交" />
		</form>
		</fieldset>
<?php
	require_once 'footer.php';

	if(!empty($_POST)){
		$_POST['reportid'] = saddslashes($_POST['reportid']);
		if(empty($_POST['reportid'])){
			$_POST['reportid'] = '不需出具报告文书';
		}
		if(!empty($_POST['worker'])){
			$_POST['worker'] = sec('cn',$_POST['worker']);
		}
		$_POST['jobstatus'] = sec('num',$_POST['jobstatus']);
		echo $reid = $database->update("cases", $_POST,array("caseid" => $caseid));
		//var_dump($database->error());	
		header("Location: http://$host/main.php");
		exit;
	}
	
?>
