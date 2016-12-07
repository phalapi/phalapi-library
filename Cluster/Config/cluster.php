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
        //数据库通用属性,如果不通用可以去掉一些属性,在db_list分别配置
        'general'    => array(
            'user'     => 'root',                  //数据库用户名
            'password' => '',                        //数据库密码
            'port'     => '3306',                  //数据库端口
            'charset'  => 'UTF8',                  //数据库字符集
        ),
        //配置数据库集群的地址和数据库名(可以在一台mysql上配置4个数据库模拟集群)
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
        //集群分布配置
        'list'    => array(
            'demo'  => array(
                //使用demo集群配置最大ID和最小ID,最大ID为0等于不上限
                'id_min' => 0,
                'id_max' => 0,
            ),
        ),
        //where查询条件放到衍生表中的字段
        'where'   => array(
            'city'
        ),
        //ID名称
        'id_name' => 'uId',
    ),
);
