<?php
namespace Frame;

/**
 * Action接口，用于实际处理用户的一次请求
 * @author Biscuit\@Linux
 */
interface ActionInterface {
    /**
     * 处理用户请求，返回结果
     * @param \Workerman\Connection\TcpConnection $request
     * @param array $data
     */
    public function doRequest(\Workerman\Connection\TcpConnection $connection, $data);
}