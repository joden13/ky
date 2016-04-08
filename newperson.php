<?php
#		newperson.php  新增涉案人员
#		2016年1月7日, AM 09:10:38

	require_once 'header.php';
	require_once 'check.php';				

	
	/*//检测是否已设置变量$_GET['caseid']
	if(!isset($_GET['caseid'])||count($_GET) >= 2){
		die('非法参数');
	}else{
		//对变量$_GET['caseid']进行过滤，只匹配数字
		$_GET['caseid'] = secnum($_GET['caseid']);
		if(empty($_GET['caseid'])){
			die('非法参数');
		}
		//echo $_GET['caseid'];
	}
	*/
	//检测get变量是否合法
	$caseid = secget($_GET,'caseid');
	
	//抽取检测权限需要数据
	$sql = $database->get("cases", array("casedist","casedep","userid"),array('caseid' => $caseid));
	//检测权限
	if($_SESSION['flag'] == 9){
		
	}else if($_SESSION['flag'] == 3){
		if($_SESSION['dist'] != $sql['casedist']){
			die('你没有权限查看该案件！');
		}
	}else if($_SESSION['flag'] == 2 or $_SESSION['flag'] == 1){
		if($_SESSION['userid'] != $sql['userid']){
			die('你没有权限查看该案件！');
		}
	}else{
		die('你的权限有问题！');
	}
	
	
	
	//读取persons表中caseid为$_GET['caseid']的数据
	$persons = $database->select("persons", array("personid","personname","personno","personrole","personsth"),$_GET);
	//print_r($persons);
	
	if(!empty($persons)){
		foreach($persons as $key => $value){
			echo "<table id='showp'>";
			foreach($value as $key2=>$value2){
				if($key2 == 'personid'){
						continue; 
				}
				$a = entochs($key2);
				echo "<tr><th>",$a,"</th><td>",$value2,"</td></tr>";
				}
			echo "<tr><th>操作</th><th><a href=\"javascript:if(confirm('确实要删除该内容吗?'))location='http://",$host,"/delperson.php?personid=",$value['personid'],"'\">删除</a></th></tr>";
			echo "</table>";
		}
	}


?>
<fieldset>
<legend>新增涉案人员</legend>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
<p>涉案人员姓名：<input type="text" name="personname" />必填</p>
<p>涉案人员身份证号码：<input type="text" name="personno" />必填</p>
<p>涉案人员角色：
<select name="personrole">
  <option value ="主犯">主犯</option>
  <option value ="从犯">从犯</option>
  <option value="受害人">受害人</option>
  <option value="证人">证人</option>
  <option value="其他涉案人员">其他涉案人员</option>
</select>必填</p>
<p>备注：<textarea type="text" name="personsth"/></textarea>如掌握嫌疑人虚拟身份信息，请填入此处。</p>
<input type="submit" value="提交" />
</form>
</fieldset>
<?php 
	echo "<p><a href='javascript:history.back(-1)'>返回上一页</a><a href='http://",$host,"/newsample.php?caseid=",$caseid,"'>新增委托物品</a></p>";
	require_once 'footer.php';
	
	
	if(!empty($_POST)){
		//$chs = secchs(array("personname" => $_POST['personname'],"personrole" => $_POST['personrole'],"personsth" => $_POST['personsth']));
		//$_POST['personno'] = secid($_POST['personno']);
		//$persth = array_pop($chs);		//把备注字段删除
		//检测必填项目
		foreach($_POST as $key => $val){
			if($key == 'personname'||$key == 'personrole'){
				$val = sec('cn',$val);
			}else if($key == 'personsth'){
				$val = saddslashes($val);
			}else if($key == 'personno'){
				$val = sec('sfz',$val);
			}
		}
		$_POST['caseid'] = $_GET['caseid'];
		//print_r($_POST);
		$last_user_id = $database->insert("persons", $_POST);
		//重定向至本页面，显示刚刚提交的数据
		header("Location: http://$host//newperson.php?caseid=$caseid");
		//销毁post数据，防止重复提交。
		unset($_POST);
	}
?>