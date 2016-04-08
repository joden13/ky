<?php
#		modifypw.php  修改密码
#		2016年2月4日, PM 02:48:37

	require_once 'header.php';
	require_once 'check.php';
	
	$userid = $_SESSION['userid']
?>
<fieldset>
<legend>修改用户密码</legend>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
	<p>原密码: <input type="text" name="oldpw" /></p>
	<p>输入新密码: <input type="text" name="newpw" /></p>
	<p>确认新密码: <input type="text" name="renewpw" /></p>
	<input type="submit" value="提交" />
</form>
</fieldset>
<?php
	require_once 'footer.php';
	if(!empty($_POST)){
		foreach($_POST as $value){
			if(empty($value)||strlen($value) < 6){
				die('密码不能为空或少于6个字符。');
			}
		}
		$password = md5($_POST['oldpw'].'kysys');
		$password1 = $database->get('users',array("password"),array("userid" => $userid));
		if($password != $password1['password']){
			die('原密码不正确。');
		}
		if($_POST['newpw'] != $_POST['renewpw']){
			die('两次输入的新密码并不相同。');
		}
		if($password == $password1['password'] and $_POST['newpw'] == $_POST['renewpw']){
			$newpassword = md5($_POST['newpw'].'kysys');
			$database->update("users", array("password" => $newpassword),array("userid" => $userid));
			var_dump($database->error());
		}else{
			echo 'something wrong!';
		}
		session_destroy();
		header("Location: http://$host/login.php");
	}