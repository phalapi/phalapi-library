<?php

return array(
    //请将以下配置拷贝到 ./Config/app.php 文件对应的位置中，未配置的表将使用默认路由
    'auth' => array(
        'auth_on' => true, // 认证开关
        'auth_user' => 'user', // 用户信息表
        'auth_group' => 'auth_group', // 组数据表名
        'auth_group_access' => 'auth_group_access', // 用户-组关系表
        'auth_rule' => 'auth_rule', // 权限规则表
        'auth_not_check_user' => array() //跳过权限检测的用户
    )
);


