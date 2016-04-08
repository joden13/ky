<?php
	#		用户审核
	#		2016年1月9日, PM 06:41:56
	
	require_once 'header.php';			
	require_once 'check.php';

	
	if($_SESSION['flag'] != 9){
		header("Location: http://$host/login.php");
		exit;
	}
	
	//生成未审核用户表
	$sql = $database->select("users",array('userid','username','userdist','userdep','userdep2','userflag','usercheck'),array("ORDER" => 'userid'));
	echo "<table border=\"1\">";
	//以下循环运行一次，生成表的标题列
	foreach($sql as $key => $value){
		echo "<tr>";
		foreach($value as $key2 => $value2){
			$a = entochs($key2);
			echo '<th>',$a,'</th>';
		}
		echo '<th>操作</th><th>重置密码</th></tr>';
		break;
	}
	//以下循环每个案件生成一行
	foreach($sql as $value){
		echo "<tr>";
		foreach($value as $key2 => $value2){
			echo '<td>',$value2,'</td>';
		}
		if($value['usercheck'] == 0){
			echo "<td><a href='http://",$host,"/usermanager.php?userid=",$value['userid'],"'>审核</a></td>";
		}else{
			echo "<td>已审核</td>";
		}
		echo "<td><a href=\"javascript:if(confirm('确实要重置该用户密码吗?'))location='http://",$host,"/usermanager.php?userp=",$value['userid'],"'\">重置密码</a></td>";
		echo "</tr>";
	}
	echo '</table>';
	echo '<p>点击重置密码，用户密码即被重置为123456.</p>';

	
	
	require_once 'footer.php';
	if(isset($_GET['userid'])&&!empty($_GET['userid'])){
		$_GET['userid'] = sec('num',$_GET['userid']);
		$database->update("users",array("usercheck" => "1"),array("userid" => $_GET['userid']));
		header("Location: http://$host/usermanager.php");
	}
	if(isset($_GET['userp'])&&!empty($_GET['userp'])){
		$_GET['userp'] = sec('num',$_GET['userp']);
		$password = md5('123456kysys');
		$database->update("users",array("password" => $password),array("userid" => $_GET['userp']));
		header("Location: http://$host/usermanager.php");
	}
?>