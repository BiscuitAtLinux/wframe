<?php
namespace Frame;

/**
 * Web框架插件，类似于Java Servlet的Filter
 * @author Biscuit\@Linux
 */
class BasePlugin implements PluginInterface {
    /**
     * @{inheritDoc}
     */
    public function beforeAction(\Workerman\Connection\TcpConnection $connection, &$data){
    }
    
    /**
     * @{inheritDoc}
     */
    public function afterAction(\Workerman\Connection\TcpConnection $connection, $data){
    }
}
