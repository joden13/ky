<?php
	#		存放位置
	#		2016年4月5日, AM 10:18:25
	
	require_once 'header.php';
	require_once 'check.php';

	
	if($_SESSION['flag'] != 9){
		header("Location: http://$host/login.php");
		exit;
	}
	$caseid = secget($_GET,'caseid');
?>
<fieldset>
<legend>案件审核</legend>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<p>存放位置: <input type="text" name="location" /></p>
	<input type="submit" value="提交" />
</form>
</fieldset>
<?php
	require_once 'footer.php';
	if(!empty($_POST)){
		if(!empty($_POST['location'])){
			$_POST['location'] = sec('id',$_POST['location']);
		}
		echo $reid = $database->update("cases", $_POST,array("caseid" => $caseid));
		header("Location: http://$host/main.php");
		exit;
	}
?>