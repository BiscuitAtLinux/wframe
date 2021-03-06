<?php
namespace Frame;

/**
 * Web框架管理器，应用入口
 * @author Biscuit\@Linux
 */
class Manager {
    
    /** @var RouterInterface $router */
    private $router = null;
    private $pluginList = null;
    
    /**
     * Web框架管理器
     * @param RouterInterface $router 路由类的实例
     * @param array $pluginList 插件实例列表
     */
    public function __construct(RouterInterface $router, array $pluginList = array()) {
        $this->router = $router;
        $this->pluginList = $pluginList;
    }
    
    /**
     * 处理请求
     * 执行插件钩子，执行路由
     * @param \Workerman\Connection\TcpConnection $request
     * @param array $data
     */
    public function doRequest(\Workerman\Connection\TcpConnection $connection, $data) {
        try {
            $httpEnd = false;
            //是否在未声明长连接时主动关闭连接，如果是异步接口请在Action中设为false，否则会导致某些HTTP/1.0的客户的被提前关闭
            $connection->autoCloseConnection = true;

            //执行插件beforeAction
            foreach ($this->pluginList as /** @var PluginInterface $plugin*/ $plugin) {
                $plugin->beforeAction($connection, $data);
            }
            //由路由执行实际请求
            $this->router->doRequest($connection, $data);
            $httpEnd = true;
            //执行插件aftereAction
            foreach ($this->pluginList as /** @var PluginInterface $plugin*/ $plugin) {
                $plugin->afterAction($connection, $data);
            }
            //如果不是keep-alive的连接，则主动关闭，保证与apache bench等HTTP/1.0老客户的的兼容性
            if ($connection->autoCloseConnection && $data['server']['SERVER_PROTOCOL']=='HTTP/1.0' &&
                !(isset($data['server']['HTTP_CONNECTION']) && $data['server']['HTTP_CONNECTION']=='Keep-Alive')) {
                $connection->close();
            }
        } catch (\Exception $e) {
            echo $e->getTraceAsString().PHP_EOL;
            //如果HTTP请求还没有结束，就可以报500
            if (!$httpEnd) {
                \Workerman\Protocols\Http::header('HTTP/',true,500);
                $connection->send('Server Error: ' . $e->getMessage());
            }
        }
    }
}
