<?php
namespace Plugin;

/**
 * 插件例子
 * @author Biscuit\@Linux
 */
class SamplePlugin extends \Frame\BasePlugin {
    public function beforeAction(\Workerman\Connection\TcpConnection $connection, $data){
        echo 'before'.PHP_EOL;
    }
    
    public function afterAction(\Workerman\Connection\TcpConnection $connection, $data){
        echo 'after'.PHP_EOL;
    }
}