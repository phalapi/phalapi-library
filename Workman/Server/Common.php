<?php
/**
 * 公共类
 *
 * 其它Server类继承该公共类
 *
 * @author Axios<axioscros@aliyun.com>
 * @blog http://hanxv.cn
 */
class Server_Common{
    protected $message_type,$config;
    protected $CLIENT_ID,$SERVER ,$ACTION , $CLIENT_MSG ,$MSG_DATA ;
    protected $TARGET , $CLIENTS=array() ;
    function __construct(){
        $this->message_type = array(
            '0'=>"present",   //给当前用户发送
            '1'=>"single",    //给特定用户发送
            '2'=>"group",     //给所有正在连接的用户发送
            '3'=>"all"        //给多个用户发送
        );
        $this->config = DI()->config->get('app.workman');
    }
    
    protected function response($data){
        $re['type'] = empty($this->TARGET)? "present":$this->TARGET;
        if(!($this->CLIENTS)){
            $re['clients'] = $this->CLIENTS;
        }
        $re['data'] = $data;
        return $re;
    }
    protected function set($message=array()){
        $this->setServer(!empty($message['server'])? $message['server'] :$this->config['default_server'] );
        $this->setAction(!empty($message['action'])? $message['action'] :$this->config['default_action']);
        $this->setData(!empty($message['data'])?$message['data']:array());
    }

    private function setServer($server){
        $server = "Server_".ucfirst($server);
        $this->SERVER = class_exists($server) ? $server: "Server_".ucfirst($this->config['default_server']);
    }

    private function setAction($action){
        $this->ACTION = empty($action)?$this->config['default_action']:$action;
    }

    private function setData($data){
        $this->MSG_DATA = $data;
    }

    protected function setTarget($type="0"){
        $type = strval($type);
        $this->TARGET = in_array($type,$this->message_type) ? $this->message_type[$type] : "present";
    }

    protected function setClient($client){
        $this->CLIENTS = is_array($client) ? $client : array(0=>$client);
    }
    
}