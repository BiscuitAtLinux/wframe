<?php
namespace Frame;

/**
 * 路由接口，查找并执行实际的Action
 * @author Biscuit\@Linux
 */
interface RouterInterface {
    /**
     * 执行路由操作
     * @param \Workerman\Connection\TcpConnection $request
     * @param array $data
     */
    public function doRequest(\Workerman\Connection\TcpConnection $connection, $data);
}