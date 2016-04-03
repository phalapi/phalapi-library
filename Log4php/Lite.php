<?php
/**
 * Apache log4php
 * 
 * 使用示例：
```
 * DI()->logger = new Log4php_Lite();
``` 
 * @link http://logging.apache.org/log4php/quickstart.html
 * @author dogstar 20160402
 */

require_once dirname(__FILE__) . '/src/Logger.php';

class Log4php_Lite extends PhalApi_Logger {

    protected $logger;

    public function __construct($name = 'PhalApi') {
        Logger::configure(API_ROOT . '/Config/log4php.xml');

        $this->logger = Logger::getLogger($name);

        parent::__construct(PhalApi_Logger::LOG_LEVEL_DEBUG | PhalApi_Logger::LOG_LEVEL_INFO | PhalApi_Logger::LOG_LEVEL_ERROR);
    }

    public function log($type, $msg, $data) {
        if (!in_array(strtolower($type), array('trace', 'debug', 'info', 'warn', 'error', 'fatal'))) {
            throw new PhalApi_Exception_InternalServerError(T('no log4php level as {type}', array('type' => $type)));
        }

        $message = $data !== NULL ? $msg . '|' . json_encode($data) : $msg;
        $this->logger->$type($message);
    }

    public function trace($msg, $data = NULL) {
        $this->log('trace', $msg, $data);
    }

    public function warn($msg, $data = NULL) {
        $this->log('warn', $msg, $data);
    }

    public function fatal($msg, $data = NULL) {
        $this->log('fatal', $msg, $data);
    }
}

