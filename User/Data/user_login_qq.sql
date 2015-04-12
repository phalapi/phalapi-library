CREATE TABLE `tbl_user_login_qq` (
      `id` bigint(10) NOT NULL AUTO_INCREMENT,
      `qq_openid` varchar(28) DEFAULT '' COMMENT 'QQ的OPENID',
      `qq_token` varchar(150) DEFAULT '' COMMENT 'QQ的TOKEN',
      `qq_expires_in` int(10) DEFAULT '0' COMMENT 'QQ的失效时间',
      `user_id` bigint(10) DEFAULT '0' COMMENT '绑定的用户ID',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
