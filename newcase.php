<?php
#		input_cases.php  新增委托
#		2016年1月3日, AM 10:39:18

	require_once 'header.php';
	require_once 'check.php';				//数据库配置文件

?>
<fieldset>
<legend>新增委托案件 - 所有项目必填</legend>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
<?php
if($_SESSION['flag'] == 9){
	echo slt($distlist,'casedist');		//生存select下拉列表，函数slt()见selectlist.php
	echo slt($deplist,'casedep');
	echo "<p>单位名称：<input type='text' name='casedep2' />例：刑警大队或城关派出所，仅汉字。</p>";
	}else if($_SESSION['flag'] == 3){
		echo "<input type='hidden' name='casedist' value=".$_SESSION['dist']." />";
		echo slt($deplist,'casedep','请选择部门...');
		echo "<p>单位名称：<input type='text' name='casedep2' />例：刑警大队或城关派出所，仅汉字。</p>";
	}else if($_SESSION['flag'] <= 2){
	echo "<input type='hidden' name='casedist' value=".$_SESSION['dist']." />";
	echo "<input type='hidden' name='casedep' value=".$_SESSION['dep']." />";
	echo "<input type='hidden' name='casedep2' value=".$_SESSION['dep2']." />";
}
?>

<p>案件名称：<input type='text' name='casename' /></p>
<p>简要案情：<textarea name='casedetails' cols='50' rows='10'>请输入简要案情。</textarea></p>
<p>勘验需求：<textarea name='requirement' cols='50' rows='5'>请输入勘验需求。</textarea></p>
<p>联系人姓名: <input type='text' name='linkman1' /> 联系方式: <input type='text' name='contacts1' />手机号码。</p>
<p>联系人姓名: <input type='text' name='linkman2' /> 联系方式: <input type='text' name='contacts2' />手机号码。</p>
<input type='submit' value='提交' />
</form>
</fieldset>
<?php 
	require_once 'footer.php';

	if(!empty($_POST)){
		$date = date("Y-m-d");
		//print_r($_POST);
		//echo '<br>';
		//循环检测数组是否有空值，如有输出异常后退出，否则什么也不做
		foreach($_POST as $val){
			if(empty($val)){
				die("请完整填写表单!");
			}
		}
		//把只允许为中文的输入项组成一个数组，为过滤做准备
		$chs = array("casedist" => $_POST['casedist'],"casedep" => $_POST['casedep'],"casedep2" => $_POST['casedep2'],"linkman1" => $_POST['linkman1'],"linkman2" => $_POST['linkman2']);
		//把只允许为电话号码的输入项组成一个数组，为过滤做准备
		$mob = array("contacts1" => $_POST['contacts1'],"contacts2" => $_POST['contacts2']);
		//把可能含有中文英文数字符号的输入项组成一个数组，为过滤做准备
		$txtarea = array("casename" => $_POST['casename'],"casedetails" => $_POST['casedetails'],"requirement" => $_POST['requirement']);
		//对三个数组应用不同的过滤函数后，组成同一个数组
		$secarr = sec('cn',$chs)+sec('mob',$mob)+saddslashes($txtarea);
		//把时间项加入数组
		$secarr = array_merge($secarr,array("casedate" => $date));
		//把用户ID加入数组
		$secarr['userid'] = $_SESSION['userid'];
		//生成caseno，并加入数组
		$year = date('Y');			//大写为4位年份
		//本年度已有数字+1为新的编号
		$caseno = $database->count("cases",array("MATCH" => array("columns" => "casedate","keywoed" => "$year"))) + 1;
		$secarr['caseno'] = 'WT-'.$year.'-'."$caseno";
		
		//print_r($secarr);
		//插入数据库
		$last_user_id = $database->insert("cases", $secarr);
		//var_dump($database->error());
		unset($_POST);
		//根据插入数据库语句返回的id值跳转到案件详情页面
		header("Location: http://$host/details.php?caseid=$last_user_id");
		exit;
	}
?>