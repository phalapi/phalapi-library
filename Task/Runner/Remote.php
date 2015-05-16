<?php

class Task_Runner_Remote extends Task_Runner {

    protected $contector;

    protected $timeoutMS;

    const MAX_TIMEOUT_MS = 3000;

    public function __construct(Task_MQ $mq, $step = 10, Task_Runner_Remote_Connector $contector = NULL) {
        $config = DI()->config->get('app.Task.runner.remote');

        if ($contector === NULL) {
            if (empty($config['host'])) {
                throw new PhalApi_Exception_InternalServerError(T('task miss api host for'));
            }
            $contector = new Task_Runner_Remote_Connector_Http($config);
        }

        $this->contector = $contector;
        $this->timeoutMS = isset($config['timeoutMS']) ? intval($config['timeoutMS']) : self::MAX_TIMEOUT_MS;

        parent::__construct($mq, $step);
    }

    protected function youGo($service, $params) {
        $rs = $this->contector->request($service, $params, $this->timeoutMS);

        if ($this->contector->getRet() == 404) {
            throw PhalApi_Exception_InternalServerError('task request api time out',
                array('url' => $this->contector->getUrl()));
        }

        $isOk = $this->contector->getRet() == 200 ? TRUE : FALSE;

        if (!$isOk) {
            DI()->logger->debug('task remote request not ok', 
                array('url' => $this->contector->getUrl(), 'ret' => $this->contector->getRet(), 'msg' => $this->contector->getMsg()));
        }

        return $isOk;
    }
}
