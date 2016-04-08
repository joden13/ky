<?php



$str = '如不符合要求求请';
print(strlen($str));
$content=mb_substr($str, 0, 8, 'utf-8').'...';
echo $content;
?>