<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/18
 * Time: 下午12:17
 */

use \Workerman\Worker;
use \Workerman\WebServer;


require_once '../workerman/Autoloader.php';
\Workerman\Autoloader::setRootPath(__DIR__);
// 这里监听8080端口，如果要监听80端口，需要root权限，并且端口没有被其它程序占用
$webserver = new WebServer('http://0.0.0.0:8080');
// 类似nginx配置中的root选项，添加域名与网站根目录的关联，可设置多个域名多个目录
$webserver->addRoot('localhost', './');
//$webserver->addRoot('blog.example.com', '/your/path/of/blog/');
// 设置开启多少进程
$webserver->count = 4;

Worker::runAll();
