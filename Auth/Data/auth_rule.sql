CREATE TABLE `phalapi_auth_rule` (
                  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                  `name` char(80) NOT NULL DEFAULT '',
                  `title` char(20) NOT NULL DEFAULT '',
                  `status` tinyint(1) NOT NULL DEFAULT '1',
                  `add_condition` char(100) NOT NULL DEFAULT '',
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `name` (`name`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;