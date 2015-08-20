
    CREATE TABLE `phalapi_auth_rule` (
                  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                  `name` char(80) NOT NULL DEFAULT '',
                  `title` char(20) NOT NULL DEFAULT '',
                  `type` tinyint(1) NOT NULL DEFAULT '1',
                  `status` tinyint(1) NOT NULL DEFAULT '1',
                  `add_condition` char(100) NOT NULL DEFAULT '',
                  `mid` tinyint(3) unsigned NOT NULL DEFAULT '0',    #新增,外键,和tk_modules的id对应,对规则分类处理,方便管理
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `name` (`name`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;