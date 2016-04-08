<?php
$distlist = array('市直','金安区','裕安区','开发区','叶集区','霍邱县','霍山县','舒城县','金寨县','其他');

$deplist = array('刑侦','经侦','治安','国保','网安','派出所','交警','其他');


//$array a为标签中value的值  value为选项值；name为select name的值；choose为默认选项的值
function slt($array, $name, $choose='') {
    $slt = "<select name=\"$name\"\">";
	$selected = 0;
    foreach($array as $val) {
		if($val == $choose){
			$slt .= "<option value=\"$val\" selected=\"selected\">$choose</option>";
			$selected++;
		}else{
			$slt .= "<option value=\"$val\">$val</option>";
		}
	}
	if($selected == 0){
		$slt .= "<option value=\"\" selected=\"selected\">请选择...</option></select>";
	}else{
		$slt .= "</select>";
	}
    return $slt;
}

function entochs($a){
	switch ($a) {
				case 'caseid':
					$a = '序号';
					return $a;
					break;
				case 'caseno':
					$a = '委托编号';
					return $a;
					break;
				case 'casedate':
					$a = '委托时间';
					return $a;
					break;
				case 'casedist':
					$a = '所属地区';
					return $a;
					break;
				case 'casedep':
					$a = '所属部门';
					return $a;
					break;
				case 'casedep2':
					$a = '所属单位';
					return $a;
					break;
				case 'casename':
					$a = '案件名称';
					return $a;
					break;
				case 'casedetails':
					$a = '简要案情';
					return $a;
					break;
				case 'location':
					$a = '存放位置';
					return $a;
					break;
				case 'reportid':
					$a = '报告文号';
					return $a;
					break;
				case 'requirement':
					$a = '勘验需求';
					return $a;
					break;
				case 'linkman1':
					$a = '联系人';
					return $a;
					break;
				case 'contacts1':
					$a = '联系方式';
					return $a;
					break;
				case 'linkman2':
					$a = '联系人';
					return $a;
					break;
				case 'contacts2':
					$a = '联系方式';
					return $a;
					break;
				case 'jobstatus':
					$a = '任务状态';
					return $a;
					break;
				case 'casecheck':
					$a = '审核状态';
					return $a;
					break;
				case 'remarks':
					$a = '备注';
					return $a;
					break;
				case 'worker':
					$a = '勘验人';
					return $a;
					break;
				case 'caseno':
					$a = '送检编号';
					return $a;
					break;
				//person表
				case 'personname':
					$a = '涉案人员姓名';
					return $a;
					break;
				case 'personno':
					$a = '涉案人员身份证';
					return $a;
					break;
				case 'personrole':
					$a = '涉案人员角色';
					return $a;
					break;
				case 'personsth':
					$a = '备注';
					return $a;
					break;
				//sample表
				case 'sampletype':
					$a = '涉案物品类型';
					return $a;
					break;
				case 'samplemodel':
					$a = '涉案物品品牌型号';
					return $a;
					break;
				case 'sampleunique':
					$a = '涉案物品唯一识别码';
					return $a;
					break;
				case 'samplesth':
					$a = '备注';
					return $a;
					break;
				case 'backman':
					$a = '领回人';
					return $a;
					break;
				case 'backdate':
					$a = '领回时间';
					return $a;
					break;
				//main页面 统计人员物品数量
				case 'countperson':
					$a = '涉案人员';
					return $a;
					break;
				case 'countsample':
					$a = '涉案物品';
					return $a;
					break;
				//users表字段
				case 'userid':
					$a = '用户序号';
					return $a;
					break;
				case 'username':
					$a = '用户名';
					return $a;
					break;
				case 'userdist':
					$a = '用户县区';
					return $a;
					break;
				case 'userdep':
					$a = '用户部门';
					return $a;
					break;
				case 'userdep2':
					$a = '用户单位';
					return $a;
					break;
				case 'userflag':
					$a = '用户权限';
					return $a;
					break;
				case 'usercheck':
					$a = '用户审核';
					return $a;
					break;
				default:
					die('参数错误！');
	}
}

//为二维数组中的第二维增加2个键值，用在main页面中
function inscol($arr,$k1,$k2){
		foreach($arr as $key => $value){
			$arr["$key"]["$k1"] = 0;
			$arr["$key"]["$k2"] = 0;
		}
		return($arr);
	}

//截取中文字符串，只留8个字符
function cut($str){
	if(is_string($str)){
		return $str = mb_substr($str, 0, 8, 'utf-8').'...';
	}else{
		die('cut函数的参数必须是字符串。');
	}
	
}
?>