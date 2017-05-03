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