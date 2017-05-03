<?php
namespace Frame\Log;

/**
 * 简单日志记录器
 * @author Biscuit\@Linux
 */
class SimpleLogger {
    const LEVEL_FATAL = 4;
    const LEVEL_WARNING = 3;
    const LEVEL_NOTICE = 2;
    const LEVEL_INFO = 1;
    const LEVEL_DEBUG = 0;
    
    static $LEVEL_MARK = array(
        self::LEVEL_FATAL => 'FATAL',
        self::LEVEL_WARNING => 'WARNING',
        self::LEVEL_NOTICE => 'NOTICE',
        self::LEVEL_INFO => 'INFO',
        self::LEVEL_DEBUG => 'DEBUG',
    );
    
    private $file = null;
    private $filename = null;
    private $logLevel = null;
    
    /**
     * 构造函数
     * @param string $filename
     * @param int $logLevel 0~4，只打印大于等于当前级别的日志
     */
    public function __construct($filename=null,$logLevel=self::LEVEL_NOTICE) {
        if ($filename) {
            $this->filename = $filename;
        } else {
            $this->filename = __DIR__.'/../../app.log';
        }
        $this->logLevel = $logLevel;
        $this->file = fopen($this->filename, 'a');
    }
    
    /**
     * 打印一条日志，只会打印大于设定级别的日志，其他日志会忽略
     * @param int $level
     * @param string $message
     */
    public function log($level, $message) {
        if ($level<$this->logLevel || !isset(self::$LEVEL_MARK[$level])) {
            return;
        }
        $msg = self::$LEVEL_MARK[$level].': '.date('Y-m-d H:i:s').' '.$message.PHP_EOL;
        //同步写入
        fwrite($this->file, $msg);
    }
    
    /**
     * 打印Debug日志
     * @param string $message
     */
    public function debug($message) {
        $this->log(self::LEVEL_DEBUG, $message);
    }
    
    /**
     * 打印Info日志
     * @param string $message
     */
    public function info($message) {
        $this->log(self::LEVEL_INFO, $message);
    }
    
    /**
     * 打印Notice日志
     * @param string $message
     */
    public function notice($message) {
        $this->log(self::LEVEL_NOTICE, $message);
    }
    
    /**
     * 打印Warning日志
     * @param string $message
     */
    public function warning($message) {
        $this->log(self::LEVEL_WARNING, $message);
    }
    
    /**
     * 打印Fatal日志
     * @param string $message
     */
    public function fatal($message) {
        $this->log(self::LEVEL_FATAL, $message);
    }
}