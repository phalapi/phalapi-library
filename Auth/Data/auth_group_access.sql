-- ----------------------------
-- phalapi_auth_group_access 用户组明细表
-- uid:用户id，group_id：用户组id
-- ----------------------------
CREATE TABLE `phalapi_auth_group_access` (
    `uid` mediumint(8) unsigned NOT NULL,  
    `group_id` mediumint(8) unsigned NOT NULL, 
    UNIQUE KEY `uid_group_id` (`uid`,`group_id`),  
    KEY `uid` (`uid`), 
    KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;