<?php
// [ 应用入口文件 ]
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
//配置文件目录
define('CONF_PATH', __DIR__.'/../config/');
// 加载框架引导文件
require __DIR__ . '/../core/start.php';
