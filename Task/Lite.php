<?php

class Task_Lite {

    protected $mq;

    public function __construct(Task_MQ $mq) {
        $this->mq = $mq;
    }

    public function add($service, $params = array()) {
        if (empty($service) || count(explode('.', $service)) < 2) {
            return FALSE;
        }
        if (!is_array($params)) {
            return FALSE;
        }

        $rs = $this->mq->add($service, $params);

        if (!$rs) {
            DI()->logger->debug('task add a new mq', 
                array('service' => $service, 'params' => $params));

            return FALSE;
        }

        return TRUE;
    }
}
