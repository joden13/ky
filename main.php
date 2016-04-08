<?php
	#		用户主页面
	#		2016年1月8日, AM 12:45:36
	require_once 'header.php';			//包含头文件
	require_once 'check.php';			//登录检测
	include_once 'page.php';			//分页类

	//如果没有get参数，则设置为1，即默认显示第一页
	$_GET['page'] = empty($_GET['page']) ? 1 : $_GET['page']; 
	secget($_GET,'page');
	//以下参数为分页所用
	$total = $database->count("cases");		//记录总条数
	$showrow = 20; //一页显示的行数
	$curpage = empty($_GET['page']) ? 1 : $_GET['page']; //当前的页,还应该处理非数字的情况
	if (!empty($_GET['page']) && $total != 0 && $curpage > ceil($total / $showrow)){
		$curpage = ceil($total_rows / $showrow); //当前页数大于最后页数，取最后一页
	}
	$url = "?page={page}"; //分页地址，如果有检索条件 ="?page={page}&q=".$_GET['q']
	//以上参数为分页所用
	
	$queue = $database->count("cases",array('jobstatus' => '0'));
	
	echo "<p><a href='http://",$host,"/newcase.php'>新增案件</a><a href='http://",$host,"/modifypw.php'>修改密码</a>";
	if($_SESSION['flag'] == '9'){
		echo "<a href=\"http://",$host,"/usermanager.php\">用户管理</a>";
	}echo "还有 $queue 个案件未勘验完成,您的任务正在队列之中。</p>";
	//按照session内的权限变量值判断使用不同的查询语句
	/* if($_SESSION['flag'] == '9'){							//最高权限列出所有案件
		$main = $database->select("cases", array("caseid","caseno","casename","casedate","casedist","casedep","casedep2","worker","location","reportid","jobstatus","casecheck","remarks"),array("LIMIT" => array(($curpage - 1) * $showrow,$showrow)));
	}else if($_SESSION['flag'] == '3'){						//查询条件为县区
		$main = $database->select("cases", array("caseid","caseno","casename","casedate","casedist","casedep","casedep2","worker","location","reportid","jobstatus","casecheck","remarks"),array( 'casedist'=> $_SESSION['dist']));
	}else if($_SESSION['flag'] == '2'){						//查询条件为部门
		$main = $database->select("cases", array("caseid","caseno","casename","casedate","casedist","casedep","casedep2","worker","location","reportid","jobstatus","casecheck","remarks"),array( 'casedep'=> $_SESSION['dep']));
	}else{													//查询条件为当前登录用户ID
		$main = $database->select("cases", array("caseid","caseno","casename","casedate","casedist","casedep","casedep2","worker","location","reportid","jobstatus","casecheck","remarks"),array('userid'=> $_SESSION['userid'],"LIMIT" => array(($curpage - 1) * $showrow,$showrow)));
	} */
	$where = array();
	switch ($_SESSION['flag']) {
		case 9:
			$where = array("LIMIT" => array(($curpage - 1) * $showrow,$showrow));
			break;
		case 3;
			$where = array('casedist'=> $_SESSION['dist'],"LIMIT" => array(($curpage - 1) * $showrow,$showrow));
			break;
		case 2;
			$where = array('casedep'=> $_SESSION['dep'],"LIMIT" => array(($curpage - 1) * $showrow,$showrow));
			break;
		case 1;
			$where = array('userid'=> $_SESSION['userid'],"LIMIT" => array(($curpage - 1) * $showrow,$showrow));
			break;
	}
	$main = $database->select("cases", array("caseid","caseno","casename","casedate","casedist","casedep","casedep2","worker","location","reportid","jobstatus","casecheck","remarks"),$where);
	if(empty($main)){
		die('暂时还没有案件。');
	}
	
	//添加2列显示涉案人员与物品
	$main = inscol($main,'countperson','countsample');
	//print_r($main);
	if(!empty($main)){
		echo "<table id='maintable'>";
		//以下循环运行一次，生成表的标题列
		foreach($main as $key => $value){
			echo "<tr>";
			foreach($value as $key2 => $value2){
				//使用continue; 语句放弃此次循环，从而不显示caseid的值
				if($key2 == 'caseid'){
					continue; 
				}
				
				$a = entochs($key2);
				echo '<th>',$a,'</th>';
			}
			if($_SESSION['flag'] == 9){
				echo '<th>操作</th>';
			}
			echo '</tr>';
			break;
		}
		//以下循环每个案件生成一行
		foreach($main as $value){
			echo "<tr>";
			foreach($value as $key2 => $value2){
				//使用continue; 语句放弃此次循环，从而不显示caseid的值
				if($key2 == 'caseid'){
					continue; 
				}
				//给案件名称加上直达案件详细页面的链接
				if($key2 == 'casename'){
					$value2 = stripcslashes($value2);
					//将案件全名保存至临时变量title
					$title = $value2;
					//将太长的数据(超过8个汉字)的数据进行截断显示
					if(strlen($value2) > 24){
						$value2 = cut($value2);
					}
					$value2 = "<a href='http://".$host."/details.php?caseid=".$value['caseid']."' title='$title'>$value2</a>";
				}
				//如果存放位置没有值则显示链接
				if($key2 == 'location'){
					if($value2 == null){
						$value2 = "<a href='http://".$host."/location.php?caseid=".$value['caseid']."'>位置</a>";
					}else{
						$value2 = "<a href='http://".$host."/location.php?caseid=".$value['caseid']."'>$value2</a>";
					}
				}
				
				
				//替换jobstatus、casecheck的值为相应的中文状态
				if($key2 == 'jobstatus'){
					if($value2 == '2'){
						$value2 = '物品已领回';
					}else if($value2 == '1'){
						$value2 = '勘验已完成';
					}else{
						$value2 = '勘验未完成';
					}
				}
				if($key2 == 'casecheck'){
					if($value2 == '1'){
						$value2 = '已审核';
					}else{
						$value2 = '未审核';
					}
				}
				if($key2 == 'countperson'){
					$value2 = $database->count("persons",array("caseid" => $value['caseid']));
					$value2 = "<a href=\"http://".$host."/showperson.php?caseid=".$value['caseid']."\">$value2</a>";
				}
				if($key2 == 'countsample'){
					$value2 = $database->count("samples",array("caseid" => $value['caseid']));
					$value2 = "<a href=\"http://".$host."/showsample.php?caseid=".$value['caseid']."\">$value2</a>";
				}
				if($key2 == 'remarks'){
					if(strlen($value2) > 24){
						$value2 = cut($value2);		//将太长的数据(超过8个汉字)的数据进行截断显示
					}
				}
				echo '<td>',$value2,'</td>';
			}
			if($_SESSION['flag'] == 9){
				echo "<td><a href=\"http://",$host,"/casecheck.php?caseid=",$value['caseid'],"\">审核</a><a href=\"http://",$host,"/finishjob.php?caseid=",$value['caseid'],"\">勘验完成</a></td>";
			}
			echo '</tr>';
		}
		echo "</table>";
	}
	
	
	if ($total > $showrow) {//总记录数大于每页显示数，显示分页
		$page = new page($total, $showrow, $curpage, $url, 2);
		echo $page->myde_write();
	}
	require_once 'footer.php';
?>
