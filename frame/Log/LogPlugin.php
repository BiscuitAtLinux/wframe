<?php
namespace Frame\Log;

/**
 * 简单日志插件，每次请求自动打印一次NOTICE日志
 * @author biscuit
 */
class LogPlugin extends \Frame\BasePlugin {
    
    private $logger = null;
    private static $slogger = null;
    private $startTime = null;
    private static $extraData = null;   //日志额外数据
    
    /**
     * 构造函数
     * @param SimpleLogger $logger 日志实例
     */
    public function __construct($logger=null) {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new SimpleLogger();
        }
        //记录到静态变量中，便于Action中访问
        self::$slogger = $this->logger;
    }
    
    /**
     * 进入Action前记录请求开始时间
     * @{inheritDoc}
     */
    public function beforeAction(\Workerman\Connection\TcpConnection $connection, $data){
        $this->startTime = microtime(true);
    }
    
    /**
     * Action结束后打印日志
     * @{inheritDoc}
     */
    public function afterAction(\Workerman\Connection\TcpConnection $connection, $data){
        $endTime = microtime(true);
        $this->logger->notice(sprintf('%s remote=%s(%s) request_uri=%s ua="%s" start=%0.3f end=%0.3f const=%0.3f param=%s cookie=%s extra=%s',
                $data['server']['REQUEST_METHOD'],$data['server']['REMOTE_ADDR'],isset($data['server']['HTTP_X_FORWARDED_FOR'])?$data['server']['HTTP_X_FORWARDED_FOR']:'-', 
                $data['server']['REQUEST_URI'],$data['server']['HTTP_USER_AGENT'], $this->startTime,$endTime, $endTime-$this->startTime,
                json_encode(self::getParam($data)),isset($data['cookie'])?json_encode($data['cookie']):'',json_encode(self::$extraData)));
        $this->startTime = null;
        self::$extraData = null;
    }
    
    /**
     * 获取参数
     * @param array $data
     * @return mixed
     */
    private static function getParam($data) {
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
    
        return $params;
    }
    
    /**
     * 获得日志对象
     * @return \Frame\Log\SimpleLogger
     */
    public static function getLogger() {
        return self::$slogger;
    }
    
    /**
     * 添加访问日志中的参数
     * @param string $key
     * @param mixed $value
     */
    public static function addData($key,$value) {
        if (self::$extraData === null) {
            self::$extraData = array();
        }
        self::$extraData[$key] = $value;
    }
}
