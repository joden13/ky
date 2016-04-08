<?php
#		showsample.php  查看涉案物品
#		2016年1月22日, AM 10:47:49

	require_once 'header.php';
	require_once 'check.php';	

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

	//初始化$joinwhere
	$joinwhere = array();
	//连接查询的参数需要加表名
	$joinwhere['samples.caseid'] = $caseid;
	$samples = $database->select('samples', array('[>]persons' => 'personid'),array('persons.personname','samples.sampleid','samples.sampletype','samples.samplemodel','samples.sampleunique','samples.samplesth','samples.backman','samples.backdate'),$joinwhere);



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
			echo "</table>";
		}
	}
	echo "<p><a href='javascript:history.back(-1)'>返回上一页</a>";
	require_once 'footer.php';
?>