<?php
#		print1.php  打印送检物品清单页面
#		2016年1月21日, PM 12:38:58

	require_once 'header.php';		//包含头文件
	
	if(!isset($_SESSION['flag'])){
		die('无权限');
	}
	$caseid = secget($_GET,'caseid');
	
	//抽取检测权限需要数据
	$sql = $database->get("cases", array("casedist","casedep","userid"),array('caseid' => $caseid));
	if(empty($sql)){
		die('错误的参数！');
	}
	
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
	$samples = $database->select('samples', array('[>]persons' => 'personid'),array('persons.personname','samples.sampletype','samples.samplemodel','samples.sampleunique','samples.samplesth'),$joinwhere);
	//var_dump($database->error());
	//print_r($samples);

	if(!empty($samples)){
		echo "<table id='print'><caption>送检物品清单</caption>";
		//以下循环运行一次，生成表的标题列
		foreach($samples as $key => $value){
			echo '<tr>';
			foreach($value as $key2 => $value2){
				$a = entochs($key2);
				echo '<th>',$a,'</th>';
			}
			echo '</tr>';
			break;
		}
		//以下循环每个案件生成一行
		foreach($samples as $value){
			echo '<tr>';
			foreach($value as $key2 => $value2){
				if($key2 == 'personname' and empty($value2)){
					echo "<td>不明人员</td>";
				}else{
					echo '<td>',$value2,'</td>';
				}
				
			}
			echo '</tr>';
		}
		echo "<tr><td>送检人签名</td><td colspan='4'></td></tr>";
		echo "<tr><td style='height:50px;'>领回人签名</td><td colspan='4'></td></tr>";
		echo "<tr><td style='width: 111px;border:none;'></td><td style='width: 110px;border:none;'></td><td style='width: 145px;border:none;'></td><td style='width: 163px;border:none;'></td><td style='width: 72px;border:none;'></td></tr>";
		echo '</table>';
	}
?>