<?php
#		newperson.php  新增物品
#		2016年1月7日, AM 09:10:38

	require_once 'header.php';
	require_once 'check.php';				//

	
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
	}*/
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
	$persons = $database->select("persons", array("personid","personname"),array('caseid' => $caseid));
	//var_dump($database->error());
	
	//初始化$joinwhere
	$joinwhere = array();
	//连接查询的参数需要加表名
	$joinwhere['samples.caseid'] = $caseid;
	//内连接查询表samples与表persons，select语句如下
	// SELECT
	//     'persons'.'personname',
	//     'psamples'.'sampletype',
	//     'samples'.'samplemodel','samples'.'sampleunique','samples'.'samplesth'
	// FROM 'samples'
	// INNER JOIN 'persons' ON 'amples'.'personid = 'persons'.'personid'
	// WHERE
	//     'samples'.'personid' = $joinwhere
	$samples = $database->select('samples', array('[>]persons' => 'personid'),array('persons.personname','samples.sampleid','samples.sampletype','samples.samplemodel','samples.sampleunique','samples.samplesth','samples.backman','samples.backdate'),$joinwhere);
	//var_dump($database->error());
	//print_r($samples);


	if(!empty($samples)){
		foreach($samples as $key => $value){
			echo "<table id='shows'>";
			foreach($value as $key2=>$value2){
				if($key2 == 'sampleid'){
					continue;
				}
				$a = entochs($key2);
				if($key2 == 'personname' and empty($value2)){
					echo "<tr><th>",$a,"</th><td>不明人员</td></tr>";
				}else{
					echo "<tr><th>",$a,"</th><td>",stripcslashes($value2),"</td></tr>";
				}
			}
			echo "<tr><th>操作</th><td><a href=\"javascript:if(confirm('确实要删除该内容吗?'))location='http://",$host,"/delsample.php?sampleid=",$value['sampleid'],"'\">删除</a></td></tr>";
			//只有支队权限可更新领回信息
			if($_SESSION['flag'] == 9){
				echo "<tr><th>操作</th><td><a href=\"http://",$host,"/sampleback.php?sampleid=",$value['sampleid'],"\">领回</td></tr>";
			}
			echo "</table>";
		}
	}
?>
<fieldset>
<legend>新增涉案物品 -- 所有项目必填</legend>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
<p>涉案物品类型：
<select name="sampletype">
  <option value ="" selected="selected">请选择类别...</option>
  <option value ="手机">手机</option>
  <option value ="台式电脑">台式电脑</option>
  <option value="笔记本电脑">笔记本电脑</option>
  <option value="平板电脑">平板电脑</option>
  <option value="U盘或其他移动存储">U盘或其他移动存储</option>
  <option value="其他">其他</option>
</select></p>
<p>涉案物品持有人：
<?php
	//遍历二维数组$persons，将该案件中所有涉案人员姓名生成下拉列表
	if(!empty($persons)){
		echo "<select name=\"personid\">";
		echo "<option value =\"\" selected=\"selected\">不明</option>";
		foreach($persons as $value){
			echo "<option value =",$value['personid'],">",$value['personname'],"</option>";
		}
		echo "</select>";
	}
?>
</p>
<p>涉案物品品牌型号：<input type="text" name="samplemodel" /></p>
<p>涉案物品唯一识别码（如手机串号或物品S/N号）：<input type="text" name="sampleunique" /></p>
<p>备注（如送检物品为手机请在此填写手机号码,其他物品请填写外观特征等信息）：<input type="text" name="samplesth" /></p>
<input type="submit" value="提交" />
</form>
</fieldset>
<?php
	echo "<p><a href='javascript:history.back(-1)'>返回上一页</a><a href=\"http://",$host,"/newsample.php?caseid=",$caseid,"\">新增委托物品</a><a href='http://",$host,"/main.php'>完成</a></p>";
	require_once 'footer.php';
	
	
	if(!empty($_POST)){
		//检测必填项目
		foreach($_POST as $key => $val){
			if($key == 'sampletype'){
				$val = sec('cn',$val);
			}else if($key == 'personid'){
				if($val != ''){
					$val = sec('num',$val);
				}
			}else{
				$val = saddslashes($val);
				if(empty($val)){
					die("非法输入。");
				}
			}
		}
		$_POST['caseid'] = $_GET['caseid'];
		//print_r($_POST);
		$last_user_id = $database->insert("samples", $_POST);
		//var_dump($database->error());
		//重定向至本页面，显示刚刚提交的数据
		//header("Location: http://$host//newsample.php?caseid=$caseid");
		echo "<script language=JavaScript> location.replace(location.href);</script>";	//js刷新方法
		//销毁post数据，防止重复提交。
		unset($_POST);
	}
	
?>