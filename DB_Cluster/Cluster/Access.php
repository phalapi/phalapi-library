<?php

/**
 * 初始化DB集群拓展
 * @author: 喵了_个咪 <wenzhenxi@vip.qq.com> 2015-10-21
 */
class Cluster_Access{
    //存放集群文件
    private $cluster_list = array();
    //存放配置文件
    private $cluster_config;
    //数据库链接列表
    private $DB_list = array();

    /**
     * 构造函数初始化
     */
    public function __construct($config){
        //获取配置项
        $this->cluster_config = $config['cluster'];
        //过滤配置组
        foreach($this->cluster_config['list'] as $k => $v){
            $this->cluster_list[$k] = $config[$k];
        }
        //初始化数据库
        $tables = array(
            'tables' => array(
                //通用路由
                '__default__' => array(
                    'prefix' => '',
                    'key'    => 'id',
                    'map'    => array(
                        array('db' => 'db_demo'),
                    ),
                )
            )
        );
        foreach($this->cluster_list as $k => $v){
            $general = $v['general'];
            foreach($v['db_list'] as $key => $value){
                $servers             = array(
                    'servers' => array(
                        'db_demo' => array_merge($general, $value)
                    )
                );
                $this->DB_list[$k][] = new PhalApi_DB_NotORM(array_merge($servers, $tables), !empty($_GET['__sql__'])
                    ? true : false);
            }
        }
    }

    /**
     * 获取集群文件
     */
    public function getClusterList(){
        return $this->cluster_list;
    }

    /**
     * 获取配置文件
     */
    public function getClusterConfig(){
        return $this->cluster_config;
    }

    /**
     * 获取数据库实例列表
     */
    public function getDBList(){
        return $this->DB_list;
    }
}




