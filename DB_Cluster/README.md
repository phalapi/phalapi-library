#基于PhalApi的DB集群拓展 V0.1bate

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

##前言##
***先在这里感谢phalapi框架创始人@dogstar,为我们提供了这样一个优秀的开源框架.***

编写本次拓展出于的目的是解决大量数据写入分析的问题,也希望本拓展能对大家有些帮助,能够解决大家遇到的同样的问题.

**注:V0.1bate版本,很多功能尚不完善,只提供技术交流使用,请不要用户生产环境**

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")

##1.起因##

说到为什么写这个拓展,起因是这样的,在和产品交流的时候他们希望可以**存一些东西作为数据分析用**,我考虑过hadoop但是如果说使用hadoop需要投入的成本太高了,在想有没有什么好办法的时候,想到了分表分库解决数据量大的问题,那么可以有一个封装好的服务就和操作数据库一样操作可以达到良好的分表分库的效果吗,出于这个考虑就开始这个拓展的编写.

##2.业务场景##

1. 大量select
	
	当一个数据库需要对付大量的select请求的时候,我们往往会想到使用读写分离来解决此类问题,一个写库多个读库,一台或多台服务器用一个读库,所有的写入操作使用主库操作,应为是大量的select操作,读的压力被分配到了很多个读库实例,可以很好的解决问题大量select的问题,再者就是进行添加缓存机制的优化,这样也是能很好的解决问题

2. 大量的insert

	对于大量insert上面所谓的读写分离完全不够看了,所有的压力全部会集中在负责写入的主库,但并不是应为并发请求的问题,问题是在于数据量大导致不管是干嘛都会慢,当数据量到了上亿的级别简直不敢想像,如果是通过分表分库(如果是4库4表也就是16张表),数据分均衡的分配到(库数量-乘-表数量)这么多张表里面从而达到解决大量数据的问题(在分表分库前面有一个主表),当然他也有缺陷就是当进行条件查询的时候最坏的条件会遍历(库数量-乘-表数量)这么多张表才能获得想要的结果,所以不是很建议用到查询列表比较平凡的应用中,当然结合缓存和读写分离可以缓解压力

##3.使用拓展##

###3.1 下载/注册拓展###

大家可以到Git项目[PhalApi Library](http://git.oschina.net/dogstar/PhalApi-Library "PhalApi Library")中下载下来,找到其中的**DB_Cluster**拓展复制到/PhalApi/Library目录下,如下:



	引入集群拓展拓展库
	DI()->loader->addDirs('Library/DB_Cluster');
	初始化配置文件
	DI()->Cluster_DB = new Cluster_Access(DI()->config->get('cluster'));
