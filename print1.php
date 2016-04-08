<?php
#		print1.php  打印委托书页面
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
<caption>电子物证勘验委托书</caption>
<tr>
  <th colspan='2'>委托勘验单位</th>
  <td><?php echo $sql['casedist'].$sql['casedep2']; ?></td>
  <th>委托时间</th>
  <td><?php echo $sql['casedate']; ?></td>
</tr>
<tr>
  <th rowspan="2">送检人</th>
  <th class='pth'>姓名</th><td><?php echo $sql['linkman1']; ?></td><th>联系方式</th><td><?php echo $sql['contacts1']; ?></td>
</tr>
<tr>
  <th>姓名</th><td><?php echo $sql['linkman2']; ?></td><th>联系方式</th><td><?php echo $sql['contacts2']; ?></td>
</tr>
  <th colspan='2'>勘验机构名称</th><td colspan='3'>六安市公安局电子物证鉴定实验室</td>
<tr>
  <th colspan='2'>案件名称</th><td><?php echo $sql['casename']; ?></td><th>委托编号</th><td><?php echo $sql['caseno']; ?></td>
</tr>
<tr>
<th colspan='2' class='textarea'>简要案情</th><td colspan='3'><?php echo $sql['casedetails']; ?></td>
</tr>
<tr>
  <th colspan='2' class='textarea'>委托需求</th><td colspan='3'><?php echo $sql['requirement']; ?></td>
</tr>
<tr>
  <th colspan='2'>送检物品封存情况</th><td> </td><th>送检人签字<br>及单位盖章</th><td> </td>
</tr>
<tr>
  <th colspan='2' class='textarea'>双方约定</th><td colspan='3'>1.委托方向我支队介绍的情况客观真实，提交的检材和样本等来源合法可靠；<br>2.依据《刑事诉讼法》相关条款，未发现我支队有回避的情形；<br>3.我支队在任何情况下对与案件相关的信息保密；<br>4.此次勘验，甲方共送检____件检材，其中____件予以受理；<br>5.无特殊情况，委托方自勘验完成九十个工作日内取回检材，否则我支队有权对过期检材进行处理；<br>6.双方对检材及状态已确认。</td>
</tr>
<tr>
   <th colspan='2'>附件</th><td colspan='3'>1.立案决定书复印件 2.受理案件登记表复印件 3.强制措施文书复印件<br>4.送检物品清单 5.呈请实施强制措施文书复印件</td>
</tr>
</table>