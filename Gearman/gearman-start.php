<?php

require_once  "./gearman-server.php";
$config = DI()->config->get('app.gearman');  //获取gearman配置
if(empty($config)){
    $config = ['servers'=>"127.0.0.1:4730",'task'=>['testTask'=>"Test.task"]];
}
echo "Starting:".date("Y-m-d H:i:s")."\n";
$worker= new GearmanWorker();
$worker->addServers($config['servers']);
$worker->addFunction("job", "do_func");
$worker->setTimeout (10000);  //10秒。如果不设置此项，则会一直占用mysql连接，直到执行某个job，也就是说，如果长时间无job，则会出现mysql连接超时的错误
while(@$worker->work()|| $worker->returnCode() == GEARMAN_TIMEOUT){
    if ($worker->returnCode() == GEARMAN_TIMEOUT)
    {
        //echo "Timeout. Waiting for next job...\n";
        continue;
    }
    if ($worker->returnCode() != GEARMAN_SUCCESS) {
        echo "return_code: " . $worker->returnCode() . "\n";
        continue;
    }
}
echo "Done:".date("Y-m-d H:i:s")."\n";
function do_func($job)
{
    $handle = (string)$job->handle();
    $data = $job->workload();
    echo $handle.":Datetime -------> " . date("Y-m-d H:i:s"). "\n";

    $params = json_decode($data, TRUE); // 即将发往请求参数，
    if (!is_array($params)) {
        $params = array();
    }
    //$params['sign'] = ''; //可以在此处添加请求到接口的数字签名
    //$params['from'] = 'gearman';

    echo $handle.":Received job---->" . json_encode($params). "\n";

    DI()->request = new PhalApi_Request($params);
    DI()->response = new Gearman_Response_Json();
    $phalapi = new PhalApi();
    $rs = $phalapi->response();
    $apiRs = $rs->getResult();
    echo $handle.":Result --------->" .$rs->formatResult($apiRs)."\n";
    $data = $rs->formatResult($apiRs);
    $rs->output();
    $job->sendComplete($data);
}
