<?php

class Swoole_Response_Json extends PhalApi_Response_Json {

    public function __construct() {
    }

    //no header any more
    protected function handleHeaders($headers) {
    }

    public function formatResult($result) {
        return parent::formatResult($result);
    }
}
