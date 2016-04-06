<?php
# reg.php  注册页面
# 填写5个参数：username、password、dist、dep、dep2
# 对用户输入进行防注入检测后如非空则向数据库插入数据。
# 2016年1月2日, PM 01:36:26

require_once 'header.php';				//包含头文件

?>

<fieldset>
<legend>用户注册-所有项目必填</legend>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
<?php
echo "所属地区:";
echo slt($distlist,'dist');		//生存select下拉列表，函数slt()见selectlist.php
echo "所属警种:";
echo slt($deplist,'dep');
?>
<p>单位名称:<input type="text" name="dep2" />某大队或某派出所，例：<b>刑警大队</b>或<b>小南海派出所</b>，不需要带六安市某某区/某某县公安局。</p>
<p>用户名: <input type="text" name="username" />允许字母数字，必须字母开头，4-16个字符。</p>
<p>输入密码: <input type="password" name="password" />大于6个字符。</p>
<p>确认密码: <input type="password" name="repassword" />再一次输入密码。</p>
<input type="submit" value="提交" />
<a href="login.php">返回登录界面</a>
</form>
</fieldset>
<?php 
	require_once 'footer.php';
?>


<?php
if(!empty($_POST)){				//检测是否提交过数据，如第一次访问并未提交数据不运行一下程序
	//密码+kysys算32MD5
	if(strlen($_POST['password']) < 6){
		print('密码过短！');
		exit;
	}else if($_POST['password'] != $_POST['repassword']){
		die('两次输入密码不同');
	}
	$_POST['password'] = md5($_POST['password'].'kysys');
	//输入检测是否空值
	foreach($_POST as $val){
		if(empty($val)){
			print("输入为空！！");
			exit;
		}
	}
	//使用inject_check.php对用户输入进行过滤,仅允许中文。
	$_POST['dist'] = sec('cn',$_POST['dist']);
	$_POST['dep'] = sec('cn',$_POST['dep']);
	$_POST['dep2'] = sec('cn',$_POST['dep2']);	
	$_POST['username'] = sec('id',$_POST['username']);
	
	/*
	if(empty($_POST['username'])){
		print("用户名输入为空或含有非法字符！！");
		exit;
	}else if($password == '4aa850c34905f94399860ec8154cc718'){
		print("请填写密码！！");
		exit;
	}else if(empty($_POST['dist'])){
		print("县区输入为空或含有非法字符！！");
		exit;
	}else if(empty($_POST['dep'])){
		print("部门输入为空或含有非法字符！！");
		exit;
	}else if(empty($_POST['dep2'])){
		print("仅中文，单位输入为空或含有非法字符！！");
		exit;
	}
	*/
	/*
	// 输出各种变量加以调试
	print_r($_POST);
	printf("<br>dist=%s<br>",$_POST['dist']);
	printf("dep=%s<br>",$_POST['dep']);
	printf("dep2=%s<br>",$_POST['dep2']);
	printf("username=%s<br>",$_POST['username']);
	printf("password=%s<br>",$password);
	*/
	//这里应该用has判断的，但是他妈的这个类库的has有问题
	$count = $database->count('users',array("username" => $_POST['username']));
	if($count){
		die('用户名已存在!');
	}
	//	进行数据插入
	$last_user_id = $database->insert("users", array(
		"username" => $_POST['username'],
		"password" => $_POST['password'],
		"userdist" => $_POST['dist'],
		"userdep" => $_POST['dep'],
		"userdep2" => $_POST['dep2']
	));
	if($last_user_id){			//数据插入成功返回userid必不为0，如插入失败返回0，在此作为条件判断插入是否成功。
		printf("恭喜注册成功，你的用户名为：%s，用户县区：%s，用户部门：%s，用户单位：%s，请牢记你的密码。",$_POST['username'],$_POST['dist'],$_POST['dep'],$_POST['dep2']);
		printf("<br><a href=\"http://%s/login.php\">登录</a>",$host);
		unset($_POST);
		exit;
	}
	echo '注册失败，如多次提交均失败，请致电0564-3378036.';
	//var_dump($database->error());		//查看调试信息
	//销毁post数据，防止重复提交。
	unset($_POST);
}
?>
