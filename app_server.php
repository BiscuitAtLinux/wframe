<?php
/**
 * 服务入口脚本
 * @author Biscuit\@Linux
 */

use Workerman\Worker;
use Frame\SimpleNamespaceRouter;

require_once __DIR__.'/vendor/autoload.php';

//监听地址
$addr = '0.0.0.0';
//监听端口号
$port = 9502;

$http_worker = new Worker('http://'.$addr.':'.$port);
echo 'HTTP Server listen at '.$addr.':'.$port.PHP_EOL;

//Worker进程数量
$http_worker->count = 4;

//每个Worker进程启动时执行，执行进程初始化
$http_worker->onWorkerStart = function ($worker){
    global $manager;
    $manager = new Frame\Manager(new SimpleNamespaceRouter(),array(new \Frame\Log\LogPlugin()));
    echo 'Worker started: '.getmypid().PHP_EOL;
};

//每次HTTP请求时执行
$http_worker->onMessage = function ($connection, $data) {
    //var_dump($data);
    global $manager;
    $manager->doRequest($connection, $data);
};

Worker::runAll();