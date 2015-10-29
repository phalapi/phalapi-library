<?php

/**
 * 默认接口服务类
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */
class Api_Default extends PhalApi_Api{

    public function getRules(){
        return array(
            'select' => array(),
            'delete' => array(),
            'update' => array(),
            'insert' => array(),
        );
    }

    public function select(){
        $Cluster_User = new Cluster_User();
        $PhalApi_Tool = new PhalApi_Tool();
        return $Cluster_User->getInfo($PhalApi_Tool->createRandStrphone(2));
    }

    public function delete(){
        $Cluster_User = new Cluster_User();
        $PhalApi_Tool = new PhalApi_Tool();
        return $Cluster_User->delectInfo($this->createRandStrphone(2));
    }

    public function update(){
        $Cluster_User = new Cluster_User();
        $PhalApi_Tool = new PhalApi_Tool();
        $data         = array(
            'city'  => $this->createRandStr(3),
            'phone' => $this->createRandStrphone(3)
        );
        return $Cluster_User->updateInfo($data, $this->createRandStrphone(2));
    }

    public function insert(){
        $Cluster_User = new Cluster_User();
        $data         = array(
            'name'  => $this->createRandStr(1),
            'city'  => $this->createRandStr(1),
            'phone' => $this->createRandStrphone(1),
        );
        return $Cluster_User->setInfo($data);
    }

    private function createRandStrphone($len){
        $chars = '0123456789';
        return substr(str_shuffle(str_repeat($chars, rand(5, 8))), 0, $len);
    }

    private function createRandStr($len){
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle(str_repeat($chars, rand(5, 8))), 0, $len);
    }
}
