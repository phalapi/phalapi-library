<?php

class Task_MQ_Array implements Task_MQ {

    protected $list = array();

    public function add($service, $params = array()) {
        if (!isset($this->list[$service])) {
            $this->list[$service] = array();
        }

        $this->list[$service][] = $params;

        return TRUE;
    }

    public function pop($service, $num = 1) {
        if (empty($this->list[$service])) {
            return array();
        }

        $rs = array_splice($this->list[$service], 0, $num);

        return $rs;
    }
}
