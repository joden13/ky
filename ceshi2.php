<?php
function sec($cat,$postval){
	if(is_array($postval)) {
		foreach ($postval as $k => $v) {
			$postval[$k] = sec($cat,$v);
		}
	}else if(is_string($postval)) {
		$pregid = '/^[a-z][a-z0-9]*[a-z0-9]{3,16}$/i';		//允许字母数字，必须字母开头，不允许符号结尾。
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
				die('参数错误！');
		}
		if(preg_match($preg,$postval)){
			return $postval;
		}else{
			die("你输入的 $postval 不符合填写要求");
		}
	
	}
}
$s=sec('id','wazd');
//$d = preg_match('/^[a-z][a-z0-9]*[a-z0-9]{3,16}$/i','wazd');
print($s);

?>