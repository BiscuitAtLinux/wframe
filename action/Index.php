<?php
namespace Action;

class Index extends \Frame\BaseAction{
    public function doRequest(\Workerman\Connection\TcpConnection $connection, $data) {
        $connection->send('Hello Index Action');
    }
}