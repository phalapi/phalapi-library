## 3.8.3 利用SOAP搭建Web Services

当需要使用SOAP时，需要在配置PHP时，通过```--enable-soap```参数开启SOAP。 

### (1) SOAP扩展的安装

从PhalApi-Library扩展库中的SOAP目录拷贝到你项目的Library目录下即可。  
```
cp /path/to/PhalApi-Library/SOAP/ ./PhalApi/Library/ -R
```
到此SOAP扩展安装完毕！  

### (2) SOAP扩展的配置

需要将以下扩展配置添加到项目配置文件./Config/app.php。  
```
    /**
     * 扩展类库 - SOAP配置
     * @see SoapServer::__construct ( mixed $wsdl [, array $options ] )
     */
    'SOAP' => array(
        'wsdl' => NULL,
        'options' => array(
            'uri' => 'http://api.phalapi.net/shop/soap.php',
            'port' => NULL,
        ),
    ),
```
其中，wsdl配置对应SoapServer构造函数的第一个参数，options配置则对应第二个参数，其中的uri须与下面的入口文件路径对应。  

### (3) SOAP服务端访问入口 

SOAP扩展不需要注册DI服务，但需要单独实现访问入口，参考以下实现。
```
// $ vim ./Public/shop/soap.php 
<?php
require_once dirname(__FILE__) . '/../init.php';

// 装载你的接口
DI()->loader->addDirs('Shop');

$server = new SOAP_Lite();
$server->response();
```

至此，SOAP的服务端已搭建完毕。接下来，客户端便可通过SOAP进行访问了。

### (4) SOAP客户端调用

SOAP客户端的使用，需要使用SoapClient类，其使用示例如下所示。
```
$url = 'http://api.phalapi.net/shop/soap.php';
$params = array('servcie' => 'Welcome.Say');

try {
    $client = new SoapClient(null,
        array(
            'location' => $url,
            'uri'      => $url,
        )
    );

    $data = $client->__soapCall('response', array(json_encode($params)));

    //处理返回的数据。。。
    var_dump($data);
}catch(SoapFault $fault){
    echo "Error: ".$fault->faultcode.", string: ".$fault->faultstring;
}
```
注意，客户端传递的接口参数，最后需要JSON编码后再传递。  

### (5) SOAP调试脚本

SOAP扩展提供了一个可以发起SOAP访问的脚本，使用示例如下。  
```
$ ./Library/SOAP/check.php http://api.phalapi.net/shop/soap.php "service=Welcome.Say"
array(3) {
  ["ret"]=>
  int(200)
  ["data"]=>
  string(11) "Hello World"
  ["msg"]=>
  string(0) ""
}
```
### (6) 对客户端的影响

当使用SOAP访问接口服务时，服务端可以通过使用SOAP扩展快速搭建Web Services，但对于客户端，如同使用PHPRPC协议一样，也要进行三方面的调整。这里简单说明一下。   

 + 调用方式的改变  

首先是客户端调用方式的改变，需要通过SOAP协议进行访问。  

 + POST参数传递方式的改变

其次是对POST参数传递的改变。和前面的PHPRPC协议一样，客户端需要把全部的参数JSON编码后再传递。当POST的数据和GET的数据冲突时，以POST为准。   

相应地，当需要传递POST参数时，客户需要这样调整：
```
$data = $client->__soapCall('response', array(json_encode($params)));
```
若无此POST参数，则可以忽略不传。

 + 返回结果格式的改变

和PHPRPC协议一样，客户端接收到的是接口服务直接返回的源数据，不再是序列化后返回的字符串。如前面示例中，返回的是数组类型。   

