<?php
	require_once 'header.php';			//包含头文件
	include 'page.php';

	$total = $database->count("cases");		//记录总条数
	$showrow = 10; //一页显示的行数
	$curpage = empty($_GET['page']) ? 1 : $_GET['page']; //当前的页,还应该处理非数字的情况
	if (!empty($_GET['page']) && $total != 0 && $curpage > ceil($total / $showrow)){
		$curpage = ceil($total_rows / $showrow); //当前页数大于最后页数，取最后一页
	}
	//获取数据
	$sql = $database->select("cases", array("caseid","caseno","casename","casedate","casedist","casedep","casedep2","worker","location","reportid","jobstatus","casecheck","remarks"),array("LIMIT" => array(($curpage - 1) * $showrow,$showrow)));
	var_dump($database->error()); 
	
	
	$url = "?page={page}"; //分页地址，如果有检索条件 ="?page={page}&q=".$_GET['q']

	
	if ($total > $showrow) {//总记录数大于每页显示数，显示分页
		$page = new page($total, $showrow, $curpage, $url, 2);
		echo $page->myde_write();
	}
	
	


?>
<a href='download/ACDSee.exe'>下载</a>