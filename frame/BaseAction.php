<?php
namespace Frame;

/**
 * Action基类，用于实际处理用户的一次请求
 * @author Biscuit\@Linux
 */
class BaseAction implements ActionInterface {
    /**
     * {@inheritDoc}
     * @see \Frame\ActionInterface::doRequest()
     */
    public function doRequest(\Workerman\Connection\TcpConnection $connection, $data) {
        $connection->send('This is BaseAction, please override me');
    }
    
    /**
     * 获取参数
     * @param array data
     * @param string $key 参数key，不传则返回所有参数
     * @return mixed
     */
    public static function getParam($data, $key=null) {
        //merge POST和GET的参数，GET参数优先级高
        if (isset($data['post']) && isset($data['get'])) {
            $params = array_merge($data['post'],$data['get']);
        } else if (isset($data['get'])) {
            $params = $data['get'];
        } else if (isset($data['post'])) {
            $params = $data['post'];
        } else {
            $params = array();
        }
        
        if ($key) {
            return $params[$key];
        } else {
            return $params;
        }
    }
    
    /**
     * 向客户端以JSON格式发送数据
     * @param \Workerman\Connection\TcpConnection $connection
     * @param mixed $data
     */
    public static function renderJson(\Workerman\Connection\TcpConnection $connection, $data) {
        \Workerman\Protocols\Http::header('Content-type', 'application/json');
        self::logOutput($data);
        $connection->send(json_encode($data));
    }
    
    /**
     * 记录输出日志
     * @param mixed $output
     */
    public static function logOutput($output) {
        \Frame\Log\LogPlugin::addData('output', $output);
    }
}