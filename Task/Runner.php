<?php

abstract class Task_Runner {

    protected $mq;

    protected $step;

    public function __construct(Task_MQ $mq, $step = 10) {
        $this->mq = $mq;

        $this->step = max(1, intval($step));
    }

    public function go($service) {
        $rs = array('total' => 0, 'fail' => 0);

        $todoList = $this->mq->pop($service, $this->step);
        $failList = array();

        while (!empty($todoList)) {
            $rs['total'] += count($todoList);

            foreach ($todoList as $params) {
                try {
                    $isFinish = $this->youGo($service, $params);

                    if (!$isFinish) {
                        $rs['fail'] ++;
                    }
                } catch (PhalApi_Exception_InternalServerError $ex) {
                    $rs['fail'] ++;

                    $failList[] = $params;

                    DI()->logger->error('task occur exception to go',
                        array('service' => $service, 'params' => $params, 'error' => $ex->getMessage()));
                }
            }

            $todoList = $this->mq->pop($service, $this->step);
        }

        foreach ($failList as $params) {
            $this->mq->add($service, $params);
        }

        return $rs;
    }

    abstract protected function youGo($service, $params);
}
