<?php
/**
 * DB集群配置
 * @author: 喵了_个咪 <wenzhenxi@vip.qq.com> 2015-10-21
 */

return array(
    /**
     * DB数据库服务器集群
     */
    'demo'   => array(
        'general'    => array(
            'user'     => 'root',                  //数据库用户名
            'password' => 'woyouxinxi',                        //数据库密码
            'port'     => '3306',                  //数据库端口
            'charset'  => 'UTF8',                  //数据库字符集
        ),
        'db_list'    => array(
            0 => array(
                'host' => '192.168.0.201',             //数据库域名
                'name' => 'user_cluster0',               //数据库名字
            ),
            1 => array(
                'host' => '192.168.0.202',             //数据库域名
                'name' => 'user_cluster1',               //数据库名字
            ),
            2 => array(
                'host' => '192.168.0.203',             //数据库域名
                'name' => 'user_cluster2',               //数据库名字
            ),
            3 => array(
                'host' => '192.168.0.204',             //数据库域名
                'name' => 'user_cluster3',               //数据库名字
            ),
        ),
        //表名称列表,所有的库中必须一样
        'table_list' => array(
            0 => 'user0',
            1 => 'user1',
            2 => 'user2',
            3 => 'user3',
        ),
    ),
    /**
     * 配置表
     */
    'cluster' => array(
        'list'    => array(
            'demo'  => array(
                'id_min' => 0,
                'id_max' => 0,
            ),
        ),
        // where查询条件放到衍生表中的字段
        'where'   => array(
            'city'
        ),
        //ID名称
        'id_name' => 'uId',
    ),
);
