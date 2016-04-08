<?php
	#		案件审核
	#		2016年1月10日, PM 06:13:39
	
	require_once 'header.php';
	require_once 'check.php';

	
	if($_SESSION['flag'] != 9){
		header("Location: http://$host/login.php");
		exit;
	}
	
	/* //检测是否已设置变量$_GET['caseid']
	if(!isset($_GET['caseid'])||count($_GET) >= 2){
		die('非法参数');
	}else {
		//对变量$_GET['caseid']进行过滤，只匹配数字
		$_GET['caseid'] = secnum($_GET['caseid']);
		if(empty($_GET['caseid'])){
			die('非法参数');
		}
		//echo $_GET['caseid'];
	}
	$caseid = $_GET['caseid'];
	//$sql = $database->update("cases",array("casecheck" => "1"),array("caseid" => $caseid));
	//header("Location: http://$host/main .php"); */
	
	$caseid = secget($_GET,'caseid');
?>

<fieldset>
<legend>案件审核</legend>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<p>备注: <textarea type="text" name="remarks" cols="50" rows="5"></textarea>如不符合要求，请将需修改的要求填写在此。</p>
	<p>勘验人：
	<select name="worker">
	  <option value ="" selected="selected">请选择...</option>
	  <option value ="张镇宁">张镇宁</option>
	  <option value ="涂安元">涂安元</option>
	  <option value ="李先捷">李先捷</option>
	  <option value ="刘开阳">刘开阳</option>
	</select></p>
	<p>审核操作：
	<select name="casecheck">
	  <option value ="0" selected="selected">请选择...</option>
	  <option value ="0">审核不通过</option>
	  <option value ="1">审核通过</option>
	</select></p>
	<input type="submit" value="提交" />
</form>
</fieldset>	
<?php
	require_once 'footer.php';
	if(!empty($_POST)){
		if(!empty($_POST['worker'])){
			$_POST['worker'] = sec('cn',$_POST['worker']);
		}
		$_POST['casecheck'] = $_POST['casecheck']?1:0;
		$_POST['remarks'] = saddslashes($_POST['remarks']);
		echo $reid = $database->update("cases", $_POST,array("caseid" => $caseid));
		header("Location: http://$host/main.php");
		
		exit;
	}
?>
