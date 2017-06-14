<?php
/**
 * 默认消息处理类
 *
 * @author Axios<axioscros@aliyun.com>
 * @blog http://hanxv.cn
 */
class Server_Index extends Server_Common{
    public function index(){
        return $this->response("Hello World!This is Server_index/index");
    }
    public function demo0(){
        $this->setTarget(0);
        return $this->response("send to present client");
    }

    public function demo1(){
        $this->setTarget(1);
        $this->setClient(100251);
        return $this->response("send to a client");
    }

    public function demo2(){
        $this->setTarget(2);
        $client1_id = 1;
        $client2_id = 2;
        $clients = array($client1_id,$client2_id);
        $this->setClient($clients);
        return $this->response("send to clients ");
    }
    public function demo3(){
        $this->setTarget(3);
        return $this->response("send to all clients ");
    }
}