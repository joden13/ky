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
		echo '<th>操作</th></tr>';
		break;
	}
	//以下循环每个案件生成一行
	foreach($sql as $value){
		echo "<tr>";
		foreach($value as $key2 => $value2){
			echo '<td>',$value2,'</td>';
		}
		if($value['usercheck'] == 0){
			echo "<td><a href=\"http://",$host,"/usercheck.php?userid=",$value['userid'],"\">审核</a></td></tr>";
		}else{
			echo "<td>已审核</td></tr>";
		}
	}
	echo '</table>';
	

	
	
	require_once 'footer.php';
	
	if(isset($_GET)&&!empty($_GET)){
		$database->update("users",array("usercheck" => "1"),array("userid" => $_GET['userid']));
		header("Location: http://$host/usercheck.php");
	}
?>