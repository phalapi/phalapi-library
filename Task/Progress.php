<?php

class Task_Progress {

    //1 day
    const MAX_LAST_FIRE_TIME_INTERVAL = 86400;

    protected $model;

    public function __construct() {
        $this->model = new Model_Task_TaskProgress();
    }

    public function run() {
        $this->tryToResetWrongItems();

        $this->runAllWaittingItems();

        return TRUE;
    }

    protected function tryToResetWrongItems() {
        $maxLastFireTime = $_SERVER['REQUEST_TIME'] - self::MAX_LAST_FIRE_TIME_INTERVAL;

        $wrongItems = $this->model->getWrongItems($maxLastFireTime);

        foreach ($wrongItems as $item) {
            $this->model->resetWrongItems($item);

            DI()->logger->debug('task try to reset wrong items', $item);
        }
    }

    protected function runAllWaittingItems() {
        $waittingItems = $this->model->getAllWaittingItems();

        foreach ($waittingItems as $item) {
            //
            if (!$this->model->isRunnable($item['id'])) {
                continue;
            }

            $class = $item['trigger_class'];
            $params = $item['fire_params'];

            if (empty($class) || !class_exists($class)) {
                DI()->logger->error('task can not run illegal class', $item);
                continue;
            }

            $trigger = new $class();
            if (!is_callable(array($class, 'fire'))) {
                DI()->logger->error('task can not call fire()', $item);
                continue;
            }

            $this->model->setRunningState($item['id']);

            try {
                $result = call_user_func(array($trigger, 'fire'), $params);

                $this->model->updateFinishItem($item['id'], $result);
            } catch (Exception $ex) {
                throw $ex;
                $this->model->updateExceptionItem($item['id'], $ex->getMessage());
            }
        }
    }
}
