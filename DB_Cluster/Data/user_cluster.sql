--PhalApi-DB集群-SQL文件(这里是用的是Mysql)
--本次集群采取4库每一库4表 4*4共16表的mysql集群(基础库不算在里面)

--基础库(id自增长,用表索引进行列表查询条件)
--库名project
--当自己建立集群mysql的时候要注意以下几点
--1.一定要注意ID要加上自动增长,这里进行的分表分库都是基于自增ID进行的,如果是自定义字符串ID需要进行算法修改,也可以使用其他缓存生成自增ID
--2.除了ID之外的字段(用于按条件查询列表ID)一定要加上索引或者是主键,不然数据量大的时候获取列表ID会很慢
--3.除了ID之外的字段一定要是更具业务需求进行查询比较频繁的,而且要保持尽量的少1-2个,大于2个建议在分出一张表做对应

DROP TABLE IF EXISTS `user_base`;
CREATE TABLE `user_base` (
  `uId` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`uId`),
  KEY `city` (`city`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--库名分表为user_cluster0,user_cluster1,user_cluster2,user_cluster3
--下面四张表为每一个库中都拥有的4张表(注意ID不能使用自动增长)
--user_0
--user_1
--user_2
--user_3
DROP TABLE IF EXISTS `user0`;
CREATE TABLE `user0` (
  `uId` int(11) NOT NULL DEFAULT '0',
  `name` varchar(32) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `phone` int(32) DEFAULT NULL,
  PRIMARY KEY (`uId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user1`;
CREATE TABLE `user1` (
  `uId` int(11) NOT NULL DEFAULT '0',
  `name` varchar(32) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `phone` int(32) DEFAULT NULL,
  PRIMARY KEY (`uId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user2`;
CREATE TABLE `user2` (
  `uId` int(11) NOT NULL DEFAULT '0',
  `name` varchar(32) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `phone` int(32) DEFAULT NULL,
  PRIMARY KEY (`uId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user3`;
CREATE TABLE `user3` (
  `uId` int(11) NOT NULL DEFAULT '0',
  `name` varchar(32) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `phone` int(32) DEFAULT NULL,
  PRIMARY KEY (`uId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;











