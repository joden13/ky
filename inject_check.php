<?php
#		防注入文件
#		对用户输入进行检测
#		2016年1月2日, PM 01:37:22
#		2016年2月2日, AM 11:24:22更新



function sec($cat,$postval){
	if(is_array($postval)) {
		foreach ($postval as $k => $v) {
			$postval[$k] = sec($cat,$v);
		}
	}else if(is_string($postval)) {
		$pregid = '/^[a-z][a-z0-9]*[a-z0-9]{3,16}$/i';		//允许字母数字，必须字母开头，4-16个字符。
		$pregcn = '/^[\x{4e00}-\x{9fff}]+$/u';							//匹配中文
		$pregen = '/^[a-z]+$/ui';		//纯英文
		$pregsfz = '/^[1-9]\d{5}[1-9]\d{3}((0[1-9])|(1[0-2]))(0[1-9]|([1|2]\d)|3[0-1])\d{3}[0-9X]$/i';		//身份证号码
		$pregmob = '/^1[3-8]\d{9}$/';		//手机号码
		$pregtel = '/^0\d{2,3}(-?)\d{7,8}$/';		//固定电话号码
		$pregnum = '/^\d+$/';		//纯数字
		$preg = '';
		switch($cat){
			case 'id':
				$preg = $pregid;
				break;
			case 'cn':
				$preg = $pregcn;
				break;
			case 'en':
				$preg = $pregen;
				break;
			case 'sfz':
				$preg = $pregsfz;
				break;
			case 'mob':
				$preg = $pregmob;
				break;
			case 'tel':
				$preg = $pregtel;
				break;
			case 'num':
				$preg = $pregnum;
				break;
			default:
				die('参数错误!');
		}
		if(!(preg_match($preg,$postval))){
			die("你输入的 $postval 不符合填写要求");
		}
	}
	return $postval;
}

//检测get数据的函数
function secget($array,$key,$preg='/^\d+$/'){
	if(!isset($array[$key])||count($array) >= 2){	//参数不能超过一个，且必须是给定的键值
		die('非法参数');
	}else{
		$array[$key] = sec('num',$array[$key]);
		return $array[$key];
	}
}

//对某些字符前加上了反斜线。这些字符是单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）。 
function saddslashes($array) {
	if(is_array($array)) {
		foreach($array as $key => $val) {
			$array[$key] = saddslashes($val);
		}
	} else if (is_string ( $array )) {
		$array = addslashes(strip_tags($array));
	}
	return $array;
} 
?>