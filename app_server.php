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
//每个进程最多执行的请求数，<=0则不限制，可以用reload代替
//FIXME 高并发下会导致 Connection reset by peer，可能是进程有积压的请求，但是却退出了
$MAX_REQUEST= 0;

//每个Worker进程启动时执行，执行进程初始化
$http_worker->onWorkerStart = function ($worker){
    global $manager;
    $manager = new Frame\Manager(new SimpleNamespaceRouter(),array(new \Frame\Log\LogPlugin()));
    echo 'Worker started: '.getmypid().PHP_EOL;
};

//每次HTTP请求时执行
$http_worker->onMessage = function ($connection, $data) use ($MAX_REQUEST) {
    //已经处理请求数
    static $request_count = 0;
    global $manager;
    $manager->doRequest($connection, $data);
    //如果Worker处理请求数达到上线
    if($MAX_REQUEST>0 && ++$request_count >= $MAX_REQUEST)
    {
        Worker::stopAll();
    }
};

Worker::runAll();
