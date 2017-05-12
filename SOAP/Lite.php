<?php
/**
 * SOAP服务
 * @author dogstar 20170512
 */

class SOAP_Lite {

    public function response() {
        try {
            $config = DI()->config->get('app.SOAP');

            $server = new SOAPServer(
                $config['wsdl'],
                $config['options']
            );

            $server->setClass('SOAP_PhalApi');
            return $server->handle();
        } catch (SOAPFault $f) {
            DI()->logger->error('SAOPFault', $f->faultstring);
            return FALSE;
        }
    }
}
