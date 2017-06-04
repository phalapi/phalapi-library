<?php
/**
 * @link http://www.swoole.com/
 * 
 * - 请将以下配置追加到./Config/app.php
```
 *   'Swoole' => array(
 *       //服务
 *       'server' => array(
 *           'ip' => '127.0.0.1',
 *           'port' => 9501,
 *           'worker_num' => 1,
 *       ),
 *       //计划任务
 *       'task' => array(
 *           'ip' => '127.0.0.1',
 *           'port' => 9502,
 *           'worker_num' => 1,
 *       ),
 *   ),
```
 *
 * @author dogstar 20150501
 */

class Swoole_Lite {

    protected $task_list;

    /**
     * 启动接口服务，支持长链接
     */
    public function runServer() {
        $oldErrorHandler = set_error_handler(array(__CLASS__, 'myErrorHandler'));

        $config = DI()->config->get('app.Swoole.server');

        $ip         = isset($config['ip']) ? $config['ip'] : '127.0.0.1';
        $port       = isset($config['port']) ? $config['port'] : 9501;
        $workerNum  = isset($config['worker_num']) ? $config['worker_num'] : 8;

        $serv = new swoole_server($ip, $port);

        $serv->set(array(
            'worker_num' => $workerNum,     //工作进程数量
            'daemonize' => true,            //是否作为守护进程
        ));

        $serv->on('connect', function ($serv, $fd){
            DI()->logger->debug('client connect in swoole');
        });

        $serv->on('receive', function ($serv, $fd, $fromId, $data) {
            $params = json_decode($data, TRUE);
            if (!is_array($params)) {
                $params = array();
            }

            DI()->request = new PhalApi_Request($params);
            DI()->response = new Swoole_Response_Json();

            try {
                $phalapi = new PhalApi();
                $rs = $phalapi->response();
                $apiRs = $rs->getResult();

                $serv->send($fd, $rs->formatResult($apiRs));

            } catch (Exception $ex) {
                echo $ex->getTraceAsString();
                DI()->logger->error('exception in swoole', $ex->getMessage());
                //TODO 通知管理员
            }

            if (DI()->notorm) {
                DI()->notorm->disconnect();
            }

            $serv->close($fd);
        });

        $serv->on('close', function ($serv, $fd) {
            DI()->logger->debug('client close in swoole');
        });

        $serv->start();
    }

    /**
     * 启动计划任务，支持异步处理
     */
    public function runTask() {
        $oldErrorHandler = set_error_handler(array(__CLASS__, 'myErrorHandler'));

        $config = DI()->config->get('app.Swoole.task');

        $ip         = isset($config['ip']) ? $config['ip'] : '127.0.0.1';
        $port       = isset($config['port']) ? $config['port'] : 9502;
        $workerNum  = isset($config['worker_num']) ? $config['worker_num'] : 4;

        $serv = new swoole_server($ip, $port);

        $serv->set(array('task_worker_num' => $workerNum));

        $serv->on('Receive', function($serv, $fd, $fromId, $data) {
            $taskId = $serv->task($data);
            $this->task_list[$taskId] = $fd;
            DI()->logger->debug("asynctask($taskId) dispath in swoole", $data);
        });

        $serv->on('Task', function ($serv, $taskId, $fromId, $data) {
            DI()->logger->debug("asynctask($taskId) start in swoole", $data);

            $params = json_decode($data, TRUE);
            if (!is_array($params)) {
                $params = array();
            }

            DI()->request = new PhalApi_Request($params);
            DI()->response = new Swoole_Response_Json();

            try {
                $phalapi = new PhalApi();
                $rs = $phalapi->response();
                $apiRs = $rs->getResult();

                $serv->finish($rs->formatResult($apiRs));

            } catch (Exception $ex) {
                echo $ex->getTraceAsString();
                DI()->logger->error("asynctask($taskId) exception in swoole", $ex->getMessage());
                $serv->finish("Exception: " . $ex->getMessage());
                //TODO 通知管理员
            }

            if (DI()->notorm) {
                DI()->notorm->disconnect();
            }
        });

        $serv->on('Finish', function ($serv, $taskId, $data) {
            DI()->logger->debug("asynctask($taskId) finish in swoole", $data);
            $serv->close($this->task_list[$taskId]);
        });

        $serv->start();
    }

    /**
     * error handler function
     */
    public static function myErrorHandler($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting
            return;
        }

        switch ($errno) {
        case E_USER_ERROR:
        case E_USER_WARNING:
        case E_USER_NOTICE:
        default:
            DI()->logger->error('error in swoole', 
                array('errno' => $errno, 'errstr' => $errstr, 'errline' => $errline, 'errfile' => $errfile));
            break;
        }

        /* Don't execute PHP internal error handler */
        return true;
    }
}
