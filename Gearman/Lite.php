<?php
/**
 * User: Axios
 * Date: 2016/8/9
 * Time: 9:13
 * Start: gearmand -d
 * Stop: killall -9 gearmand
 *
 * url:http://hanxv.cn/index.php/archives/25.html#gearman
 */
class Gearman_Lite{
    private $task;
    private $servers;

    function __construct($config=array(),$worker_id="gearman"){
        $this->servers = $config['servers']?$config['servers'] :"127.0.0.1:4730";
        $this->task = $config['task'];
    }

    private function runClient($service,$data,$job){
        if(isset($this->task[$service])){
            $service = $this->task[$service];
        }
        $send = $data;
        $send['service'] = $service;
        $gmc= new GearmanClient();
        $gmc->addServers($this->servers);
        $gmc->addTaskBackground($job, json_encode($send), null);
        $gmc->runTasks();
        
        return $service;
    }

    public function task($service , $data =array(),$job='timer' ){
        return $this->runClient($service , $data ,$job);
    }
}