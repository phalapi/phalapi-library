<?php

return array(
    /**
     * 扩展类库 - SOAP配置
     * @see SoapServer::__construct ( mixed $wsdl [, array $options ] )
     */
    'SOAP' => array(
        'wsdl' => NULL,
        'options' => array(
            'uri' => 'http://localhost/demo/soap.php',
            'port' => NULL,
        ),
    ),
);
