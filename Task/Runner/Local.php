<?php

class Task_Runner_Local extends Task_Runner {

    protected function youGo($service, $params) {
        $params['service'] = $service;

        DI()->request = new PhalApi_Request($params);
        DI()->response = new PhalApi_Response_Json();

        $phalapi = new PhalApi();
        $rs = $phalapi->response();
        $apiRs = $rs->getResult();

        if ($apiRs['ret'] != 200) {
            DI()->logger->debug('task local go fail', 
                array('servcie' => $service, 'params' => $params, 'rs' => $apiRs));

            return FALSE;
        }

        return TRUE;
    }

}

