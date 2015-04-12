CREATE TABLE `tbl_user_login_sina` (
      `id` bigint(10) NOT NULL AUTO_INCREMENT,
      `sina_openid` varchar(28) DEFAULT '' COMMENT '新浪微博的OPENID',
      `sina_token` varchar(150) DEFAULT '' COMMENT '新浪微博的TOKEN',
      `sina_expires_in` int(10) DEFAULT '0' COMMENT '新浪微博的失效时间',
      `user_id` bigint(10) DEFAULT '0' COMMENT '绑定的用户ID',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


