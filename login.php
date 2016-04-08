<?php
	#login.php登录检测页面
	#2016年1月2日, PM 03:45:01
	#验证用户名密码，如正确验证是否已审核用户，注册session变量之后转入main.php


	require_once 'header.php';				//包含头文件

?>
		<div id='logo'>六安市公安局网安支队电子物证委托勘验登记系统</div>
		<fieldset>
		<legend>用户登录</legend>
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
			<p>用户名: <input type="text" name="username" /></p>
			<p>密&nbsp&nbsp&nbsp码: <input type="password" name="password" /></p>
			<p><input type="submit" value="登录" /><a href="/ky/reg.php">注册</a></p>
		</form>
		<p><b>请使用IE6.0以上浏览器，或使用<a href='download/chrome_installer.exe'>chrome</a>或firefox浏览器登录本系统，否则本系统的各项功能可能无法正常使用。</b><br>忘记密码请致电0564-3378036，由支队重置密码。</p>
		</fieldset>
<?php 
	require_once 'footer.php';
?>



<?php
//检测$_POST的值来判断第一次访问该页面还是表单提交过后
if(!empty($_POST)){
	$username = sec('id',$_POST['username']);
	$password = md5($_POST['password']."kysys");
	$table = 'users';
	$columns = array('userid','password','userflag','usercheck','userdist','userdep','userdep2');
	$where = array('username' => $username);
	$sql_result = $database->get($table, $columns, $where);
	
	//如果用户名为空、sql查询结果为空、密码错误，均输出登录失败信息，并exit
	if(empty($sql_result)||empty($username)||$password != $sql_result['password']){
		print('登录失败，请重新登录！');
		exit;
	}else if($sql_result['usercheck'] == 0){
		echo '用户未审核，请等待支队审核后登录或拨打0564-3378036！';
		exit;
	}
	
	//设置SESSION全局变量flag为用户角色，check为审核标识。
	//session_start();
	$_SESSION['userid'] = $sql_result['userid'];
	$_SESSION['username'] = $username;
	$_SESSION['flag'] = $sql_result['userflag'];
	$_SESSION['usercheck'] = $sql_result['usercheck'];
	$_SESSION['dist'] = $sql_result['userdist'];
	$_SESSION['dep'] = $sql_result['userdep'];
	$_SESSION['dep2'] = $sql_result['userdep2'];
	print_r($_SESSION);
	
	//记录用户IP及时间至日志文件，若第一次登录则创建该用户日志文件
	$date = date("Y-m-d H:i:s");
	$ip = $_SERVER['REMOTE_ADDR'];
	$str = "name:$username|IP:$ip|date:$date\r\n";
	file_put_contents("log/$username.txt",$str,FILE_APPEND);			//可使用echo显示写入字节数进行调试
	
	//header location后面不能直接用$_SERVER['SERVER_NAME']组成链接只能用变量。（原因不明）
	header("Location: http://$host/main.php");
	//var_dump($database->error());      
}
?>