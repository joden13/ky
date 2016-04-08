<?php
// 1. 独立配置的方法
require_once  'md.php';

$database = new medoo(array(
    // 必须配置项
    'database_type' => 'mysql',
    'database_name' => 'yourdatabasename',
    'server' => 'localhost',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
 
    // 可选参数
    //'port' => 3306,
 
    // 可选，定义表的前缀
    //'prefix' => 'PREFIX_',
 
    // 连接参数扩展, 更多参考 http://www.php.net/manual/en/pdo.setattribute.php
    'option' => array(
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    )
));
//打印info，查看链接是否成功
//print_r($database->info());


//打开session
session_start();

$host = $_SERVER['SERVER_NAME']."/ky";

define("XITONG","网安支队电子物证勘验登记系统");

?>