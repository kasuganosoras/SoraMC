<?php
set_time_limit(10);	// 超时设置
$pathinfo = pathinfo($_SERVER['PHP_SELF']);
$path = str_replace("/" . $pathinfo['basename'], "", $_SERVER['PHP_SELF']);
define("ROOT", str_replace("\\", "/", __DIR__));
define("DOCROOT", $path);
include(ROOT . "/sora-include/loader.php");
$Loader = new Loader();
$Loader->plugin();
$Loader->checkLogin();
$Loader->router();
$Loader->frame();