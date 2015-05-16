<?php

interface Task_MQ {

    public function add($service, $params = array());

    public function pop($service, $num = 1);
}
