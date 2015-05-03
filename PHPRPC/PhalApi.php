<?php

class PHPRPC_PhalAPi {

    protected $phalapi;

    public function __construct($phalapi = NULL) {
        if ($phalapi === NULL) {
            $phalapi = new PhalApi();
        }

        $this->phalapi = $phalapi;
    }

    public function response($params = NULL) {
        $paramsArr = json_decode($params, TRUE);
        if ($paramsArr !== FALSE) {
            DI()->request = new PhalApi_Request(array_merge($_GET, $paramsArr));
        } else {
			DI()->request = new PhalApi_Request($_GET);
		}

        $rs = $this->phalapi->response();

        return $rs->getResult();
    }
}
