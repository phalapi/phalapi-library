<?php

class PHPRPC_PhalAPi {

    protected $phalapi;

    public function __construct($phalapi = NULL) {
        if ($phalapi === NULL) {
            $phalapi = new PhalApi();
        }

        $this->phalapi = $phalapi;
    }

    public function response() {
        $rs = $this->phalapi->response();

        return $rs->getResult();
    }
}
