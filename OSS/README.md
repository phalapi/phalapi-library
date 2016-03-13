#PhalApi-OSS -- 阿里云OSS包

![](http://webtools.qiniudn.com/master-LOGO-20150410_50.jpg)

##前言

日常大家都会选择文件服务器,阿里云的OSS当然是个不错的选择,可以存放大量的图片以及压缩文件等,还可以开启cdn加速,但是使用起来并不是那么的舒服,所以对OSS进行了封装希望大家喜欢!

附上:

官网地址:[http://www.phalapi.net/](http://www.phalapi.net/ "PhalApi官网")

开源中国Git地址:[http://git.oschina.net/dogstar/PhalApi/tree/release](http://git.oschina.net/dogstar/PhalApi/tree/release "开源中国Git地址")

开源中国拓展Git地址:[http://git.oschina.net/dogstar/PhalApi-Library](http://git.oschina.net/dogstar/PhalApi-Library "开源中国Git地址")


##1. 安装使用

我们第一步需要配置好我们OSS一些参数,默认读取是配置文件sys.php中的一下两个参数

    'OSS_ACCESS_ID'       => '',
    'OSS_ACCESS_KEY'      => '',

此扩展只需要简单的把文件放到Library目录下即可使用使用方法如下:

        $oss_sdk_service = new OSS_Lite();

        //设置是否打开curl调试模式
        $oss_sdk_service->set_debug_mode(FALSE);
        $bucket   = "test";
        $filePath  = "/file/zip.zip";
        $filename  = "test.zip";
        $response = $oss_sdk_service->upload_file_by_file($bucket, 'FILE/' . $filename, $filePath);
        if ($response->status != 200) {
            throw new PhalApi_Exception_BadRequest(T('OSS ERROR'));
        }
        
当然这是一个最简单的文件上传,具体其他详细的操作可以参考阿里云OSS手册:

https://help.aliyun.com/document_detail/oss/sdk/php-sdk/install.html?spm=5176.383663.13.4.FwOIL6

##2. 总结

希望此拓展能够给大家带来方便以及实用!

注:笔者能力有限有说的不对的地方希望大家能够指出,也希望多多交流!

**官网QQ交流群:421032344  欢迎大家的加入!**