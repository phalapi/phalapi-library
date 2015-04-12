CREATE TABLE `tbl_user` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'UID',
      `username` varchar(100) NOT NULL DEFAULT '' COMMENT '用户名',
      `nickname` varchar(50) DEFAULT '' COMMENT '昵称',
      `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
      `salt` varchar(32) DEFAULT NULL COMMENT '随机加密因子',
      `reg_time` int(11) DEFAULT '0' COMMENT '注册时间',
      `avatar` varchar(255) DEFAULT '' COMMENT '头像',
      PRIMARY KEY (`id`),
      UNIQUE KEY `username_unique_key` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

