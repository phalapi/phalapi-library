CREATE TABLE `tbl_user_login_weixin` (
      `id` bigint(10) NOT NULL AUTO_INCREMENT,
      `wx_openid` varchar(28) DEFAULT '' COMMENT '微信OPENID',
      `wx_token` varchar(150) DEFAULT '' COMMENT '微信TOKEN',
      `wx_expires_in` int(10) DEFAULT '0' COMMENT '微信失效时间',
      `user_id` bigint(10) DEFAULT '0' COMMENT '绑定的用户ID',
      PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
