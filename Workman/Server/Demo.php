<?php
/**
 * ÑİÊ¾Àà
 *
 * @author Axios<axioscros@aliyun.com>
 * @blog http://hanxv.cn
 */
class Server_Demo extends Server_Common{
    public function index($client_id,$data){
        return $data;
    }
    public function demo0($client_id,$data){
        $this->setTarget(0);
        return "send to present client";
    }
}