#PhalApi-Excel

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

##前言

使用PHP导出Excel我们经常用到,也有很多好的拓展比如知名的**PHPExcel和PHP-ExcelReader**,我们在读取Excel是毋庸置疑使用PHP-ExcelReader是最好的选择,但是在导出的时候
使用PHPExcel颇为复杂,有没有一种简单的方式呢?笔者找到了一个导出Excel封装比较舒服的类包,只需要传入一个array既可完成导出功能,分享出来提供使用!

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")

开源中国拓展Git地址:[http://git.oschina.net/dogstar/PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library "开源中国Git地址")


##1. 安装

安装只需要你把下载的Excel文件放到你的项目目录的Library中即可开始使用,开始使用只需要初始化即可开始

需要传入三个参数分别为

    //使用编码(默认为utf - 8)
    //是否需要对Number进行处理(默认false)
    //工作表的标题
    $xls = new Excel_Lite('UTF-8', false, 'My Test Sheet');

##2. Demo

非常简单的使用方式
    
    $data = array(
        1 => array('Name', 'Surname'),
        array('Schwarz', 'Oliver'),
        array('Test', 'Peter')
    );
    
    $xls = new Excel_Lite('UTF-8', false, 'My Test Sheet');
    $xls->addArray($data);
    $xls->generateXML('my-test');

##3. 总结

希望此拓展能够给大家带来方便以及实用,暂时只支持容联云如有其他童鞋希望能加入其余常用运营商可与笔者进行联系!

注:笔者能力有限有说的不对的地方希望大家能够指出,也希望多多交流!

**官网QQ交流群:421032344  欢迎大家的加入!**