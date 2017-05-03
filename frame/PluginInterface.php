<?php
namespace Frame;

/**
 * Web框架插件，类似于Java Servlet的Filter
 * @author Biscuit\@Linux
 */
interface PluginInterface {
    /**
     * 执行实际Action之前执行
     * @param \Workerman\Connection\TcpConnection $request
     * @param array $data
     */
    public function beforeAction(\Workerman\Connection\TcpConnection $connection, $data);
    
    /**
     * 实际Action执行之后执行
     * @param \Workerman\Connection\TcpConnection $request
     * @param array $data
     */
    public function afterAction(\Workerman\Connection\TcpConnection $connection, $data);
}