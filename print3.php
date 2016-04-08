<?php
#		print3.php  打印告知书页面
#		2016年1月21日, PM 12:38:58

	require_once 'header.php';		//包含头文件
	
	if(!isset($_SESSION['flag'])){
		die('无权限');
	}

	
	$caseid = secget($_GET,'caseid');
	//查询抽取数据
	$sql = $database->get("cases", array('userid','caseno','casedate','casedist','casedep','casedep2','casename','casedetails','requirement','linkman1','contacts1','linkman2','contacts2'),array('caseid' => $caseid));
		//检测权限
	if($_SESSION['flag'] == 9){
		
	}else if($_SESSION['flag'] == 3){
		if($_SESSION['dist'] != $sql['casedist']){
			die('你没有权限查看该案件！1');
		}
	}else if($_SESSION['flag'] == 2 or $_SESSION['flag'] == 1){
		if($_SESSION['userid'] != $sql['userid']){
			die('你没有权限查看该案件！');
		}
	}else{
		die('你的权限有问题！');
	}
?>	
<table id="print">
<caption>操作告知书</caption>
<tr>

  <td colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;委托方：<?php echo $sql['casedist'].$sql['casedep'].$sql['casedep2']; ?></td>
 
  <td>&nbsp;&nbsp;&nbsp;&nbsp;委托时间：<?php echo $sql['casedate']; ?></td>
</tr>
<tr>
<td style="height:300px;" colspan='3'><p>&nbsp;&nbsp;&nbsp;&nbsp;您方委托的“<?php echo $sql['casename']; ?>”电子物证检查勘验项目，已于<?php echo $sql['casedate']; ?> 开始受理。因送检检材中有笔记本电脑、平板电脑、手机等智能移动终端产品，在我方检验过程中需要对这些检材进行破解，以获得委托方所需要的数据，但是破解过程会有失败，可能会造成检材损坏无法正常使用或数据丢失等结果，为保障客户权益，特与贵方协商，是否允许我方对检材进行破解操作。</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;同时，也请对我方的工作给予建议和意见，以有利于我们不断寻求改进。<br>
&nbsp;&nbsp;&nbsp;&nbsp;谢谢合作！<br><br><br><br><br></p>
<p style="text-align:right;">六安市公安局电子物证检验鉴定实验室（公章）<br>

年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>

地址：六安市佛子岭路与梅山南路交叉口110指挥中心。    电话\传真：0564-3378036</td>
  
</tr>
<tr style="height:300px;">
  <td style="text-align:center;">委托方意见</td><td style="width:120px; border-right: 0;"></td><td style="text-align:right; border-left: 0;"><br><br><br><br><br><br><br><br><br><br>（盖章）<br>年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日</td>
</tr>

</table>